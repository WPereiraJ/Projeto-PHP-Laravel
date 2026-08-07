@extends('app.layouts.main-app')

@section('titulo', 'Gerenciamento de Inscrições')

@push('styles')
    {{-- O ?v=2 força o navegador a recarregar o CSS novo --}}
    <link rel="stylesheet" href="{{ asset('css/arquivos.css') }}?v=2">
@endpush

@section('conteudo')
    <div class="page-arquivos">

        @include('app.layouts._partials.nav-bar')

        {{-- Header Verde --}}
        <div class="page-header text-center"
            style="background: linear-gradient(135deg, #1d6736 0%, #2d8a4e 100%); padding-top: 30px; padding-bottom: 70px;">
            <h2 class="text-white font-weight-bold mb-1" style="font-size: 1.75rem;">Arquivos PIBIC/PIBIT</h2>
            <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Gestão centralizada de documentos</p>
        </div>

        {{-- Conteúdo Principal --}}
        <div class="content-wrapper container">

            @include('app.layouts._partials.warning')

            <div class="row">

                {{-- COLUNA 1: Formulário de Upload --}}
                <div class="col-lg-4 mb-4">
                    <div class="custom-card p-4">
                        <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">
                            <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Novo Arquivo
                        </h5>

                        <form action="{{ route('app.arquivos.store') }}" method="POST" enctype="multipart/form-data"
                            id="uploadForm">
                            @csrf

                            {{-- Área de Upload --}}
                            <div class="file-upload-wrapper">
                                <input type="file" name="arquivo" id="arquivoInput" class="file-upload-input" accept=".pdf"
                                    required>

                                <svg class="icon-upload" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15"
                                        stroke="currentColor" stroke-width="0" />
                                </svg>

                                <p class="file-label-text mb-0">Arraste o PDF ou clique</p>
                                <small class="text-muted mt-1">Máximo: 10MB</small>
                            </div>

                            <div class="text-center mt-3">
                                <div id="fileNameDisplay" class="text-muted small text-break mb-3 font-weight-bold px-2"
                                    style="min-height: 20px; line-height: 1.4;">
                                    Nenhum arquivo selecionado
                            </div>
                            <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm py-2">
                                <i class="fas fa-check mr-2"></i> Enviar Arquivo
                            </button>
                    </div>
                    </form>
                </div>
            </div>

            {{-- COLUNA 2: Lista de Arquivos --}}
            <div class="col-lg-8 mb-4">
                <div class="custom-card">
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-folder-open text-warning mr-2"></i> Arquivos Disponíveis
                            </h5>
                            <span class="badge badge-pill badge-light border">
                                {{ isset($arquivos) ? $arquivos->count() : 0 }} item(s)
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="scrollable-list p-3">
                            @if(isset($arquivos) && $arquivos->count() > 0)
                                @foreach($arquivos as $arq)
                                    <div class="file-item p-3 d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center overflow-hidden">
                                            <div class="bg-light rounded-circle p-3 mr-3 text-danger">
                                                <i class="fas fa-file-pdf fa-lg"></i>
                                            </div>
                                            <div style="min-width: 0;">
                                                <span class="font-weight-bold text-dark d-block text-truncate"
                                                    title="{{ $arq->nome_arquivo }}">
                                                    {{ $arq->nome_arquivo }}
                                                </span>
                                                <small class="text-muted">Documento PDF</small>
                                            </div>
                                        </div>

                                        <a href="{{ route('app.arquivos.download', $arq->id) }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-3 ml-2">
                                            <i class="fas fa-download mr-1"></i> Baixar
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-inbox fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="text-muted">Ainda não há arquivos enviados.</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    {{-- JS Específico --}}
    <script src="{{ asset('js/arquivos.js') }}"></script>
@endpush