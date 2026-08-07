@extends('app.layouts.main-app')

@section('titulo', 'Central de Gestão')

{{-- Importando CSS da lista --}}
@section('css_path', 'css/lista-usuarios.css')

@section('conteudo')
    {{-- Nav Bar --}}
    @include('app.layouts._partials.nav-bar')

    @push('scripts')
        {{-- Side Bar --}}
        @include('app.layouts._partials.side-bar')
        <script src="{{ asset('js/sidebar.js') }}"></script>
    @endpush

    <div class="container-fluid mt-4 pb-5">
        <h2 class="text-center text-white font-weight-bold my-4">Central de Gestão</h2>
        
        @include('app.layouts._partials.warning')

        <div class="row">
            
            {{-- COLUNA 1: GESTÃO DE USUÁRIO --}}
            <div class="col-lg-4 mb-4">
                <h5 class="text-white text-center mb-3">Gestão de Usuário</h5>
                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-0 border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs m-0" id="avaliadorTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active rounded-0 border-top-0 border-left-0" id="add-tab" data-toggle="tab" href="#add" role="tab">Adicionar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-0 border-top-0" id="delete-tab" data-toggle="tab" href="#delete" role="tab">Status</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-0 border-top-0 border-right-0" id="edit-tab" data-toggle="tab" href="#edit" role="tab">Alterar</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <div class="tab-content" id="avaliadorTabContent">
                            {{-- ABA: ADICIONAR --}}
                            <div class="tab-pane fade show active" id="add" role="tabpanel">
                                @include('app.layouts._components.user.forms-add')
                            </div>

                            {{-- ABA: ATIVAR/DESATIVAR --}}
                            <div class="tab-pane fade" id="delete" role="tabpanel">
                                {{-- Aqui mantemos $usuarios pois a pessoa precisa ver os inativos para poder ativá-los --}}
                                @include('app.layouts._components.user.forms-status', ['usuarios' => $usuarios])
                            </div>

                            {{-- ABA: ALTERAR --}}
                            <div class="tab-pane fade" id="edit" role="tabpanel">
                                @include('app.layouts._components.user.forms-edit', ['usuarios' => $usuarios])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUNA 2: LISTA CENTRAL --}}
            <div class="col-lg-4 mb-4">
                <h5 class="text-white text-center mb-3">Usuários Cadastrados</h5>
                @include('app.layouts._partials.lista-usuarios')
            </div>

            {{-- COLUNA 3: GERENCIAMENTO --}}
            <div class="col-lg-4 mb-4">
                <h5 class="text-white text-center mb-3">Gerenciamento</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-0 border-bottom-0">
                        {{-- Menu Scrollável horizontalmente caso tenha muitas abas --}}
                        <div style="overflow-x: auto; white-space: nowrap;">
                            <ul class="nav nav-tabs card-header-tabs m-0 flex-nowrap" id="gerenciamentoTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="gerenciar-tab" data-toggle="tab" href="#gerenciar" role="tab">Permissões</a></li>
                                <li class="nav-item"><a class="nav-link" id="prazos-tab" data-toggle="tab" href="#prazos" role="tab">Prazos</a></li>
                                <li class="nav-item"><a class="nav-link" id="vincular-tab" data-toggle="tab" href="#vincular" role="tab">Vincular</a></li>
                                <li class="nav-item"><a class="nav-link" id="arquivos-tab" data-toggle="tab" href="#arquivos" role="tab">Arquivos</a></li>
                                <li class="nav-item"><a class="nav-link" id="termos-tab" data-toggle="tab" href="#termos" role="tab">Termos</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body" style="min-height: 400px;">
                        <div class="tab-content" id="gerenciamentoTabContent">
                            
                            {{-- ABA 1: GERENCIAR USUÁRIO --}}
                            <div class="tab-pane fade show active" id="gerenciar" role="tabpanel">
                                <form action="{{ route('app.central-gestao.selecionar') }}" method="POST" id="formSelecionarUsuario">
                                    @csrf
                                    <div class="form-group">
                                        <label for="usuario_id_select" class="font-weight-bold text-dark">Usuário: <span class="text-danger">*</span></label>
                                        <select id="usuario_id_select" name="usuario_id" class="form-control" onchange="this.form.submit()">
                                            <option value="">Selecione um usuário</option>
                                            @foreach ($usuariosAtivos as $usuario)
                                                <option value="{{ $usuario->id }}" {{ (session('usuario_gerenciado_id') == $usuario->id) ? 'selected' : '' }}>
                                                    {{ $usuario->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>

                                <hr>

                                @if($usuarioSelecionado)
                                    <div class="mt-3 fade-in">
                                        @include('app.layouts._components.management.forms-acesso-pagina', [
                                            'usuarioSelecionado' => $usuarioSelecionado,
                                            'paginas' => $paginas,
                                            'permissoesAtuais' => $permissoesAtuais
                                        ])
                                    </div>
                                @else
                                    <div class="text-center text-muted mt-5">
                                        <i class="fas fa-user-cog fa-3x mb-3"></i><br>
                                        <small>Selecione um usuário ativo acima para carregar as permissões.</small>
                                    </div>
                                @endif
                            </div>

                            {{-- ABA 2: PRAZOS --}}
                            <div class="tab-pane fade" id="prazos" role="tabpanel">
                                @include('app.layouts._components.management.forms-prazos')
                            </div>

                            {{-- ABA 3: VINCULAR TRABALHOS --}}
                            <div class="tab-pane fade" id="vincular" role="tabpanel">
                                @include('app.layouts._components.management.forms-vincular-trabalhos', [
                                    'trabalhos' => $trabalhos,
                                    'usuarios' => $usuariosAtivos
                                ])
                            </div>

                            {{-- ABA 4: ARQUIVOS --}}
                            <div class="tab-pane fade" id="arquivos" role="tabpanel">
                                @include('app.layouts._components.management.forms-arquivos', [
                                    'apresentacoes' => $apresentacoes ?? [] 
                                ])
                            </div>

                            {{-- ABA 5: TERMOS --}}
                            <div class="tab-pane fade" id="termos" role="tabpanel">
                                @include('app.layouts._components.management.forms-termos', [
                                    'termosData' => $termosData
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- FIM DA ROW --}}
    </div>

    @push('scripts')
        <script src="{{ asset('js/cargos.js') }}"></script>
    @endpush
@endsection