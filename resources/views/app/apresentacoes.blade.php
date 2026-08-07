@extends('app.layouts.main-app')

@section('titulo', 'Apresentações')

@section('conteudo')
    <div class="page-apresentacoes">
        
        @include('app.layouts._partials.nav-bar')

        {{-- Header Estilo Novo --}}
        <div class="page-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding-top: 40px; padding-bottom: 80px;">
            <h2 class="text-white font-weight-bold mb-1">Avaliação de Apresentações</h2>
            <p class="text-white-50 mb-0">Gerencie as apresentações orais e banners - {{ date('Y') }}</p>
        </div>

        <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
            
            @include('app.layouts._partials.warning')

            {{-- Accordion Principal --}}
            <div class="accordion" id="accordionApresentacoes">
                @forelse($trabalhosPorCategoria as $categoria => $trabalhos)
                    
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 15px; overflow: hidden;">
                        {{-- Cabeçalho da Categoria --}}
                        <div class="card-header bg-white border-bottom-0 p-0" id="heading-{{ \Illuminate\Support\Str::slug($categoria) }}">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-left py-4 px-4 d-flex justify-content-between align-items-center focus-none" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse-{{ \Illuminate\Support\Str::slug($categoria) }}" 
                                        aria-expanded="true"
                                        style="text-decoration: none; color: inherit;">
                                    
                                    <span class="font-weight-bold h5 mb-0 text-dark">
                                        <i class="fas fa-folder text-primary mr-2"></i> {{ $categoria }}
                                    </span>
                                    
                                    <span class="badge badge-pill badge-light border">
                                        {{ $trabalhos->count() }} trabalhos
                                    </span>
                                </button>
                            </h2>
                        </div>

                        {{-- Lista de Trabalhos (Collapse) --}}
                        <div id="collapse-{{ \Illuminate\Support\Str::slug($categoria) }}" 
                             class="collapse {{ $loop->first ? 'show' : '' }}" 
                             data-parent="#accordionApresentacoes">
                            
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($trabalhos as $trabalho)
                                        <div class="list-group-item p-4 hover-effect">
                                            <div class="row align-items-center">
                                                {{-- Dados do Trabalho --}}
                                                <div class="col-md-9">
                                                    <h5 class="font-weight-bold text-dark mb-1">
                                                        {{ $trabalho->inscricao->nome_trabalho }}
                                                    </h5>
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-user-circle mr-1"></i> {{ $trabalho->inscricao->nome_autor }}
                                                    </p>
                                                    
                                                    {{-- Se for Admin, mostra os avaliadores vinculados --}}
                                                    @if($nivel != 0)
                                                        <div class="small mt-2">
                                                            <strong class="text-secondary">Avaliadores:</strong>
                                                            @foreach($trabalho->avaliadores as $avaliador)
                                                                <span class="badge badge-light border mr-1">{{ $avaliador->nome }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Botões de Ação --}}
                                                <div class="col-md-3 text-right">
                                                   @if($nivel == 0)
                                                        {{-- Lógica para Avaliador: Verificar se já avaliou --}}
                                                        @php
                                                            $idLogado = session('usuario_externo')['id'] ?? session('usuario_interno')['id'];
                                                            $minhaAvaliacao = $trabalho->avaliadores->where('id', $idLogado)->first();
                                                            $jaAvaliou = $minhaAvaliacao && $minhaAvaliacao->pivot->apresentacao > 0;
                                                        @endphp

                                                        <button class="btn {{ $jaAvaliou ? 'btn-outline-primary' : 'btn-success' }} btn-block rounded-pill font-weight-bold"
                                                                data-toggle="modal" 
                                                                data-target="#votarModal"
                                                                data-id="{{ $trabalho->id }}"
                                                                data-titulo="{{ $trabalho->inscricao->nome_trabalho }}"
                                                                data-autor="{{ $trabalho->inscricao->nome_autor }}"
                                                                data-avaliador="{{ $idLogado }}"
                                                                data-resumo="{{ $minhaAvaliacao->pivot->resumo ?? '' }}"
                                                                data-importancia="{{ $minhaAvaliacao->pivot->importancia ?? '' }}"
                                                                data-apresentacao="{{ $minhaAvaliacao->pivot->apresentacao ?? '' }}"
                                                                data-desempenho="{{ $minhaAvaliacao->pivot->desempenho ?? '' }}">
                                                            @if($jaAvaliou)
                                                                <i class="fas fa-edit mr-1"></i> Editar Nota
                                                            @else
                                                                <i class="fas fa-star mr-1"></i> Avaliar
                                                            @endif
                                                        </button>
                                                    @else
                                                        {{-- Lógica para Dev/Presidente: Visualizar Detalhes --}}
                                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                                data-toggle="modal" 
                                                                data-target="#modalVisualizarApresentacao"
                                                                data-titulo="{{ $trabalho->inscricao->nome_trabalho }}"
                                                                data-autor="{{ $trabalho->inscricao->nome_autor }}"
                                                                data-avaliadores="{{ json_encode($trabalho->avaliadores) }}">
                                                            <i class="fas fa-eye mr-1"></i> Visualizar Notas
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center py-5 shadow-sm rounded-lg">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted">Nenhuma apresentação agendada para hoje.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Seção de Modais --}}
@push('modais')
        @include('app.layouts._partials.modal-votar-apresentacao')
        @include('app.layouts._partials.modal-visualizar-apresentacao')
        
        @if ($exibir_modal)
             @include('app.layouts._components.modal-instrucoes')
        @endif
@endpush
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/form-votacao-apresentacao.js') }}"></script>
    
    @if ($exibir_modal)
        <script src="{{ asset('js/modal-instrucoes.js') }}"></script>
    @endif
    
    {{-- Script de Visualização (Para Admin/Presidente) --}}
    <script src="{{ asset('js/visualizar-apresentacao.js') }}"></script>

    {{-- Pequeno ajuste para o botão do accordion tirar o outline --}}
    <style>
        .focus-none:focus { box-shadow: none !important; outline: none !important; }
        .hover-effect:hover { background-color: #f9fafb; }
    </style>
@endpush