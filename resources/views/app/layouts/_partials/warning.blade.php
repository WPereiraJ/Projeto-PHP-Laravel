{{-- 1. MENSAGEM DE SUCESSO (Verde) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 5px solid #28a745;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle mr-2 fa-lg"></i>
            <div>
                <strong>Sucesso!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 2. MENSAGEM DE AVISO / WARNING (Amarelo) --}}
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert"
        style="border-left: 5px solid #ffc107; background-color: #fff3cd; color: #856404;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle mr-2 fa-lg"></i>
            <div>
                <strong>Atenção:</strong> {{ session('warning') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 3. MENSAGEM DE ERRO (Vermelho) --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 5px solid #dc3545;">
        <div class="d-flex align-items-center">
            <i class="fas fa-times-circle mr-2 fa-lg"></i>
            <div>
                <strong>Erro:</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 4. ERROS DE VALIDAÇÃO DO LARAVEL ($request->validate) --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 5px solid #bd2130;">
        <h6 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Verifique os campos:
        </h6>
        <ul class="mb-0 mt-1 pl-3 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif