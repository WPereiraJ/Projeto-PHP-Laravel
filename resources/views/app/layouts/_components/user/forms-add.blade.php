<form action="{{ route('usuarios.store') }}" method="post" class="mt-3">
    @csrf
    <div class="form-group">
        <label for="nome">Nome do Usuário:</label>
        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Nome" required>
    </div>
    <div class="form-group">
        <label for="email">Email do Usuário:</label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="usuario_externo" name="usuario_externo" {{ old('usuario_externo') ? 'checked' : '' }}>
        <label class="form-check-label" for="usuario_externo">Usuário Externo</label>
    </div>
    <div class="form-group" id="matricula-group">
        <label for="matricula">Matrícula do Usuário:</label>
        <input type="text" class="form-control" name="matricula" value="{{ old('matricula') }}" placeholder="Matrícula">
    </div>
    <div class="form-group">
        <label for="nivel_de_acesso">Nível de Acesso:</label>
        <select class="form-control js-nivel-acesso" name="nivel_de_acesso" required>
            <option value="">Selecione um Nível</option>
            
            @php 
                $meuNivel = auth()->user()->nivel_de_acesso_user; 
            @endphp

            {{-- 1. Desenvolvedor (5) --}}
            @if($meuNivel == 5)
                <option value="4">P&D</option>
            @endif

            {{-- 2. P&D (4) --}}
            @if($meuNivel == 4)
                <option value="3">Presidente</option>
            @endif

            {{-- 3. Presidente (3) --}}
            @if($meuNivel == 3)
                <option value="0">Avaliador</option>
                <option value="1">Comissão</option>
                <option value="2">CLPI</option>
                <option value="3">Presidente</option>
                <option value="6">CTI</option>
            @endif

            {{-- 4. Comissão (1) --}}
            @if($meuNivel == 1)
                <option value="0">Avaliador</option>
                <option value="2">CLPI</option>
                <option value="6">CTI</option>
            @endif

        </select>
    </div>
    <div class="form-group">
        <p class="text-muted js-descricao-texto">Selecione um nível de acesso para ver a descrição.</p>
    </div>
    <button type="submit" class="btn btn-primary">Adicionar</button>
</form>