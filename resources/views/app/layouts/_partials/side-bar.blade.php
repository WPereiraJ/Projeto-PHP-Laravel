<div id="sidebar" class="custom-sidebar">
    <div class="custom-sidebar-header">
        <h3>NOME EVENTO</h3>
    </div>

    <ul class="list-unstyled components">
        {{-- A variável $paginasOrdenadas vem automaticamente do Composer --}}
        @foreach($paginasOrdenadas as $pagina)
            @if($pagina->rota && Route::has($pagina->rota))
                <li class="{{ Route::currentRouteName() == $pagina->rota ? 'active' : '' }}">
                    <a href="{{ route($pagina->rota) }}">
                        <i class="fas fa-arrow-right"></i> {{ $pagina->nome }}
                    </a>
                </li>
            @endif
        @endforeach

        {{-- Link de Perfil (Fixo para externos) --}}
        @if(session()->has('usuario_externo'))
            <li class="{{ Route::currentRouteName() == 'app.perfil' ? 'active' : '' }}">
                <a href="{{ route('app.perfil') }}">
                    <i class="fas fa-user-cog"></i> Meu Perfil
                </a>
            </li>
        @endif
    </ul>

    <div class="custom-sidebar-footer">
        <a class="dropdown-item text-danger font-weight-bold" href="{{ route('empregados') }}">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>
    </div>
</div>