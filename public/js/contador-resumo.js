document.addEventListener('DOMContentLoaded', function() {
    const resumoArea = document.getElementById('resumo_texto');
    const contador = document.getElementById('contador_resumo');
    
    if (resumoArea && contador) {
        const atualizarContador = () => {
            const tam = resumoArea.value.length;
            contador.textContent = tam + ' / 1650 caracteres';
            
            if (tam >= 1650) {
                contador.classList.add('text-danger', 'font-weight-bold');
            } else {
                contador.classList.remove('text-danger', 'font-weight-bold');
            }
        };
        
        // Dispara sempre que o usuário digitar ou colar um texto
        resumoArea.addEventListener('input', atualizarContador);
        
        // Inicializa o contador assim que a página carrega
        atualizarContador(); 
    }
});