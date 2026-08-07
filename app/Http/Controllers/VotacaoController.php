<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabalho;
use App\Models\AvaliadorTrabalho;
use Illuminate\Support\Facades\DB;

class VotacaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'trabalho_id' => 'required|integer|exists:trabalhos,id',
            'avaliador_id' => 'required|integer|exists:tb_usuarios,id',
            'resumo' => 'required|numeric|max:10',
            'importancia' => 'required|numeric|max:10',
            'apresentacao' => 'required|numeric|max:10',
            'desempenho' => 'required|numeric|max:10',
        ]);

        // 1. Salva ou atualiza a nota na tabela avaliador_trabalhos
        AvaliadorTrabalho::updateOrCreate(
            [
                'trabalhos_id' => $request->trabalho_id,
                'usuario_id' => $request->avaliador_id,
            ],
            [
                'resumo' => $request->resumo,
                'importancia' => $request->importancia,
                'apresentacao' => $request->apresentacao,
                'desempenho' => $request->desempenho,
            ]
        );

        // 2. Calcula as médias globais do trabalho para manter a tabela 'votacoes' atualizada
        $medias = DB::table('avaliador_trabalhos')
            ->where('trabalhos_id', $request->trabalho_id)
            ->selectRaw('
                AVG(resumo) as media_resumo,
                AVG(importancia) as media_importancia,
                AVG(apresentacao) as media_apresentacao,
                AVG(desempenho) as media_desempenho
            ')
            ->first();

        $mediaFinal = $medias->media_resumo + $medias->media_importancia + $medias->media_apresentacao + $medias->media_desempenho;

   
        DB::table('votacoes')->updateOrInsert(
            [
                'trabalho_id' => $request->trabalho_id,
                'usuario_id'  => $request->avaliador_id,
            ],
            [
                'media_resumo' => $medias->media_resumo,
                'media_importancia' => $medias->media_importancia,
                'media_apresentacao' => $medias->media_apresentacao,
                'media_desempenho' => $medias->media_desempenho,
                'media_final' => $mediaFinal,
            ]
        );

        return redirect()->back()->with('success', 'Votação registrada com sucesso.');
    }
    
    public function resultados()
    {
        $ano = date('Y');

        $trabalhos = Trabalho::query()
            ->join('votacoes as v', 'trabalhos.id', '=', 'v.trabalho_id')
            ->join('tb_inscricoes as i', 'trabalhos.inscricao_id', '=', 'i.id')
            ->select(
                'trabalhos.id',
                'v.media_final',
                'i.nome_trabalho',
                'i.nome_autor',
                'i.categoria'
            )
            ->with(['avaliadores:tb_usuarios.id,tb_usuarios.nome'])
            ->where('trabalhos.ano', $ano)
            ->orderBy('i.categoria')
            ->orderBy('v.media_final', 'desc')
            ->get();

        $votacoesPorCategoria = $trabalhos->groupBy('categoria');

        return view('app.votacoes', compact('votacoesPorCategoria'));
    }
}
