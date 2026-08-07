<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SidebarComposer
{
    /**
     * Vincula os dados à view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // 1. Identifica o usuário logado (interno ou externo)
        $usuarioExterno = session('usuario_externo');
        $usuarioInterno = session('usuario_interno');
        $userSide = $usuarioExterno ?? $usuarioInterno;

        $paginasOrdenadas = collect([]);

        if ($userSide) {
            $userSideId = data_get($userSide, 'id');
            $userSideNivel = data_get($userSide, 'nivel_de_acesso_user');

            // 2. Chave Mestra (Admin/Dev visualizam tudo)
            $isAdminSide = in_array($userSideNivel, [3, 5]);

            // 3. Busca no Banco de Dados
            if ($isAdminSide) {
                // Se for Admin, busca todas as páginas
                $paginasParaExibir = DB::table('tb_paginas')
                    ->select('nome', 'rota')
                    ->get();
            } else {
                // Se for usuário comum, busca apenas permissões 'acessar = 1'
                $paginasParaExibir = DB::table('tb_acesso_paginas as ap')
                    ->join('tb_paginas as p', 'ap.pagina_id', '=', 'p.id')
                    ->where('ap.usuario_id', $userSideId)
                    ->where('ap.acessar', 1)
                    ->select('p.nome', 'p.rota')
                    ->get();
            }

            // 4. Definição da Ordem de Prioridade (Menu)
            $ordemPrioridade = [
                'app.apresentacoes' => 1,
                'app.central-de-gestao' => 2,
                'app.verificar-inscricoes' => 3,
                'app.resumos' => 4,
                'app.votacoes' => 5,
                'app.notas-avaliadores' => 6,
                'app.notas-gerais' => 7,
                'app.home-pesquisas' => 8,
            ];

            // 5. Ordena a coleção vinda do banco
            $paginasOrdenadas = $paginasParaExibir->sortBy(function ($pagina) use ($ordemPrioridade) {
                // Se a rota não estiver na lista, joga pro final (peso 100)
                return $ordemPrioridade[$pagina->rota] ?? 100;
            });
        }

        // 6. Envia a variável $paginasOrdenadas para a View
        $view->with('paginasOrdenadas', $paginasOrdenadas);
    }
}