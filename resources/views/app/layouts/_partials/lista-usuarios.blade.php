
<link rel="stylesheet" href="{{ asset('css/lista-usuarios.css') }}">

<div class="card shadow-sm border-0">
    <div class="card-header bg-white font-weight-bold text-primary">
        <i class="fas fa-users"></i> Usuários Cadastrados
    </div>

    <div class="card-body p-2">
        <div class="user-scroll-container">

            @foreach($usuariosLista as $usuario)
                <div class="user-card mb-2 p-3 d-flex justify-content-between align-items-center">

                    {{-- 1. Nome do Usuário --}}
                    <div class="user-name">
                        {{ $usuario->nome }}
                    </div>

                    {{-- 2. Cargo --}}
                    <div class="user-role">
                        {{ $usuario->nome_cargo }}
                    </div>

                    {{-- 3. Status --}}
                    <div class="user-status-container">
                        <span class="status-dot {{ $usuario->ativo ? 'status-active' : 'status-inactive' }}"></span>
                        <span style="font-size: 0.9em;">
                            {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</div>