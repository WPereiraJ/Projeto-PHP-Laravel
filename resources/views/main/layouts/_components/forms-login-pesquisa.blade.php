{{-- Mensagens de Erro --}}
@if($errors->any())
    <div class="alert alert-danger fade show p-2 mb-4" role="alert" style="font-size: 0.85rem; border-radius: 8px;">
        <ul class="mb-0 pl-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('erro'))
    <div class="alert alert-danger fade show text-center p-2 mb-4" role="alert" style="border-radius: 8px;">
        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('erro') }}
    </div>
@endif

<form action="{{ route('main.login.submit') }}" method="POST">
    @csrf

    {{-- Input E-mail --}}
    <div class="custom-input-group">
        <span class="input-icon"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" class="form-control-clean" 
               placeholder="Seu e-mail cadastrado" required value="{{ old('email') }}">
    </div>

    {{-- Input CPF/Senha --}}
    <div class="custom-input-group">
        <span class="input-icon"><i class="fas fa-lock"></i></span>
        <input type="password" name="cpf" class="form-control-clean" 
               placeholder="CPF (apenas números)" required>
    </div>

    {{-- Botão Entrar --}}
    <button type="submit" class="btn-login-modern mt-3">
        ACESSAR SISTEMA <i class="fas fa-arrow-right ml-2"></i>
    </button>

</form>