<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Trabalho;
use App\Models\Usuario;

class ApresentacaoController extends Controller
{
    public function index()
    {
        $anoAtual = date('Y');
        $dataAtual = date('Y-m-d');
        $nivel = session('perfil');
        $usuario = session('usuario_externo') ?? session('usuario_interno');
        $usuarioId = $usuario['id'];

        // 1. Colunas da Inscrição
        $colsInscricao = 'id,categoria,nome_trabalho,nome_autor';

        // 2. Colunas do Trabalho
        $colsTrabalho = ['id', 'inscricao_id', 'data_apresentacao', 'ano'];

        $query = Trabalho::select($colsTrabalho)
            ->with([
                'inscricao:' . $colsInscricao,
                'avaliadores:tb_usuarios.id,tb_usuarios.nome'
            ])
            ->where('ano', $anoAtual);

        // Lógica do Avaliador (Nível 0)
        if ($nivel == 0) {
            $query->whereHas('avaliadores', function ($q) use ($usuarioId) {
                $q->where('usuario_id', $usuarioId);
            })
                ->whereDate('data_apresentacao', $dataAtual);
        }

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
                'PIBIC/PIBIT'   => 1,
                'Graduação'     => 2,
                'Pós-Graduação' => 3,
            ];

            return $ordemDesejada[$categoria] ?? 99;
        });

        $instrucoes = \Illuminate\Support\Facades\Cache::remember('instrucoes_apresentacao', 60, function () {
            return DB::table('tb_termos_instrucoes')->value('instrucoes');
        });

        $exibir_modal = false;
        if ($nivel == 0 && !session()->has('modal_exibido')) {
            session(['modal_exibido' => true]);
            $exibir_modal = true;
        }

        return view('app.apresentacoes', compact(
            'trabalhosPorCategoria',
            'instrucoes',
            'exibir_modal',
            'nivel'
        ));
    }
}