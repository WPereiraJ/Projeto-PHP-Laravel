<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\CadastroUsuario;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_usuarios,email',
            'nivel_de_acesso' => 'required',
        ]);

        $userLogado = auth()->user();

        if (!$userLogado->podeCadastrarNivel($request->nivel_de_acesso)) {
            return redirect()->back()->with('error', 'Sem permissão para este nível.');
        }

        // Preparação de Variáveis
        $usuario = null;
        $senhaParaEmail = null;
        $matriculaParaEmail = null;
        $tipo = null;
        $isExterno = $request->has('usuario_externo');

        try {
            DB::beginTransaction();

            $senhaParaBanco = null;
            $tipo = $isExterno ? 'externo' : 'interno';

            if ($isExterno) {
                $senhaPlana = Str::random(10);
                $senhaParaEmail = $senhaPlana;
                $senhaParaBanco = Hash::make($senhaPlana);
            } else {
                $senhaParaEmail = 'Sua senha corporativa (Mmatricula)';
                $matriculaParaEmail = $request->matricula;
            }

            // 2. Criação do Usuário
            $usuario = Usuario::create([
                'nome' => $request->nome,
                'email' => strtolower($request->email),
                'nivel_de_acesso_user' => $request->nivel_de_acesso,
                'usuario_padrão' => $isExterno ? null : $request->matricula,
                'senha' => $senhaParaBanco,
                'ativo' => 1
            ]);

            // 3. Atribuição de Permissões
            $this->atribuirPermissoes($usuario);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro no banco: ' . $e->getMessage());
        }

        // 4. Envio de E-mail
        try {
            Mail::to($usuario->email)->queue(new CadastroUsuario(
                $usuario->nome,
                $usuario->email,
                $senhaParaEmail,
                $tipo,
                $matriculaParaEmail
            ));

            return redirect()->back()->with('success', 'Cadastro realizado com sucesso!');

        } catch (\Exception $e) {
            // Mesmo com erro no email, o usuário já está salvo.
            return redirect()->back()->with('error', 'Usuário salvo, mas houve erro no envio do e-mail.');
        }
    }

    private function atribuirPermissoes($usuario)
    {
        $mapa = [
            0 => ['app.apresentacoes', 'app.resumos', 'app.notas-avaliadores'],
            1 => ['app.apresentacoes', 'app.notas_avaliadores', 'app.notas-gerais', 'app.central-de-gestao', 'app.votacoes', 'app.resumos'],
            2 => ['app.resumos'],
            4 => ['app.central-de-gestao'],
            6 => ['app.verificar-inscricoes'],
        ];

        $rotasPermitidas = $mapa[$usuario->nivel_de_acesso_user] ?? [];

        $acessoTotal = in_array($usuario->nivel_de_acesso_user, [3, 5]);
        $todasPaginas = Cache::remember('todas_paginas_sistema', 86400, function () {
            return DB::table('tb_paginas')->select('id', 'rota')->get();
        });

        $dadosParaInserir = [];

        // Configurações de permissão calculadas uma única vez
        $perms = [
            'acessar' => 1,
            'editar' => in_array($usuario->nivel_de_acesso_user, [1, 2, 3, 5, 6]) ? 1 : 0,
            'incluir' => in_array($usuario->nivel_de_acesso_user, [1, 2, 3, 4, 5, 6]) ? 1 : 0,
            'excluir' => in_array($usuario->nivel_de_acesso_user, [1, 2, 3, 5, 6]) ? 1 : 0,
        ];

        foreach ($todasPaginas as $pagina) {
            // Se tiver acesso total OU se a rota estiver na lista permitida
            if ($acessoTotal || in_array($pagina->rota, $rotasPermitidas)) {
                $dadosParaInserir[] = [
                    'usuario_id' => $usuario->id,
                    'pagina_id' => $pagina->id,
                    'acessar' => $perms['acessar'],
                    'editar' => $perms['editar'],
                    'incluir' => $perms['incluir'],
                    'excluir' => $perms['excluir'],
                ];
            }
        }

        // Bulk Insert (Inserção em massa)
        if (count($dadosParaInserir) > 0) {
            DB::table('tb_acesso_paginas')->insert($dadosParaInserir);
        }
    }


    // 2. Alterar Usuário
    public function update(Request $request)
    {
        $userLogado = auth()->user();

        // 1. Busca o usuário alvo primeiro
        $userAlvo = Usuario::findOrFail($request->avaliador_id_edit);

        // 2. Valida permissão específica sobre AQUELE usuário
        if (!$userLogado->podeGerenciarUsuario($userAlvo)) {
            return redirect()->back()->with('error', 'Você não tem permissão para alterar este usuário específico (Restrição de Nível).');
        }

        $validated = $request->validate([
            'nome_edit' => 'nullable|string|max:255',
            'email_edit' => 'nullable|email|unique:tb_usuarios,email,' . $userAlvo->id,
            'nivel_de_acesso' => 'required|integer',
        ]);

        // 3. Valida se pode mudar para o NOVO nível desejado (opcional, mas recomendado)
        if (!$userLogado->podeCadastrarNivel($validated['nivel_de_acesso']) && $validated['nivel_de_acesso'] != $userAlvo->nivel_de_acesso_user) {
            return redirect()->back()->with('error', 'Você não pode promover/rebaixar para esse nível.');
        }

        $userAlvo->update([
            'nome' => $validated['nome_edit'] ?? $userAlvo->nome,
            'email' => $validated['email_edit'] ?? $userAlvo->email,
            'nivel_de_acesso_user' => $validated['nivel_de_acesso']
        ]);

        return redirect()->back()->with('success', 'Usuário alterado com sucesso!');
    }

    // Desativar/Ativar Usuario
    public function toggleStatus(Request $request)
    {
        $userLogado = auth()->user();

        $usuarioAlvo = Usuario::findOrFail($request->usuario_id);

        // Valida permissão específica
        if (!$userLogado->podeGerenciarUsuario($usuarioAlvo)) {
            return redirect()->back()->with('error', 'Você não tem permissão para desativar/ativar este usuário (Restrição de P&D/Dev).');
        }

        $usuarioAlvo->ativo = $usuarioAlvo->ativo == 1 ? 0 : 1;
        $usuarioAlvo->save();

        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
    }
}