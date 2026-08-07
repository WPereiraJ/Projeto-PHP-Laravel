<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: #4a90e2; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .notas-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .notas-table th, .notas-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .destaque { font-size: 1.2em; font-weight: bold; color: #4a90e2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Resultado Final - [NOME DO EVENTO] {{ date('Y') }}</h2>
        </div>
        
        <p>Olá, <strong>{{ $dadosAutor->nome }}</strong>!</p>
        <p>Obrigado por participar. Segue abaixo o desempenho do seu trabalho: <em>{{ $dadosAutor->titulo }}</em>.</p>

        <table class="notas-table">
            <tr><th>Resumo</th><td>{{ number_format($dadosAutor->notas['resumo'], 2, ',', '.') }}</td></tr>
            <tr><th>Apresentação</th><td>{{ number_format($dadosAutor->notas['apresentacao'], 2, ',', '.') }}</td></tr>
            <tr><th>Importância</th><td>{{ number_format($dadosAutor->notas['importancia'], 2, ',', '.') }}</td></tr>
            <tr><th>Desempenho</th><td>{{ number_format($dadosAutor->notas['desempenho'], 2, ',', '.') }}</td></tr>
            <tr style="background-color: #f9f9f9;">
                <th>Média Final</th>
                <td class="destaque">{{ number_format($dadosAutor->mediaFinal, 2, ',', '.') }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px; font-size: 1.1em;">
            Sua posição no ranking geral: <span class="destaque">{{ $posicao }}º lugar</span> 🏆
        </p>

        <p>Atenciosamente,<br>Equipe Organizadora</p>
    </div>
</body>
</html>