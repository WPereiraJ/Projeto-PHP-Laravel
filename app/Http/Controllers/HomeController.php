<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $anoAtual = date('Y');

        $dataBanco = DB::table('tb_datas_evento')
            ->whereYear('data_inicio_evento', $anoAtual)
            ->whereYear('data_final_evento', $anoAtual)
            ->first();

        // VALOR PADRÃO
        $dataExibicao = 'Data a definir';

        if ($dataBanco && !empty($dataBanco->data_inicio_evento) && !empty($dataBanco->data_final_evento)) {

            $inicio = Carbon::parse($dataBanco->data_inicio_evento)->format('d/m/Y');
            $fim = Carbon::parse($dataBanco->data_final_evento)->format('d/m/Y');

            // Monta a string completa aqui
            $dataExibicao = "{$inicio} a {$fim}";
        }

        $evento = [
            'data_completa' => $dataExibicao,
            'local' => $dataBanco->local ?? '[LOCAL EVENTO]'
        ];

        $fotos = [
            'foto_evento1.jpeg',
            'foto_evento2.jpeg',
            'foto_evento3.jpeg',
            'foto_evento4.jpeg',
        ];

        return view('main.index', compact('evento', 'fotos'));
    }
}