<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcessoPagina;
use Illuminate\Support\Facades\DB;

class PermissaoController extends Controller
{
    public function update(Request $request)
    {
        // 1. SEGURANÇA: Verificar se quem está salvando é Presidente (3) ou Dev (5)
        // Pega o usuário da sessão (externo ou interno)
        $usuarioLogado = session('usuario_externo') ?? session('usuario_interno');
        $nivelLogado = data_get($usuarioLogado, 'nivel_de_acesso_user');

        if (!in_array($nivelLogado, [3, 5])) {
            return redirect()->back()
                ->withErrors(['error' => 'Ação não autorizada. Apenas Presidentes e Desenvolvedores podem alterar permissões.']);
        }

        // 2. VALIDAÇÃO
        $request->validate([
            'usuario_id' => 'required|exists:tb_usuarios,id',
            'permissoes' => 'array'
        ]);

        $usuarioIdAlvo = $request->input('usuario_id');
        $permissoesEnviadas = $request->input('permissoes', []);

        DB::beginTransaction(); // Garante que salva tudo ou nada

        try {
            // 3. PROCESSAMENTO
            // Percorre cada página enviada no formulário
            foreach ($permissoesEnviadas as $paginaId => $acoes) {

                // Prepara os dados (converte checkbox "on" para 1, e ausência para 0)
                $dados = [
                    'acessar' => isset($acoes['acessar']) ? 1 : 0,
                    'incluir' => isset($acoes['incluir']) ? 1 : 0,
                    'editar' => isset($acoes['editar']) ? 1 : 0,
                    'excluir' => isset($acoes['excluir']) ? 1 : 0,
                ];

                // Atualiza ou Cria o registro na tabela tb_acesso_paginas
                // Ele procura pelo par [usuario_id, pagina_id]
                // Se achar, atualiza os $dados. Se não achar, cria novo.
                DB::table('tb_acesso_paginas')->updateOrInsert(
                    // 1. Condição para buscar (WHERE)
                    [
                        'usuario_id' => $usuarioIdAlvo,
                        'pagina_id' => $paginaId
                    ],
                    // 2. Dados para Inserir ou Atualizar
                    $dados
                );
            }

            DB::commit();

            // Redireciona de volta mantendo o ID do usuário na URL para a tela não ficar branca
            return redirect()->route('app.central-de-gestao')
                ->with('success', 'Permissões atualizadas com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao salvar permissões: ' . $e->getMessage()]);
        }
    }
}