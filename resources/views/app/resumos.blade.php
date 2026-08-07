@extends('app.layouts.main-app')

@section('titulo', 'Resumos')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/resumo.css') }}">
@endpush

@section('conteudo')
    <div class="page-resumos">
        @include('app.layouts._partials.nav-bar')

        <div class="page-header text-center">
            <h2 class="text-white font-weight-bold">Trabalhos Científicos</h2>
            <p class="text-white-50">Plataforma de Gestão de Resumos Acadêmicos</p>
        </div>

        <div class="container pb-5">
            @include('app.layouts._partials.warning')

            <div class="accordion" id="accordionTrabalhos">
                @forelse($trabalhosPorCategoria as $categoria => $trabalhos)
                    <div class="card category-card mb-4 shadow-sm">
                        <div class="card-header category-header d-flex justify-content-between align-items-center"
                            data-toggle="collapse" data-target="#cat-{{ \Illuminate\Support\Str::slug($categoria) }}">
                            <h5 class="mb-0">
                                <i class="fas fa-folder-open mr-2 text-success"></i>
                                {{ $categoria }}
                            </h5>
                            <span class="badge-count">
                                {{ $trabalhos->count() }} {{ $trabalhos->count() > 1 ? 'Itens' : 'Item' }}
                            </span>
                        </div>

                        <div id="cat-{{ \Illuminate\Support\Str::slug($categoria) }}"
                            class="collapse {{ $loop->first ? 'show' : '' }}">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 45%">Título do Trabalho</th>
                                                <th>Autor Principal</th>
                                                @if(auth()->user()->nivel_de_acesso_user != 0)
                                                    <th>Status CLPI</th>
                                                @endif
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trabalhos as $trabalho)
                                                                        <tr class="tr-work">
                                                                            <td class="align-middle py-3">
                                                                                <span
                                                                                    class="d-block font-weight-bold text-dark">{{ $trabalho->inscricao->nome_trabalho }}</span>
                                                                            </td>
                                                                            <td class="align-middle text-muted">
                                                                                {{ $trabalho->inscricao->nome_autor }}
                                                                            </td>
                                                                            @if(auth()->user()->nivel_de_acesso_user != 0)
                                                                                <td class="align-middle">
                                                                                    <span
                                                                                        class="status-badge badge {{ $trabalho->clpi_aprovado == 'Aprovado' ? 'badge-success' : 'badge-warning text-dark' }}">
                                                                                        {{ $trabalho->clpi_aprovado ?? 'Pendente' }}
                                                                                    </span>
                                                                                </td>
                                                                            @endif
                                                                            <td class="text-center align-middle">
                                                                                <button class="btn btn-sm btn-outline-success btn-detail"
                                                                                    data-toggle="collapse" data-target="#detalhes-{{ $trabalho->id }}">
                                                                                    <i class="fas fa-plus mr-1"></i> Detalhes
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr id="detalhes-{{ $trabalho->id }}" class="collapse">
                                                                            <td colspan="5" class="bg-light p-0">
                                                                                <div class="detail-box p-4">
                                                                                    <div class="row">
                                                                                        <div class="col-md-8">
                                                                                            <h6 class="text-success font-weight-bold mb-3">Resumo Acadêmico
                                                                                            </h6>
                                                                                            <p class="text-justify text-muted">
                                                                                                {{ $trabalho->inscricao->resumo }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="col-md-4 border-left">
                                                                                            <h6 class="text-dark font-weight-bold">Metadados</h6>

                                                                                            <p class="small text-muted mb-1">
                                                                                                <strong>Orientador:</strong>
                                                                                                {{ $trabalho->inscricao->orientador }}
                                                                                            </p>

                                                                                            <p class="small text-muted mb-1">
                                                                                                <strong>Palavras-chave:</strong>
                                                                                                {{ $trabalho->inscricao->termos_indexacao }}
                                                                                            </p>

                                                                                            {{-- DADOS EXTRAS PARA COMISSÃO (1), CLPI (2) E PRESIDENTE (3)
                                                                                            --}}
                                                                                            @if(in_array(auth()->user()->nivel_de_acesso_user, [1, 2, 3]))
                                                                                                <div class="mt-2 p-2 bg-white border rounded">
                                                                                                    <p class="small text-muted mb-1">
                                                                                                        {{-- Aqui mapeamos o rótulo SISGEN para a coluna
                                                                                                        seg_contrato --}}
                                                                                                        <strong class="text-success"><i
                                                                                                                class="fas fa-file-contract"></i>
                                                                                                            SISGEN:</strong>
                                                                                                        {{ $trabalho->inscricao->sisgen ?? 'Não informado' }}
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="mt-2 p-2 bg-white border rounded">
                                                                                                    <p class="small text-muted mb-1">
                                                                                                        {{-- Aqui mapeamos o rótulo SISGEN para a coluna
                                                                                                        seg_contrato --}}
                                                                                                        <strong class="text-success"><i
                                                                                                                class="fas fa-file-contract"></i>
                                                                                                            SEG/Contrato:</strong>
                                                                                                        {{ $trabalho->inscricao->seg_contrato ?? 'Não informado' }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            @endif

                                                                                            <button class="btn btn-success btn-block mt-3"
                                                                                                data-toggle="modal" data-target="#avaliarModal"
                                                                                                data-id="{{ $trabalho->id }}"
                                                                                                data-name="{{ $trabalho->inscricao->nome_trabalho }}" {{--
                                                                                                Atributos para preenchimento automático do Modal --}}
                                                                                                @if(auth()->user()->nivel_de_acesso_user == 0)
                                                                                                    data-nota-resumo="{{ $trabalho->minha_avaliacao_avaliador->resumo ?? '' }}"
                                                                                                    data-nota-importancia="{{ $trabalho->minha_avaliacao_avaliador->importancia ?? '' }}"
                                                                                                 @endif

                                                   @if(in_array(auth()->user()->nivel_de_acesso_user, [1, 2]))
                                                    data-comentario="{{ $trabalho->meu_parecer_comissao->comentarios ?? '' }}"
                                                    data-clpi-status="{{ $trabalho->clpi_aprovado ?? '' }}"
                                                @endif>
                                                                                                {{ auth()->user()->nivel_de_acesso_user == 0 ? 'Dar Notas' : 'Avaliar / Comentar' }}
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center border py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum registro encontrado.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('app.layouts._components.modais.modal-avaliacao')
@endsection

@push('scripts')
    @include('app.layouts._partials.side-bar')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/resumos.js') }}"></script>
@endpush