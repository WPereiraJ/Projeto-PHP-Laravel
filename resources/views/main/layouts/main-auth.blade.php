<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') - [NOME DO EVENTO]</title>

    {{-- Bootstrap CDN --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            /* Fundo cinza claro profissional */
            font-family: 'Open Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Simplificada para Cadastro/Login */
        .auth-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }

        .auth-content {
            flex: 1;
            /* Garante que o footer vá para baixo */
            display: flex;
            align-items: center;
            /* Centraliza verticalmente se o form for pequeno */
            justify-content: center;
            padding: 40px 0;
        }

        footer {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: auto;
        }
    </style>
</head>

<body>

    {{-- Navbar Simplificada --}}
    <nav class="auth-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-dark font-weight-bold" href="{{ route('home') }}">
                <i class="fas fa-arrow-left text-success mr-2"></i> Voltar para Início
            </a>
            <span class="text-muted small text-uppercase font-weight-bold">Área de Pesquisa</span>
        </div>
    </nav>

    {{-- Conteúdo Principal --}}
    <div class="auth-content">
        @yield('conteudo')
    </div>

    {{-- Footer --}}
    <footer>
        <div class="container text-center small">
            &copy; {{ date('Y') }} [LOCAL EVENTO] - [NOME DO EVENTO]. Todos os direitos reservados.
        </div>
    </footer>
    {{-- Modals --}}
    @stack('modais')

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>

</html>