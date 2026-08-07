<form action="{{ route('usuarios.update') }}" method="post" class="mt-3">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="avaliador_id_edit">Selecionar Usuário para Editar:</label>
        <select name="avaliador_id_edit" class="form-control" required>
            <option value="">Selecione...</option>
            @foreach ($usuarios as $usuario)
                @if(auth()->user()->podeGerenciarUsuario($usuario))
                    <option value="{{ $usuario->id }}">{{ $usuario->nome }} ({{ $usuario->nome_cargo }})</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="nome_edit">Alterar Nome:</label>
        <input type="text" class="form-control" name="nome_edit" placeholder="Deixe em branco para manter o atual">
    </div>

    <div class="form-group">
        <label for="email_edit">Alterar Email:</label>
        <input type="email" class="form-control" name="email_edit" placeholder="Deixe em branco para manter o atual">
    </div>

    <div class="form-group">
        <label for="nivel_de_acesso">Novo Nível de Acesso:</label>
        <select class="form-control js-nivel-acesso" name="nivel_de_acesso" required>
            <option value="">Selecione o Nível</option>

            @php $meuNivel = auth()->user()->nivel_de_acesso_user; @endphp

            @if($meuNivel == 5)
            <option value="4">P&D</option> @endif

            @if($meuNivel == 4)
            <option value="3">Presidente</option> @endif

            @if($meuNivel == 3)
                <option value="0">Avaliador</option>
                <option value="1">Comissão</option>
                <option value="2">CLPI</option>
                <option value="3">Presidente</option>
                <option value="6">CTI</option>
            @endif

            {{-- COMISSÃO --}}
            @if($meuNivel == 1)
                <option value="0">Avaliador</option>
                <option value="2">CLPI</option>
                <option value="6">CTI</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        {{-- Classe js-descricao-texto para o script encontrar o parágrafo correto --}}
        <p class="text-muted small js-descricao-texto">Selecione um nível para ver a descrição.</p>
    </div>

    @php
        $userLogado = auth()->user();
        // P&D (4) não pode editar, então bloqueia botão visualmente também
        $podeSalvar = $userLogado->nivel_de_acesso_user != 4; 
    @endphp

    <button type="submit" class="btn btn-primary" {{ !$podeSalvar ? 'disabled' : '' }}>
        <i class="fas fa-user-edit"></i> {{ $podeSalvar ? 'Confirmar Alteração' : 'Ação Bloqueada para P&D' }}
    </button>
</form>