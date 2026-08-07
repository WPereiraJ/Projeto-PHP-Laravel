@php
    $userLogado = auth()->user();
    $nivelLogado = $userLogado ? (int) $userLogado->nivel_de_acesso_user : 0;

    // BLACKLIST
    // 4 = P&D
    $niveisRestritos = [4];

    // Se o nível do usuário estiver na lista, ele NÃO pode baixar
    $podeBaixar = !in_array($nivelLogado, $niveisRestritos);

    // Variável para desativar os campos
    $disabled = $podeBaixar ? '' : 'disabled';
@endphp

{{-- Aviso visual caso o usuário esteja bloqueado --}}
@if(!$podeBaixar)
    <div class="row">
        <div class="col-12 mb-3">
            <div class="alert alert-warning">
                <i class="fas fa-lock"></i> <strong>Acesso Restrito:</strong> Usuários dos perfis P&D.
            </div>
        </div>
    </div>
@endif

<div class="row">
    {{-- 1. Baixar Resumo (CSV) --}}
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="fas fa-file-csv"></i> Resumo do Evento</h5>
                <form action="{{ route('app.arquivos.resumo') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="ano_resumo">Selecionar Ano</label>
                        {{-- Aplicando o disabled no select --}}
                        <select name="ano" id="ano_resumo" class="form-control" required {{ $disabled }}>
                            <option value="">Selecione...</option>
                            @php $anoAtual = date('Y'); @endphp
                            @for ($i = $anoAtual; $i >= ($anoAtual - 3); $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    {{-- Aplicando o disabled no botão --}}
                    <button type="submit" class="btn btn-primary" {{ $disabled }}>
                        <i class="fas fa-download"></i> Baixar CSV Resumo
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. Baixar Inscrições (CSV) --}}
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-secondary"><i class="fas fa-users"></i> Inscrições Participantes</h5>
                <form action="{{ route('app.arquivos.inscritos') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="ano_inscritos">Selecionar Ano</label>
                        {{-- Aplicando o disabled no select --}}
                        <select name="ano" id="ano_inscritos" class="form-control" required {{ $disabled }}>
                            <option value="">Selecione...</option>
                            @for ($i = $anoAtual; $i >= ($anoAtual - 3); $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    {{-- Aplicando o disabled no botão --}}
                    <button type="submit" class="btn btn-secondary" {{ $disabled }}>
                        <i class="fas fa-download"></i> Baixar CSV Inscrições
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. Baixar Apresentações (PDF) --}}
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-warning"><i class="fas fa-file-pdf"></i> Apresentações (PDF)</h5>
                <form action="{{ route('app.arquivos.apresentacao') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nome_arquivo_apresentacao">Selecionar Apresentação</label>
                        {{-- Aplicando o disabled no select --}}
                        <select name="nome_arquivo" id="nome_arquivo_apresentacao" class="form-control" required {{ $disabled }}>
                            <option value="">Selecione o Arquivo...</option>
                            @if(isset($apresentacoes) && count($apresentacoes) > 0)
                                @foreach ($apresentacoes as $arq)
                                    <option value="{{ $arq->nome_arquivo }}">{{ $arq->nome_arquivo }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Nenhuma apresentação encontrada.</option>
                            @endif
                        </select>
                    </div>
                    {{-- Aplicando o disabled no botão --}}
                    <button type="submit" class="btn btn-warning text-white" {{ $disabled }}>
                        <i class="fas fa-file-download"></i> Baixar Apresentação
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. Baixar Resumos (PDF) --}}
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger"><i class="fas fa-file-alt"></i> Resumos (PDF)</h5>
                <form action="{{ route('app.arquivos.resumo-pdf') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nome_arquivo_resumo">Selecionar Resumo</label>
                        {{-- Aplicando o disabled no select --}}
                        <select name="nome_arquivo" id="nome_arquivo_resumo" class="form-control" required {{ $disabled }}>
                            <option value="">Selecione o Arquivo...</option>
                            @if(isset($resumos) && count($resumos) > 0)
                                @foreach ($resumos as $arq)
                                    <option value="{{ $arq->nome_arquivo }}">{{ $arq->nome_arquivo }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Nenhum resumo encontrado.</option>
                            @endif
                        </select>
                    </div>
                    {{-- Aplicando o disabled no botão --}}
                    <button type="submit" class="btn btn-danger text-white" {{ $disabled }}>
                        <i class="fas fa-file-download"></i> Baixar Resumo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>