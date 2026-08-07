/* public/js/arquivos.js */

document.addEventListener('DOMContentLoaded', function () {
    const arquivoInput = document.getElementById('arquivoInput');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const uploadForm = document.getElementById('uploadForm');

    if (arquivoInput) {
        arquivoInput.addEventListener('change', function () {
            const fileName = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado';

            if (this.files[0]) {
                fileNameDisplay.textContent = fileName;
                fileNameDisplay.classList.remove('text-muted');
                fileNameDisplay.classList.add('text-success');
                this.closest('.file-upload-wrapper').style.borderColor = '#1d6736';
            } else {
                fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
                fileNameDisplay.classList.add('text-muted');
                fileNameDisplay.classList.remove('text-success');
            }
        });
    }
});