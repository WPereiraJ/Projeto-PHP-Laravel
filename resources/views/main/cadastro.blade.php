@extends('main.layouts.main-auth')

@section('titulo', 'Cadastro de Pesquisa')
@section('css_path', 'css/cadastro.css')

@section('conteudo')

    <div class="container">
        <div class="row justify-content-center">
            {{-- Tamanho do formulário --}}
            <div class="col-lg-10 col-md-12">

                <div class="cadastro-container">

                    {{-- Cabeçalho do Card --}}
                    <div class="text-center mb-4">
                        <div style="width: 150px; margin: 0 auto;">
                            @component('main.layouts._components.logo', ['classe' => 'img-fluid'])
                            @endcomponent
                        </div>

                        <h2 class="mt-3 font-weight-bold">Cadastro de Pesquisa</h2>
                        <p class="text-muted">Preencha os dados da sua iniciação científica.</p>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- ÁREA DE AVISOS E ERROS --}}
                    {{-- ======================================================= --}}

                    {{-- 1. Mensagem de Sucesso --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- 2. Mensagem de Erro Geral --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- 3. Erros de Validação --}}
                    {{-- Lista todos os campos obrigatórios que falharam --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading font-weight-bold" style="font-size: 1rem;">
                                <i class="fas fa-times-circle mr-2"></i> Verifique os seguintes erros:
                            </h5>
                            <hr>
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    {{-- ======================================================= --}}


                    {{-- Componente do Formulário --}}
                    @component('main.layouts._components.forms-cadastro')
                    @endcomponent

                </div>

            </div>
        </div>
    </div>

    @push('modais')
        @include('main.layouts._partials.modal-resumos')
        @include('main.layouts._partials.modal-termos')
    @endpush

@endsection

@push('scripts')
    <script src="{{ asset('js/cadastro.js') }}"></script>
@endpush