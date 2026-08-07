{{-- <!-- Formulário de Upload de Arquivo --> --}}
<div id="formArquivo" style="display: none;">
    <form action="php/arquivo_apresentacao.php" method="POST" enctype="multipart/form-data" class="upload-form">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 style="color:black;">Arquivo de Apresentação</h2>
        </div>
        <div>
            <button type="button" class="btn btn-info toggleFormsBtn">Voltar para Detalhes</button>
        </div>
        <div class="file-upload">
            <input id="arquivo" name="arquivo" type="file" class="file-input" required>
            <label for="arquivo" class="file-label">
                <svg width="150" height="150" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15"
                        stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>Selecionar Arquivo .pdf</p>
            </label>
            <p id="file-name" style="margin-top: 10px; color: #007bff; font-weight: bold;">Nenhum arquivo selecionado
            </p>
            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
        </div>
    </form>
</div>
