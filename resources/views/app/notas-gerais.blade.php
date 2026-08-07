@extends('app.layouts.main-app')

@section('titulo', 'Notas Gerais')

@section('conteudo')
    <div class="page-notas">
        
        @include('app.layouts._partials.nav-bar')

        {{-- Header Roxo/Azul para diferenciar --}}
        <div class="page-header text-center" style="background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); padding-top: 40px; padding-bottom: 80px;">
            <h2 class="text-white font-weight-bold mb-1">Quadro de Notas</h2>
            <p class="text-white-50 mb-0">Classificação Geral e Médias - {{ date('Y') }}</p>
        </div>

        <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
            
            @include('app.layouts._partials.warning')

            @forelse($rankingPorCategoria as $categoria => $trabalhos)
                <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px; overflow: hidden;">
                    
                    {{-- Cabeçalho da Categoria --}}
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-0">{{ $categoria }}</h5>
                        <span class="badge badge-pill badge-light ml-auto border">{{ $trabalhos->count() }} trabalhos</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%;" class="pl-4 border-top-0 text-muted small text-uppercase font-weight-bold">Trabalho / Autor</th>
                                        <th class="text-center border-top-0 text-muted small text-uppercase font-weight-bold">Resumo</th>
                                        <th class="text-center border-top-0 text-muted small text-uppercase font-weight-bold">Apresentação</th>
                                        <th class="text-center border-top-0 text-muted small text-uppercase font-weight-bold">Média Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trabalhos as $index => $trabalho)
                                        <tr>
                                            <td class="pl-4 align-middle">
                                                {{-- Medalhas para o Top 3 --}}
                                                <div class="d-flex">
                                                    <div class="mr-3 text-center" style="min-width: 30px;">
                                                        @if($index == 0) <i class="fas fa-medal text-warning fa-lg" title="1º Lugar"></i>
                                                        @elseif($index == 1) <i class="fas fa-medal text-secondary fa-lg" title="2º Lugar"></i>
                                                        @elseif($index == 2) <i class="fas fa-medal text-danger fa-lg" title="3º Lugar"></i> {{-- Bronze --}}
                                                        @else <span class="text-muted font-weight-bold">{{ $index + 1 }}º</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="d-block font-weight-bold text-dark text-break">{{ $trabalho->nome_trabalho }}</span>
                                                        <span class="text-muted small"><i class="fas fa-user mr-1"></i> {{ $trabalho->nome_autor }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            {{-- Notas Detalhadas --}}
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light border text-secondary" data-toggle="tooltip" title="Importância: {{ number_format($trabalho->media_importancia, 2) }}">
                                                    {{ number_format($trabalho->media_resumo, 2) }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($trabalho->media_apresentacao > 0)
                                                    <span class="badge badge-light border text-secondary" data-toggle="tooltip" title="Desempenho: {{ number_format($trabalho->media_desempenho, 2) }}">
                                                        {{ number_format($trabalho->media_apresentacao, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-black-50 small">-</span>
                                                @endif
                                            </td>
                                            
                                            {{-- Nota Final Destacada --}}
                                            <td class="text-center align-middle">
                                                <h5 class="mb-0 font-weight-bold text-primary">
                                                    {{ number_format($trabalho->media_final, 2) }}
                                                </h5>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center py-5 shadow-sm rounded-lg">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">Nenhuma nota registrada para este ano ainda.</h5>
                </div>
            @endforelse
            
        </div>
    </div>
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush