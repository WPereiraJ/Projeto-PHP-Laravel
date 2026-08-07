$(document).ready(function() {
    // Mostra o nome do arquivo selecionado no input customizado
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Se houver mensagem de sucesso/erro na URL ou Sessão, ativa a tab correta
    // Exemplo: se salvou arquivo, volta para a tab de arquivos
    var hash = window.location.hash;
    if (hash) {
        $('.nav-pills a[href="' + hash + '"]').tab('show');
    }
    
    // Atualiza a URL ao clicar na tab (para o refresh manter a aba)
    $('.nav-pills a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
});