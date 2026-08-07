@extends('app.layouts.main-app')

@section('titulo', 'Notas dos Avaliadores')

@section('conteudo')
    <div class="page-notas-avaliadores">

        @include('app.layouts._partials.nav-bar')

        <div class="page-header text-center"
            style="background: linear-gradient(135deg, #FF512F 0%, #DD2476 100%); padding-top: 40px; padding-bottom: 80px;">
            <h2 class="text-white font-weight-bold mb-1">Histórico de Avaliações</h2>
            <p class="text-white-50 mb-0">Detalhamento das notas atribuídas - {{ date('Y') }}</p>
        </div>

        <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">

            @include('app.layouts._partials.warning')

            {{-- BARRA DE PESQUISA (Apenas para Presidente/Comissão) --}}
            @if(auth()->user()->nivel_de_acesso_user != 0)
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8">
                        <form action="{{ route('app.notas-avaliadores') }}" method="GET">
                            <div class="input-group input-group-lg shadow-sm"
                                style="border-radius: 50px; overflow: hidden; border: 1px solid #eee;">

                                {{-- Ícone de Lupa --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-0 pl-4">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>

                                {{-- Campo de Input --}}
                                <input type="text" name="search" class="form-control border-0 pl-2"
                                    placeholder="Procurar avaliador pelo nome..." value="{{ request('search') }}"
                                    style="font-size: 1rem;">

                                {{-- Botões de Ação --}}
                                <div class="input-group-append">
                                    {{-- Se tiver busca ativa, mostra botão de limpar --}}
                                    @if(request('search'))
                                        <a href="{{ route('app.notas-avaliadores') }}"
                                            class="btn btn-white border-0 text-muted d-flex align-items-center"
                                            title="Limpar filtro">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif

                                    <button class="btn btn-danger px-4 font-weight-bold" type="submit"
                                        style="background: #FF512F; border: none;">
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @forelse($notasAgrupadas as $nomeAvaliador => $avaliacoes)
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">

                    {{-- Cabeçalho: Nome do Avaliador --}}
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-light text-danger rounded-circle d-flex align-items-center justify-content-center mr-3 border"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 text-uppercase font-weight-bold">Avaliador</h6>
                                <h5 class="font-weight-bold text-dark mb-0">{{ $nomeAvaliador }}</h5>
                            </div>
                        </div>
                        <span class="badge badge-pill badge-light border">{{ $avaliacoes->count() }} avaliações</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pl-4 text-muted small text-uppercase font-weight-bold" style="width: 40%;">
                                            Trabalho</th>
                                        <th class="text-center text-muted small text-uppercase font-weight-bold">Resumo</th>
                                        <th class="text-center text-muted small text-uppercase font-weight-bold">Importância
                                        </th>
                                        <th class="text-center text-muted small text-uppercase font-weight-bold">Apresentação
                                        </th>
                                        <th class="text-center text-muted small text-uppercase font-weight-bold">Desempenho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($avaliacoes as $nota)
                                        <tr>
                                            <td class="pl-4 align-middle">
                                                <span class="d-block font-weight-bold text-dark text-break">
                                                    {{ $nota->trabalho->inscricao->nome_trabalho ?? 'Trabalho não encontrado' }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $nota->trabalho->inscricao->categoria ?? '-' }}
                                                </small>
                                            </td>

                                            {{-- Notas com formatação condicional (Verde alta, Amarela média) --}}
                                            <td class="text-center align-middle">
                                                <span
                                                    class="badge {{ $nota->resumo >= 8 ? 'badge-success' : 'badge-warning text-white' }} px-3 py-2">
                                                    {{ number_format($nota->resumo, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span
                                                    class="badge {{ $nota->importancia >= 8 ? 'badge-success' : 'badge-warning text-white' }} px-3 py-2">
                                                    {{ number_format($nota->importancia, 1) }}
                                                </span>
                                            </td>

                                            {{-- Apresentação e Desempenho podem ser nulos (ainda não avaliados presencialmente)
                                            --}}
                                            <td class="text-center align-middle">
                                                @if($nota->apresentacao !== null)
                                                    <span class="badge badge-light border text-dark font-weight-bold px-2">
                                                        {{ number_format($nota->apresentacao, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($nota->desempenho !== null)
                                                    <span class="badge badge-light border text-dark font-weight-bold px-2">
                                                        {{ number_format($nota->desempenho, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
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
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">Nenhuma avaliação encontrada para este ano.</h5>
                </div>
            @endforelse

            @if(isset($paginacao) && $paginacao instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-5">
                    {{ $paginacao->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endpush