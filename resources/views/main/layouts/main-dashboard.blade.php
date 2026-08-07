<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') - Área do Pesquisador</title>

    {{-- Bootstrap 4 (CDN ou Local) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    {{-- FontAwesome (Ícones) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- Fonte Google (Inter/Open Sans para leitura melhor) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Específico do Dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')
</head>
<body class="bg-light">

    {{-- NAVBAR SUPERIOR (Exclusiva do Dashboard) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-modern shadow-sm sticky-top">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand font-weight-bold" href="#">
                <i class="fas fa-microscope mr-2"></i> [NOME DO EVENTO]
            </a>

            {{-- Menu Mobile Toggle --}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDashboard">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarDashboard">
                <ul class="navbar-nav ml-auto align-items-center">
                    
                    {{-- Nome do Usuário --}}
                    <li class="nav-item mr-3">
                        <span class="text-white-50 small">Logado como:</span>
                        <span class="text-white font-weight-bold">{{ Session::get('pesquisador')['nome'] ?? 'Pesquisador' }}</span>
                    </li>

                    {{-- Separador --}}
                    <li class="nav-item d-none d-lg-block text-white-50 mx-2">|</li>

                    {{-- Botão Logout --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                        {{-- Formulário Oculto de Logout --}}
                        <form id="logout-form" action="{{ route('pesquisa.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- CONTEÚDO PRINCIPAL --}}
    <main class="py-5">
        @yield('conteudo')
    </main>

    {{-- Scripts Globais --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>