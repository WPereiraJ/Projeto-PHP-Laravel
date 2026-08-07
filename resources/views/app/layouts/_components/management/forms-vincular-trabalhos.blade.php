@section('css_path', 'css/form-vincular-trabalhos.css')

@php
    // 1. Recupera o usuário logado
    $userLogado = auth()->user();
    $nivelLogado = $userLogado ? (int)$userLogado->nivel_de_acesso_user : 0;

    // 2. Permissão por Cargo
    // 1 = Comissão | 3 = Presidente
    $acessoPorCargo = in_array($nivelLogado, [1, 3]);

    // 3. Permissão por Banco de Dados (Exceção)
    // Verifica na tabela 'tb_acesso_paginas' se o usuário tem 'incluir = 1' na página 'Apresentações'
    // Lembrete: No seu sistema, o checkbox "Incluir" da página "Apresentações" é o "Vincular Avaliador"
    $acessoPorPermissao = false;
    
    if ($userLogado) {
        $acessoPorPermissao = \Illuminate\Support\Facades\DB::table('tb_acesso_paginas')
            ->join('tb_paginas', 'tb_acesso_paginas.pagina_id', '=', 'tb_paginas.id')
            ->where('tb_acesso_paginas.usuario_id', $userLogado->id)
            ->where('tb_paginas.nome', 'Apresentações') // Nome exato que está no banco
            ->where('tb_acesso_paginas.incluir', 1)     // A coluna que libera essa função
            ->exists();
    }

    // 4. Lógica Final: Pode vincular se tiver cargo OU se tiver a permissão específica
    $podeVincular = $acessoPorCargo || $acessoPorPermissao;

    // 5. Define a trava
    $disabled = $podeVincular ? '' : 'disabled';
@endphp

{{-- Aviso visual (Só aparece se NÃO tiver nenhuma das duas permissões) --}}
@if(!$podeVincular)
    <div class="alert alert-warning shadow-sm mb-4">
        <i class="fas fa-lock"></i> <strong>Acesso Restrito:</strong> Você não possui permissão para vincular avaliadores.
    </div>
@endif

<form action="{{ route('app.vincular.update') }}" method="POST">
    @csrf

    {{-- 1. FILTRO E LISTA DE USUÁRIOS --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-white font-weight-bold">Seleção de Usuários</div>
        <div class="card-body">
            
            <div class="form-group">
                <label class="custom-label">Filtrar por Cargo</label>
                <select class="form-control" id="cargo-select" onchange="filterUsuariosByCargo()" {{ $disabled }}>
                    <option value="">Todos</option>
                    @foreach ([0=>'Avaliador', 1=>'Comissão', 2=>'CLPI', 3=>'Presidente', 4=>'P&D', 5=>'Desenvolvedor', 6=>'CTI'] as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="custom-label">Selecione os Usuários</label>
                <div class="scrollable-checkbox-list">
                    @foreach ($usuarios as $usuario)
                        <div class="form-check usuario-item" data-cargo-id="{{ $usuario->nivel_de_acesso_user ?? $usuario->nivel_de_acesso }}">
                            <input class="form-check-input" type="checkbox" name="usuario_ids[]" 
                                   value="{{ $usuario->id }}" id="usuario{{ $usuario->id }}" {{ $disabled }}>
                            <label class="form-check-label" for="usuario{{ $usuario->id }}">
                                {{ $usuario->nome }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTRO E LISTA DE TRABALHOS --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-white font-weight-bold">Seleção de Trabalhos</div>
        <div class="card-body">
            
            <div class="form-group">
                <label class="custom-label">Filtrar por Categoria</label>
                <select class="form-control" id="categoria-select" onchange="filterTrabalhosByCategoria()" {{ $disabled }}>
                    <option value="">Todas</option>
                    <option value="PIBIC/PIBIT">PIBIC/PIBIT</option>
                    <option value="Graduação">Graduação</option>
                    <option value="Pós-Graduação">Pós-Graduação</option>
                </select>
            </div>

            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="custom-label mb-0">Selecione os Trabalhos</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAllTrabalhos" onclick="toggleSelectFilteredTrabalhos()" {{ $disabled }}>
                        <label class="form-check-label text-primary" for="selectAllTrabalhos" style="cursor: pointer;">Selecionar Todos Visíveis</label>
                    </div>
                </div>
                
                <div class="scrollable-checkbox-list" id="trabalhos-list">
                    @foreach ($trabalhos as $trabalho)
                        <div class="form-check trabalho-item" data-categoria="{{ $trabalho->categoria }}">
                            <input class="form-check-input trabalho-checkbox" type="checkbox" 
                                   name="trabalho_ids[]" value="{{ $trabalho->id }}" id="trabalho{{ $trabalho->id }}" {{ $disabled }}>
                            <label class="form-check-label" for="trabalho{{ $trabalho->id }}">
                                {{ $trabalho->nome_trabalho }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 3. OPÇÕES DE AVALIADOR --}}
    <div class="card bg-light border-0">
        <div class="card-body">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="avaliador" name="avaliador" onchange="togglePresentationFields()" {{ $disabled }}>
                <label class="form-check-label font-weight-bold" for="avaliador">Definir como Avaliador</label>
            </div>

            <div id="presentation-fields" style="display: none;">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="data_apresentacao">Data da Apresentação</label>
                        <input type="date" class="form-control" id="data_apresentacao" name="data_apresentacao" {{ $disabled }}>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="horario_apresentacao">Horário da Apresentação</label>
                        <input type="time" class="form-control" id="horario_apresentacao" name="horario_apresentacao" {{ $disabled }}>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3" {{ $disabled }}>
                <i class="fas fa-link"></i> Vincular Selecionados
            </button>
        </div>
    </div>
</form>

@push('scripts')
    <script src="{{ asset('js/form-vincular-trabalho.js') }}"></script>
@endpush