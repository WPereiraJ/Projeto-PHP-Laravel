@extends('main.layouts.main-index')

@section('titulo', 'Login')

@section('conteudo')

    <div class="login-wrapper">

        {{-- Botão Voltar --}}
        <a href="{{ route('home') }}" class="btn-back-floating">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>

        {{-- Caixa Principal --}}
        <div class="login-box fade-in-up pt-4">

            {{-- Logo --}}
            @component('main.layouts._components.logo', ['classe' => 'logo'])
            @endcomponent

            {{-- Navegação em Abas (Pills) --}}
            <ul class="nav nav-pills nav-fill mb-4 mt-3" id="pills-tab" role="tablist"
                style="background: #f1f3f5; border-radius: 50px; padding: 5px;">
                <li class="nav-item">
                    <a class="nav-link active rounded-pill font-weight-bold" id="pills-externo-tab" data-toggle="pill"
                        href="#pills-externo" role="tab" aria-controls="pills-externo" aria-selected="true">
                        Participante
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill font-weight-bold" id="pills-evento-tab" data-toggle="pill"
                        href="#pills-evento" role="tab" aria-controls="pills-evento" aria-selected="false">
                        [NOME EVENTO]
                    </a>
                </li>
            </ul>

            {{-- Conteúdo das Abas --}}
            <div class="tab-content" id="pills-tabContent">

                {{-- ABA 1: Externo --}}
                <div class="tab-pane fade show active" id="pills-externo" role="tabpanel"
                    aria-labelledby="pills-externo-tab">
                    @component('main.layouts._components.forms-externo')
                    @endcomponent
                </div>

                {{-- ABA 2: ADM--}}
                <div class="tab-pane fade" id="pills-evento" role="tabpanel" aria-labelledby="pills-evento-tab">
                    <div class="alert alert-light border text-muted small text-center mb-3">
                        <i class="fas fa-info-circle mr-1"></i> Use suas credenciais de rede corporativa.
                    </div>
                    @component('main.layouts._components.forms-comissao')
                    @endcomponent
                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-ver-senha').on('click', function () {
                let input = $(this).closest('.input-group').find('input');
                let icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endpush