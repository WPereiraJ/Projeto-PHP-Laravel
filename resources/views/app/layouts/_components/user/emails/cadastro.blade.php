<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/emails.css') }}">
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Sistema [NOME DO EVENTO]</h1>
            </div>

            <div class="content">
                <p class="welcome-text">Olá, <strong>{{ $name }}</strong>,</p>

                <p>Informamos que o seu cadastro no sistema <strong>[NOME DO EVENTO]</strong> foi realizado com sucesso.
                    Abaixo estão as informações detalhadas para o seu acesso:</p>

                <div class="credentials-box">
                    <table>
                        @if($tipoUsuario === 'externo')
                            <tr>
                                <td class="label">Usuário/Email:</td>
                                <td>{{ $email }}</td>
                            </tr>
                            <tr>
                                <td class="label">Senha:</td>
                                <td><code>{{ $senha }}</code></td>
                            </tr>
                        @else
                            <tr>
                                <td class="label">Matrícula:</td>
                                <td>{{ $matricula }}</td>
                            </tr>
                            <tr>
                                <td class="label">E-mail:</td>
                                <td>{{ $email }}</td>
                            </tr>
                            <tr>
                                <td class="label">Senha:</td>
                                <td><em>{{ $senha }}</em></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="button-container">
                    <a href="" class="button">Acessar o Sistema</a>
                </div>
            </div>

            <div class="footer">
                <p>Este é um e-mail automático enviado pelo sistema. Por favor, não responda.</p>
                <p>&copy; {{ date('Y') }} [NOME DO EVENTO] - [LOCAL EVENTO]</p>
            </div>
        </div>
    </div>
</body>

</html>