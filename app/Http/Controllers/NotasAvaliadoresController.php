<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\AvaliadorTrabalho;
use Illuminate\Support\Facades\Auth;

class NotasAvaliadoresController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $ano = date('Y');
        $camposInscricao = 'id,categoria,nome_trabalho,nome_autor';

        $search = $request->input('search');

        if ($user->nivel_de_acesso_user == 0) {
            $notas = AvaliadorTrabalho::with(['trabalho.inscricao:' . $camposInscricao])
                ->where('usuario_id', $user->id)
                ->whereHas('trabalho', function ($q) use ($ano) {
                    $q->where('ano', $ano);
                })
                ->get();

            $notasAgrupadas = ['Minhas Avaliações' => $notas];
            $paginacao = null;
        } else {
            // --- CENÁRIO PRESIDENTE/COMISSÃO: Busca com Filtro ---

            // Inicia a Query
            $query = Usuario::whereHas('avaliacoes_realizadas.trabalho', function ($q) use ($ano) {
                $q->where('ano', $ano);
            });

            // SE TIVER BUSCA: Filtra pelo nome do avaliador
            if ($search) {
                $query->where('nome', 'LIKE', "%{$search}%");
            }

            // Continua com os relacionamentos e paginação
            $avaliadores = $query->with([
                'avaliacoes_realizadas' => function ($query) use ($ano, $camposInscricao) {
                    $query->whereHas('trabalho', function ($q) use ($ano) {
                        $q->where('ano', $ano);
                    })
                        ->with(['trabalho.inscricao:' . $camposInscricao]);
                }
            ])
                ->orderBy('nome')
                ->paginate(10);

            //Mantém o termo de busca nos links das próximas páginas
            $avaliadores->appends(['search' => $search]);

            $notasAgrupadas = collect();
            foreach ($avaliadores as $avaliador) {
                $notasAgrupadas->put($avaliador->nome, $avaliador->avaliacoes_realizadas);
            }

            $paginacao = $avaliadores;
        }

        return view('app.notas-avaliadores', compact('notasAgrupadas', 'paginacao'));
    }
}