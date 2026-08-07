{{-- Acesso Externo (Estudantes/Participantes) --}}

@if ($errors->hasBag('externo') && $errors->externo->any())
    <div class="alert alert-danger small p-2">
        <ul class="mb-0 pl-3">
            @foreach ($errors->externo->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('login.externo') }}" method="post">
    @csrf
    <div class="form-group text-left">
        <label for="email" class="font-weight-bold text-secondary small text-uppercase">Email</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-muted"></i></span>
            </div>
            <input type="email" class="form-control border-left-0 pl-0" id="email" name="email"
                placeholder="seu@email.com" required>
        </div>
    </div>

    <div class="form-group text-left">
        <label for="senha" class="font-weight-bold text-secondary small text-uppercase">Senha</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="fas fa-key text-muted"></i></span>
            </div>
            <input type="password" class="form-control border-left-0 pl-0" id="senha" name="senha"
                placeholder="Sua senha" required>
            <div class="input-group-append">
                <button class="btn btn-outline-light border-left-0 border text-muted btn-ver-senha" type="button">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block shadow-sm mb-3">
        Acessar
    </button>
</form>