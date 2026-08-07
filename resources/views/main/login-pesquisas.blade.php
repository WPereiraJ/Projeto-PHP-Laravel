@extends('main.layouts.main-auth')

@section('titulo', 'Login - Pesquisa')
@section('css_path', 'css/login-novo.css')

@section('conteudo')

<section class="login-section">
    
    <div class="login-card fade-in-up">
        
        <div class="card-body p-4 p-md-5">
            
            <div class="text-center">
                <img src="{{ asset('img/) }}" 
                     alt="Logo [NOME DO EVENTO]" 
                     class="login-logo img-fluid mb-3" 
                     style="max-width: 150px; height: auto;">
                
                <h5 class="text-muted font-weight-bold mb-4">Área do Inscrito</h5>
            </div>

            @component('main.layouts._components.forms-login-pesquisa')
            @endcomponent

        </div>

        <div class="login-footer">
            <p class="mb-0">
                Ainda não tem conta? <br>
                <a href="{{ route('inscricao') }}" class="link-cadastro">
                    Cadastre-se
                </a>
            </p>
        </div>

    </div>

</section>

@endsection