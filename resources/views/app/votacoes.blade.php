@extends('app.layouts.main-app')

@section('titulo', 'Resultados da Votação')

@section('conteudo')
    <div class="page-votacoes">
        
        @include('app.layouts._partials.nav-bar')

        <div class="page-header text-center" style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%); padding-top: 40px; padding-bottom: 80px;">
            <h2 class="text-white font-weight-bold mb-1">Trabalhos Votados</h2>
            <p class="text-white-50 mb-0">Relação de notas finais e avaliadores - {{ date('Y') }}</p>
        </div>

        <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
            
            @include('app.layouts._partials.warning')

            <div class="accordion" id="accordionVotacoes">
                @forelse($votacoesPorCategoria as $categoria => $trabalhos)
                    
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 15px; overflow: hidden;">
                        
                        <div class="card-header bg-white border-bottom-0 p-0" id="heading-{{ \Illuminate\Support\Str::slug($categoria) }}">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-left py-4 px-4 d-flex justify-content-between align-items-center focus-none" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse-{{ \Illuminate\Support\Str::slug($categoria) }}" 
                                        aria-expanded="true">
                                    <span class="font-weight-bold h5 mb-0 text-dark">
                                        <i class="fas fa-list-ol text-info mr-2"></i> {{ $categoria }}
                                    </span>
                                    <span class="badge badge-pill badge-light border">{{ $trabalhos->count() }} concluídos</span>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse-{{ \Illuminate\Support\Str::slug($categoria) }}" 
                             class="collapse {{ $loop->first ? 'show' : '' }}" 
                             data-parent="#accordionVotacoes">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach($trabalhos as $index => $trabalho)
                                        <li class="list-group-item p-4 hover-effect border-top">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 mb-2 mb-md-0">
                                                    <div class="d-flex align-items-start">
                                                        <span class="text-muted font-weight-bold mr-3 mt-1 small">#{{ $index + 1 }}</span>
                                                        <div>
                                                            <h5 class="font-weight-bold text-dark mb-1">{{ $trabalho->nome_trabalho }}</h5>
                                                            <p class="text-muted mb-2"><i class="fas fa-user mr-1"></i> {{ $trabalho->nome_autor }}</p>
                                                            
                                                            <div class="small text-secondary">
                                                                <strong class="text-dark"><i class="fas fa-gavel mr-1"></i> Avaliadores:</strong>
                                                                @if($trabalho->avaliadores->count() > 0)
                                                                    {{ $trabalho->avaliadores->pluck('nome')->join(', ') }}
                                                                @else
                                                                    <span class="text-danger">Sem avaliadores</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-right text-center">
                                                    <div class="d-inline-block bg-light rounded-pill px-4 py-2 border">
                                                        <span class="text-uppercase small text-muted font-weight-bold d-block" style="font-size: 0.7rem;">Média Final</span>
                                                        <span class="h4 font-weight-bold {{ $trabalho->media_final >= 7 ? 'text-success' : 'text-warning' }} mb-0">
                                                            {{ number_format($trabalho->media_final, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center py-5">
                        <h5 class="text-muted">Nenhum trabalho votado.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <style>
        .focus-none:focus { box-shadow: none !important; outline: none !important; }
        .hover-effect:hover { background-color: #f8f9fa; }
    </style>
@endpush