{{-- Acesso [ADM] (Comissão/Avaliadores) --}}

@if ($errors->hasBag('comissao') && $errors->comissao->any())
    <div class="alert alert-danger small p-2">
        <ul class="mb-0 pl-3">
            @foreach ($errors->comissao->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('login.comissao') }}" method="post">
    @csrf
    <div class="form-group text-left">
        <label for="mmatr" class="font-weight-bold text-secondary small text-uppercase">Login Corporativo
            (Mmatrícula)</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="fas fa-id-badge text-muted"></i></span>
            </div>
            <input type="text" class="form-control border-left-0 pl-0" id="mmatr" name="mmatr"
                placeholder="Ex: m12345" required>
        </div>
    </div>

    <div class="form-group text-left">
        <label for="pass" class="font-weight-bold text-secondary small text-uppercase">Senha de Rede</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="fas fa-lock text-muted"></i></span>
            </div>
            <input type="password" class="form-control border-left-0 pl-0" id="pass" name="pass"
                placeholder="Senha do email" required>
            <div class="input-group-append">
                <button class="btn btn-outline-light border-left-0 border text-muted btn-ver-senha" type="button">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-block shadow-sm" style="background-color: #2d8a4e; border: none;">
        Acessar
    </button>
</form>