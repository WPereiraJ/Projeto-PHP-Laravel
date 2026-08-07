<form action="{{ route('usuarios.toggle') }}" method="post" class="mt-3">
    @csrf
    <div class="form-group">
        <label for="usuario_id">Selecionar Usuário:</label>
        <select name="usuario_id" class="form-control" required>
            @foreach ($usuarios as $usuario)
                {{-- Filtra visualmente --}}
                @if(auth()->user()->podeGerenciarUsuario($usuario))
                    <option value="{{ $usuario->id }}">
                        {{ $usuario->nome }} ({{ $usuario->ativo ? 'Ativo' : 'Inativo' }})
                    </option>
                @endif
            @endforeach
        </select>
    </div>

    @php
        $userLogado = auth()->user();
        // P&D não pode desativar
        $podeDesativar = $userLogado->nivel_de_acesso_user != 4;
    @endphp

    <button type="submit" class="btn btn-{{ $podeDesativar ? 'primary' : 'secondary' }}" {{ !$podeDesativar ? 'disabled' : '' }}>
        {{ $podeDesativar ? 'Desativar/Ativar' : 'Ação Bloqueada para P&D' }}
    </button>
</form>