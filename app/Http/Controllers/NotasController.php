<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabalho;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    public function index()
    {
        $ano = date('Y');

        $trabalhos = Trabalho::query()
            ->join('tb_inscricoes as i', 'trabalhos.inscricao_id', '=', 'i.id')
            ->join('votacoes as v', 'trabalhos.id', '=', 'v.trabalho_id')
            ->where('trabalhos.ano', $ano)
            ->select(
                'trabalhos.id',
                'i.categoria',
                'i.nome_trabalho',
                'i.nome_autor',
                'v.media_resumo',
                'v.media_importancia',
                'v.media_apresentacao',
                'v.media_desempenho',
                'v.media_final'
            )
            ->orderBy('i.categoria', 'asc')
            ->orderBy('v.media_final', 'desc')
            ->get();

        // Agrupa por categoria para facilitar a exibição na View
        $rankingPorCategoria = $trabalhos->groupBy('categoria');

        return view('app.notas-gerais', compact('rankingPorCategoria'));
    }
}