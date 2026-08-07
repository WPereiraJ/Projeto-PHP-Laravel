@extends('app.layouts.main-app')

@section('titulo', 'Perfil')
@section('conteudo')
    {{--- Nav Bar ---}}
    @include('app.layouts._partials.nav-bar')
    @push('scripts')
        {{--- Side Bar ---}}
        @include('app.layouts._partials.side-bar')
        <script src="{{ asset('js/sidebar.js') }}"></script>
    @endpush


@section('conteudo')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                
                <div class="card-header font-weight-bold">
                    @if(session('primeiro_acesso') == 1)
                        <i class="fa fa-lock"></i> Definição de Senha Obrigatória
                    @else
                        Meu Perfil
                    @endif
                </div>

                <div class="card-body">
                    
                    @if (session('aviso'))
                        <div class="alert alert-warning">{{ session('aviso') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('app.perfil.update') }}">
                        @csrf

                        {{-- Nome --}}
                        <div class="form-group row">
                            <label for="nome" class="col-md-4 col-form-label text-md-right">Nome Completo</label>
                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       name="nome" value="{{ old('nome', $dadosUsuario['nome']) }}" required>
                                @error('nome')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        
                        @if(session('primeiro_acesso') == 1)
                            <div class="alert alert-info text-center">
                                <strong>Para sua segurança, defina uma nova senha pessoal para liberar seu acesso.</strong>
                            </div>
                        @else
                            <h5>Alterar Senha <small class="text-muted">(Deixe vazio se não quiser alterar)</small></h5>
                        @endif

                        {{-- CAMPO SENHA ATUAL --}}
                        @if(session('primeiro_acesso') != 1)
                            <div class="form-group row">
                                <label for="senha_atual" class="col-md-4 col-form-label text-md-right">Senha Atual</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="senha_atual" type="password" class="form-control @error('senha_atual') is-invalid @enderror" name="senha_atual">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-ver-senha" type="button">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                        </div>
                                        @error('senha_atual')
                                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Necessário para confirmar a troca.</small>
                                </div>
                            </div>
                        @endif

                        {{-- Nova Senha --}}
                        <div class="form-group row">
                            <label for="nova_senha" class="col-md-4 col-form-label text-md-right">Nova Senha</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="nova_senha" type="password" class="form-control @error('nova_senha') is-invalid @enderror" name="nova_senha">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-ver-senha" type="button">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    </div>
                                    @error('nova_senha')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Confirmar Nova Senha --}}
                        <div class="form-group row">
                            <label for="nova_senha_confirmation" class="col-md-4 col-form-label text-md-right">Confirmar Nova Senha</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="nova_senha_confirmation" type="password" class="form-control" name="nova_senha_confirmation">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-ver-senha" type="button">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @if(session('primeiro_acesso') == 1)
                                        Salvar e Entrar
                                    @else
                                        Salvar Alterações
                                    @endif
                                </button>

                                @if(session('primeiro_acesso') != 1)
                                    <a href="{{ route('app.apresentacoes') }}" class="btn btn-secondary ml-2">
                                        Voltar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/perfil.js') }}"></script>
@endpush