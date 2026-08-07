@extends('main.layouts.main-dashboard')

@section('titulo', 'Painel do Pesquisador')

@section('conteudo')

    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if(session('erro'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('erro') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row mb-4 align-items-end">
            <div class="col-md-8">
                <h2 class="font-weight-bold text-dark mb-1">Visão Geral</h2>
                <p class="text-muted mb-0">Gerencie o andamento da sua submissão.</p>
            </div>
            <div class="col-md-4 text-md-right">
                <span class="badge badge-pill badge-info px-3 py-2 shadow-sm" style="font-size: 0.9rem;">
                    <i class="fas fa-calendar-check mr-1"></i> Prazo de Envio:
                    {{ isset($datasEvento->data_final_arquivo) ? \Carbon\Carbon::parse($datasEvento->data_final_arquivo)->format('d/m/Y') : 'A definir' }}
                </span>
            </div>
        </div>

        {{-- JANELA FLUTUANTE DE INSTRUÇÕES --}}
        <div id="janela-instrucoes" class="card shadow-lg border-info">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center"
                id="janela-header">
                <span class="font-weight-bold"><i class="fas fa-lightbulb mr-2 text-warning"></i> Dicas Importantes</span>
                <button type="button" class="close text-white" aria-label="Close" onclick="fecharJanelaInstrucoes()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body bg-white text-dark small p-3">
                <ul class="pl-3 mb-0" style="line-height: 1.6;">
                    <li class="mb-2"><strong>Arquivos:</strong> Após preencher seus dados, não se esqueça de acessar a aba
                        <b>"Arquivos"</b> para enviar seu Resumo em PDF.
                    </li>
                    <li class="mb-2"><strong>Edição:</strong> A aba de edição serve para você ir ajustando seu resumo de
                        acordo com as exigências.</li>
                    <li><strong>Comentários:</strong> Acompanhe sempre a aba <b>"Comentários"</b>. É lá que a comissão
                        pedirá correções no seu trabalho!</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">

                    <a class="nav-link active mb-2" id="v-pills-editar-tab" data-toggle="pill" href="#v-pills-editar"
                        role="tab">
                        <span><i class="fas fa-pen mr-2"></i> Editar Dados</span>
                    </a>

                    <a class="nav-link mb-2" id="v-pills-arquivos-tab" data-toggle="pill" href="#v-pills-arquivos"
                        role="tab">
                        <span><i class="fas fa-file-pdf mr-2"></i> Arquivo</span>
                    </a>

                    <a class="nav-link mb-2" id="v-pills-comentarios-tab" data-toggle="pill" href="#v-pills-comentarios"
                        role="tab">
                        <span><i class="fas fa-comments mr-2"></i> Pareceres</span>
                    </a>

                    <a class="nav-link mb-2" id="v-pills-notas-tab" data-toggle="pill" href="#v-pills-notas" role="tab">
                        <span><i class="fas fa-chart-bar mr-2"></i> Avaliação</span>
                    </a>

                </div>
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="dashboard-card bg-white p-4 shadow-sm">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-editar" role="tabpanel">
                            @include('main.layouts._components.aba-editar')
                        </div>

                        <div class="tab-pane fade" id="v-pills-arquivos" role="tabpanel">
                            @include('main.layouts._components.aba-arquivos')
                        </div>

                        <div class="tab-pane fade" id="v-pills-comentarios" role="tabpanel">
                            @include('main.layouts._components.aba-comentarios')
                        </div>

                        <div class="tab-pane fade" id="v-pills-notas" role="tabpanel">
                            @include('main.layouts._components.aba-notas')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/janela-instrucoes.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/janela-instrucoes.js') }}"></script>
@endpush