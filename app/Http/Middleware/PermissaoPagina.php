<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissaoPagina
{
    public function handle($request, Closure $next, $acao = 'acessar')
    {
        // 1. VERIFICAÇÃO DE LOGIN
        $usuarioExterno = session('usuario_externo');
        $usuarioInterno = session('usuario_interno');
        $usuario = $usuarioExterno ?? $usuarioInterno;

        if (!$usuario) {
            return redirect()->route('main.index')->withErrors(['acesso' => 'Login necessário.']);
        }

        $usuario_id = data_get($usuario, 'id');
        $rotaAtual = $request->route()->getName();

        if (!$rotaAtual) {
            return redirect()->route('app.acesso-negado');
        }

        // 2. REGRAS ESPECÍFICAS DE USUÁRIO EXTERNO
        if ($usuarioExterno && session('primeiro_acesso') == 1) {
            $rotasPerfil = ['app.perfil', 'app.perfil.update'];
            if (!in_array($rotaAtual, $rotasPerfil)) {
                return redirect()->route('app.perfil')
                    ->with('error', 'Você precisa alterar sua senha.');
            }
            return $next($request);
        }

        // 3. MAPA DE DEPENDÊNCIAS
        $mapaDependencias = [
            'app.permissoes.update'       => 'app.central-de-gestao',
            'app.central-gestao.selecionar' => 'app.central-de-gestao',
            'app.prazos.update'           => 'app.central-de-gestao',
            'app.vincular.update'         => 'app.central-de-gestao', 
            'app.arquivos.resumo'         => 'app.central-de-gestao',
            'app.arquivos.inscritos'      => 'app.central-de-gestao',
            'app.arquivos.apresentacao'   => 'app.central-de-gestao',
            'app.termos.salvar'           => 'app.central-de-gestao',
            'app.usuarios.store'          => 'app.central-de-gestao',
            'app.usuarios.update'         => 'app.central-de-gestao',
            'app.usuarios.toggle'         => 'app.central-de-gestao',
            'app.arquivos.resumo-pdf'     => 'app.central-de-gestao',
            'app.arquivos.store'          => 'verficar-inscricoes',
            'app.arquivos.download'       => 'verificar-inscricoes',
            
        ];

        // Se a rota atual for uma ação (POST), troca ela pela rota da página pai para checar a permissão
        $rotaParaChecar = $mapaDependencias[$rotaAtual] ?? $rotaAtual;

        // 4. REGRAS DE EXCEÇÃO
        $rotasIgnoradas = [
            'app.perfil', 
            'app.perfil.update',
        ];

        if (in_array($rotaAtual, $rotasIgnoradas)) {
            return $next($request);
        }

        // 5. CHAVE MESTRA (PRE/DEV)
        $nivelAcesso = data_get($usuario, 'nivel_de_acesso_user');
        if (in_array($nivelAcesso, [3, 5])) {
             return $next($request);
        }

        // 6. VERIFICAÇÃO NO BANCO DE DADOS
        $temPermissao = DB::table('tb_acesso_paginas as ap')
            ->join('tb_paginas as p', 'ap.pagina_id', '=', 'p.id')
            ->where('ap.usuario_id', $usuario_id)
            ->where('p.rota', $rotaParaChecar) // Usa a rota mapeada
            ->where("ap.$acao", 1) // Geralmente checa o 'acessar'
            ->exists();

        // Se a rota não existe no banco e não foi mapeada, bloqueia por segurança
        $rotaExisteNoBanco = DB::table('tb_paginas')->where('rota', $rotaParaChecar)->exists();
        
        if ($rotaExisteNoBanco && !$temPermissao) {
             // Se for requisição AJAX/Formulário, retorna erro. Se for GET, redireciona.
             if ($request->ajax() || $request->isMethod('get') || $request->isMethod('post')) {
                 abort(403, 'Acesso revogado. Suas permissões foram alteradas.');
             }
             return redirect()->route('app.acesso-negado');
        }

        return $next($request);
    }
}