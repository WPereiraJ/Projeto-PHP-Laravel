document.addEventListener('DOMContentLoaded', function() {
    // 1. Configurações
    var tempoRestante = 5; // segundos
    var display = document.getElementById('countdown');
    
    // 2. Pega a URL de redirecionamento do atributo data-url
    // Isso é necessário porque arquivos .js externos não leem Blade {{ route }}
    var redirectUrl = document.getElementById('redirect-data').getAttribute('data-url');

    // 3. Inicia o Intervalo
    var intervalo = setInterval(function() {
        tempoRestante--;
        
        // Atualiza o número na tela
        if (display) {
            display.innerText = tempoRestante;
        }

        // Verifica se acabou
        if (tempoRestante <= 0) {
            clearInterval(intervalo);
            // Redireciona
            window.location.href = redirectUrl;
        }
    }, 1000); // Executa a cada 1 segundo (1000ms)
});