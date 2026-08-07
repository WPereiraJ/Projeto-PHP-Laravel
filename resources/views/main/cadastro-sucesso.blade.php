@extends('main.layouts.main-auth')

@section('titulo', 'Cadastro Realizado!')

{{-- Chama o CSS Externo --}}
@section('css_path', 'css/success.css')

@section('conteudo')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            
            <div class="success-card fade-in-up">
                
                {{-- Animação --}}
                <div class="checkmark-wrapper">
                    <div class="checkmark-circle">
                        <div class="background"></div>
                        <div class="checkmark draw"></div>
                    </div>
                </div>

                <h2 class="success-title">Cadastro Realizado!</h2>
                <p class="success-message">
                    Sua inscrição foi submetida com sucesso. <br>
                    Enviamos um e-mail de confirmação para você.
                </p>
                
                <hr>

                <p class="redirect-text">
                    Você será redirecionado para o login em <span id="countdown">5</span> segundos...
                </p>

                <a href="{{ route('main.login-pesquisas') }}" class="btn btn-primary shadow-sm mt-3">
                    Ir para Login Agora
                </a>

                {{-- ELEMENTO OCULTO PARA PASSAR A ROTA AO JS --}}
                <div id="redirect-data" data-url="{{ route('main.login-pesquisas') }}" style="display:none;"></div>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/redirect.js') }}"></script>
@endpush