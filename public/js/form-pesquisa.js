const formDetalhes = document.getElementById('formDetalhesPesquisa');
const formArquivo = document.getElementById('formArquivo');
const toggleButtons = document.querySelectorAll('.toggleFormsBtn'); // Seleciona todos os botões de alternância

// Adiciona o evento de clique a todos os botões
toggleButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Alterna a visibilidade dos formulários
        if (formDetalhes.style.display === 'block') {
            formDetalhes.style.display = 'none';
            formArquivo.style.display = 'block';
        } else {
            formDetalhes.style.display = 'block';
            formArquivo.style.display = 'none';
        }
    });
});

// Captura o elemento do campo de arquivo e do nome do arquivo
const fileInput = document.getElementById('arquivo');
const fileNameDisplay = document.getElementById('file-name');

// Adiciona o evento para monitorar a seleção de arquivos
fileInput.addEventListener('change', function () {
    // Se um arquivo for selecionado, exibe o nome dele; caso contrário, mostra uma mensagem padrão
    if (this.files && this.files.length > 0) {
        fileNameDisplay.textContent = `Arquivo selecionado: ${this.files[0].name}`;
    } else {
        fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
    }
});