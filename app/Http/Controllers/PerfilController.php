<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class PerfilController extends Controller
{
    public function index()
    {
        if (session()->has('usuario_interno')) {
            return redirect()->route('app.acesso-negado');
        }

        $dadosUsuario = session('usuario_externo');

        if (!$dadosUsuario) {
            return redirect('/');
        }

        return view('app.perfil', compact('dadosUsuario'));
    }

    public function update(Request $request)
    {
        if (session()->has('usuario_interno')) {
            return redirect()->route('app.acesso-negado');
        }

        $usuario = session('usuario_externo');
        $id = data_get($usuario, 'id');

        // Validações
        if (session('primeiro_acesso') == 1) {
            $request->validate([
                'nova_senha' => 'required|min:6|confirmed',
                'nome' => 'required|string|max:255'
            ]);
        } else {
            $request->validate([
                'nome' => 'required|string|max:255',
                'nova_senha' => 'nullable|min:6|confirmed',
            ]);
        }

        $updateData = ['nome' => $request->nome];

        if ($request->filled('nova_senha')) {
            // Busca o utilizador no banco para comparar os Hashes
            $userDb = DB::table('tb_usuarios')->where('id', $id)->first();

            // Verifica se a nova senha é igual à atual
            if (Hash::check($request->nova_senha, $userDb->senha)) {
                return back()->withErrors(['nova_senha' => 'A nova senha não pode ser igual à senha atual.']);
            }

            // Se NÃO for primeiro acesso, exige a confirmação da senha antiga
            if (session('primeiro_acesso') != 1) {
                if (!$request->filled('senha_atual')) {
                    return back()->withErrors(['senha_atual' => 'Informe a senha atual para realizar a alteração.']);
                }

                if (!Hash::check($request->senha_atual, $userDb->senha)) {
                    return back()->withErrors(['senha_atual' => 'A senha atual informada está incorreta.']);
                }
            }

            $updateData['senha'] = Hash::make($request->nova_senha);
            $updateData['primeiro_acesso'] = 0;
        }

        DB::table('tb_usuarios')->where('id', $id)->update($updateData);

        $usuario['nome'] = $request->nome;
        session(['usuario_externo' => $usuario]);

        // === LÓGICA DE REDIRECIONAMENTO ===
        if ($request->filled('nova_senha')) {
            session(['primeiro_acesso' => 0]);

            $paginasPermitidas = session('menu_paginas');
            $rotaDestino = null;

            if ($paginasPermitidas && $paginasPermitidas->count() > 0) {
                $prioridades = [
                    'app.apresentacoes',
                    'app.central-de-gestao',
                    'app.verificar-inscricoes',
                    'app.resumos'
                ];

                foreach ($prioridades as $rotaPrioritaria) {
                    if ($paginasPermitidas->contains('rota', $rotaPrioritaria) && Route::has($rotaPrioritaria)) {
                        $rotaDestino = $rotaPrioritaria;
                        break;
                    }
                }

                // Se não encontrar nas prioridades, escolhe a primeira disponível
                if (!$rotaDestino) {
                    $fallback = $paginasPermitidas->first()->rota ?? null;
                    if ($fallback && Route::has($fallback)) {
                        $rotaDestino = $fallback;
                    }
                }
            }

            // Faz o redirecionamento
            if ($rotaDestino) {
                return redirect()->route($rotaDestino)
                    ->with('success', 'Sua senha foi definida com sucesso! Bem-vindo.');
            }

            // Fallback caso ocorra algum problema 
            return redirect()->route('app.acesso-negado')
                ->with('success', 'Sua senha foi definida com sucesso! Mas não encontramos nenhuma página disponivel para você.');
        }

        return back()->with('success', 'Perfil atualizado!');
    }
}