@php
    // 1. Pega o usuário logado via Auth do Laravel
    $userLogado = auth()->user();
    $nivelLogado = $userLogado ? (int)$userLogado->nivel_de_acesso_user : 0;

    // 2. Verifica se tem um usuário alvo selecionado para edição
    $temUsuario = !is_null($usuarioSelecionado);

    // 3. REGRA DE NEGOCIO: Só Nível 3 (Presidente) e 5 (Dev) podem mexer aqui
    $podeEditar = in_array($nivelLogado, [3, 5]) && $temUsuario;

    // 4. Define a trava
    $disabled = $podeEditar ? '' : 'disabled';
@endphp

<form action="{{ route('app.permissoes.update') }}" method="POST">
    @csrf
    
    {{-- Input hidden --}}
    <input type="hidden" name="usuario_id" value="{{ $usuarioSelecionado->id ?? '' }}">

    {{-- Aviso visual para quem não pode editar--}}
    @if(!$podeEditar && $temUsuario)
        <div class="alert alert-warning py-2 small">
            <i class="fas fa-lock"></i> Modo de visualização: Apenas Presidentes e Desenvolvedores podem alterar permissões.
        </div>
    @endif

    <div class="form-group">
        <label class="font-weight-bold mb-3">Permissões por Página:</label>

        @foreach($paginas as $pagina)
            <h5 class="text-dark mt-2 border-bottom pb-1">{{ $pagina->nome }}</h5>
            <input type="hidden" name="permissoes[{{ $pagina->id }}][touched]" value="1">
            
            @php
                // Lógica de recuperação das permissões atuais
                $perm = ($temUsuario && isset($permissoesAtuais[$pagina->id])) ? $permissoesAtuais[$pagina->id] : null;
                
                $acessar = $perm ? $perm->acessar : 0;
                $incluir = $perm ? $perm->incluir : 0;
                $editar  = $perm ? $perm->editar : 0;
                $excluir = $perm ? $perm->excluir : 0;
            @endphp

            {{-- 1. Checkbox ACESSAR --}}
            <div class="form-check">
                <input class="form-check-input" type="checkbox" 
                       name="permissoes[{{ $pagina->id }}][acessar]" value="1"
                       {{ $acessar ? 'checked' : '' }} {{ $disabled }}>
                <label class="form-check-label">Acessar</label>
            </div>

            {{-- 2. Checkbox INCLUIR --}}
            @if(in_array($pagina->nome, ['Apresentações', 'Central de Gestão']))
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="permissoes[{{ $pagina->id }}][incluir]" value="1"
                           {{ $incluir ? 'checked' : '' }} {{ $disabled }}>
                    <label class="form-check-label">
                        {{ $pagina->nome == 'Apresentações' ? 'Vincular Avaliador' : 'Cadastrar Usuário' }}
                    </label>
                </div>
            @endif

            {{-- 3. Checkboxes EDITAR e EXCLUIR --}}
            @if($pagina->nome == 'Central de Gestão')
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="permissoes[{{ $pagina->id }}][editar]" value="1"
                           {{ $editar ? 'checked' : '' }} {{ $disabled }}>
                    <label class="form-check-label">Editar Usuário</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="permissoes[{{ $pagina->id }}][excluir]" value="1"
                           {{ $excluir ? 'checked' : '' }} {{ $disabled }}>
                    <label class="form-check-label">Desativar Usuário</label>
                </div>
            @endif

            <hr>
        @endforeach
    </div>

    {{-- Botão Salvar --}}
    <div class="form-group">
        <button type="submit" class="btn btn-primary" {{ $disabled }}>
            Salvar Permissões
        </button>
        
        @if(!$temUsuario)
            <small class="text-muted d-block mt-2">
                * Selecione um usuário acima para habilitar a edição.
            </small>
        @endif
    </div>
</form>