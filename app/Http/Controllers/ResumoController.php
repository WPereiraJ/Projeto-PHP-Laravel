<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabalho;
use App\Models\AvaliadorTrabalho;
use App\Models\Comissao;
use Illuminate\Support\Facades\DB;

class ResumoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ano = date('Y');
        $camposInscricao = 'id,categoria,nome_trabalho,nome_autor,resumo,orientador,termos_indexacao,sisgen,seg_contrato';

        $query = Trabalho::with([
            'inscricao:' . $camposInscricao,
            'minha_avaliacao_avaliador',
            'meu_parecer_comissao'
        ])
            ->where('ano', $ano)
            ->withCount([
                'avaliadores as total_vinculos',
                'avaliadores as total_avaliacoes' => function ($query) {
                    $query->whereNotNull('resumo');
                }
            ]);

        // Lógica de Permissão
        if (!in_array($user->nivel_de_acesso_user, [1, 3])) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('avaliadores', function ($f) use ($user) {
                    $f->where('usuario_id', $user->id);
                })
                    ->orWhereHas('comissoes', function ($f) use ($user) {
                        $f->where('usuario_id', $user->id);
                    });
            });
        }

        $trabalhos = $query->get();
        // Processamento em Memória
        $trabalhos->each(function ($trabalho) {
            if ($trabalho->total_avaliacoes == 0) {
                $trabalho->status_formatado = 'Não avaliado';
            } elseif ($trabalho->total_avaliacoes >= $trabalho->total_vinculos && $trabalho->total_vinculos > 0) {
                $trabalho->status_formatado = 'Avaliado';
            } else {
                $trabalho->status_formatado = 'Avaliação em andamento';
            }
        });
        $trabalhos = $query->get();

        // Agrupamento, Autores e Categorias
        $trabalhosPorCategoria = $trabalhos->groupBy(function ($item) {
            return $item->inscricao->categoria ?? 'Outros';
        })->map(function ($grupoDeTrabalhos) {
            // 1. Ordena os trabalhos DENTRO da categoria pelo nome do autor (A-Z)
            return $grupoDeTrabalhos->sortBy(function ($trabalho) {
                return strtolower($trabalho->inscricao->nome_autor ?? '');
            });
        })->sortBy(function ($trabalhos, $categoria) {
            // 2. Ordena as CATEGORIAS na ordem desejada
            $ordemDesejada = [
                'PIBIC/PIBIT' => 1,
                'Graduação' => 2,
                'Pós-Graduação' => 3,
            ];

            return $ordemDesejada[$categoria] ?? 99; // Se não encontrar, envia para o fim da lista
        });

        return view('app.resumos', compact('trabalhosPorCategoria'));
    }

    /**
     * Processa a avaliação baseada no nível do usuário.
     */
    public function avaliar(Request $request)
    {
        // 1. Validação
        $regras = [
            'trabalho_id' => 'required|exists:trabalhos,id',
        ];

        if (auth()->user()->nivel_de_acesso_user == 0) {
            $regras['qualidadeResumo'] = 'required|numeric|min:0|max:10';
            $regras['importanciaCientifica'] = 'required|numeric|min:0|max:10';
        }

        $request->validate($regras);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            $trabalho = Trabalho::find($request->trabalho_id);

            // --- AVALIADOR (Nível 0) ---
            if ($user->nivel_de_acesso_user == 0) {
                DB::table('avaliador_trabalhos')->updateOrInsert(
                    [
                        'trabalhos_id' => $request->trabalho_id,
                        'usuario_id' => $user->id
                    ],
                    [
                        'resumo' => $request->qualidadeResumo,
                        'importancia' => $request->importanciaCientifica
                    ]
                );
            }

            // --- COMISSÃO (1) e CLPI (2) ---
            elseif (in_array($user->nivel_de_acesso_user, [1, 2])) {

                $trabalho = Trabalho::findOrFail($request->trabalho_id);

                // 1. Salva o Comentário (Se houver)
                if ($request->filled('comentarios')) {
                    DB::table('tb_comissao')->updateOrInsert(
                        [
                            'trabalho_id' => $request->trabalho_id,
                            'usuario_id' => $user->id
                        ],
                        [
                            'comentarios' => $request->comentarios,
                            'inscricao_id' => $trabalho->inscricao_id
                        ]
                    );
                }

                // 2. Salva o Status CLPI 
                if ($user->nivel_de_acesso_user == 2 && $request->filled('clpi_aprovado')) {
                    $trabalho->clpi_aprovado = $request->clpi_aprovado;
                    $trabalho->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Avaliação realizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Illuminate\Support\Facades\Log::error("Erro avaliação: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erro ao salvar: ' . $e->getMessage())
                ->withInput();
        }
    }
}