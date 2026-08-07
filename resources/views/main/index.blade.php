<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROJETO...</title>

    {{-- Bootstrap CDN --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    {{-- CSS Customizado --}}
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-leaf mr-2"></i> [NOME DO EVENTO]
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#sobre">Sobre</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#galeria">Galeria</a>
                    </li>
                    <li class="nav-item ml-3">
                        <a class="btn btn-nav shadow-sm" href="#acesso">
                            <i class="fas fa-sign-in-alt mr-1"></i> Entrar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <span class="text-uppercase font-weight-bold mb-3 d-block text-warning"
                        style="letter-spacing: 2px;">Inovação e Ciência</span>

                    <h1 class="hero-title">Jornada de Iniciação Científica</h1>

                    <p class="hero-subtitle">
                        Conectando conhecimento, pesquisa e futuro na agropecuária brasileira.
                    </p>

                    {{-- Datas Dinâmicas --}}
                    <div class="mt-4">
                        <div class="date-badge">
                            <i class="fas fa-calendar-alt mr-3 fa-lg"></i>
                            <div class="text-left">
                                <small class="d-block text-white-50 text-uppercase" style="font-size: 0.7rem;">Data do
                                    Evento</small>
                                <span>{{ $evento['data_completa'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ACCESS PORTAL SECTION --}}
    <section class="access-section" id="acesso">
        <div class="container">
            <div class="row justify-content-center">

                {{-- CARD 1: PARTICIPANTE --}}
                <div class="col-md-5 col-lg-4 mb-4">
                    <div class="access-card card-participante">
                        <div class="icon-circle">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="font-weight-bold mb-2">Estudantes</h3>
                        <p class="text-muted mb-4">
                            Inscreva-se, submeta seus resumos e acompanhe avaliações.
                        </p>

                        <a href="{{ route('inscricao') }}" class="btn btn-primary btn-block btn-access shadow-sm"
                            style="background-color: #3182ce; border: none;">
                            Área do Participante
                        </a>
                    </div>
                </div>

                {{-- CARD 2: ADM --}}
                <div class="col-md-5 col-lg-4 mb-4">
                    <div class="access-card card.verde">
                        <div class="icon-circle">
                            <i class="fas fa-id-card-alt"></i>
                        </div>
                        <h3 class="font-weight-bold mb-2">Colaboradores</h3>
                        <p class="text-muted mb-4">
                            Acesso para avaliadores, comissão e empregados.
                        </p>

                        <a href="{{ route('empregados') }}" class="btn btn-success btn-block btn-access shadow-sm"
                            style="background-color: #2d8a4e; border: none;">
                            Login Administrativo
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- GALERIA --}}
    <section class="gallery-section" id="galeria">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase text-success font-weight-bold" style="letter-spacing: 2px;">Galeria</h6>
                <h2 class="font-weight-bold text-dark">Melhores Momentos</h2>
                <div class="bg-success mx-auto mt-3" style="width: 50px; height: 4px; border-radius: 2px;"></div>
            </div>

            <div class="row">
                @foreach($fotos as $fotoUrl)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="gallery-img-wrapper">
                            <img src="{{ asset('img/' . $fotoUrl) }}" alt="[NOME EVENTO]" class="gallery-img">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer>
        <div class="container text-center">
            <h4 class="font-weight-bold mb-4">[NOME EVENTO]</h4>
            <p class="text-white-50 mb-4" style="max-width: 600px; margin: 0 auto;">
                Inovação tecnológica focada na geração de conhecimento e tecnologia para a agropecuária brasileira.
            </p>
            <div class="mb-5 mt-4">
                <a href="#" class="text-white mx-3"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-white mx-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white mx-3"><i class="fab fa-youtube fa-lg"></i></a>
            </div>
            <hr class="border-secondary" style="opacity: 0.2;">
            <p class="small text-white-50 mb-0 mt-3">
                &copy; {{ date('Y') }} [LOCAL EVENTO]. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS Customizado --}}
    <script src="{{ asset('js/landing.js') }}"></script>
</body>

</html>