@extends('app.layouts.main-app')

@section('css_path', 'css/global.css')
@section('titulo', 'Acesso Negado')
@section('conteudo')
    <style>
        /* Centraliza o conteúdo no body */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
    </style>
    <div class="text-center">
        <h1>
            ERROR 401<br>
            ACESSO NÃO AUTORIZADO
        </h1>
    </div>

@endsection
