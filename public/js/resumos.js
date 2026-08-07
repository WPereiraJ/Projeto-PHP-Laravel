$(document).ready(function() {
    $('#avaliarModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id');         
        var nome = button.data('name');     

        // Captura os dados existentes para edição
        var notaResumo = button.data('nota-resumo');
        var notaImportancia = button.data('nota-importancia');
        var comentario = button.data('comentario');
        var clpiStatus = button.data('clpi-status');

        var modal = $(this);
        modal.find('.modal-title').text('Avaliar: ' + nome);
        
        modal.find('#modal_trabalho_id').val(id);
        modal.find('input[name="trabalho_id"]').val(id); 

        console.log("ID do trabalho setado para: " + id);

        // Preenche campos de AVALIADOR
        if (typeof notaResumo !== 'undefined') {
            modal.find('#resumo').val(notaResumo);
            modal.find('#importancia').val(notaImportancia);
        }

        // Preenche campos de COMISSÃO/CLPI
        if (typeof comentario !== 'undefined') {
            modal.find('#comentarios').val(comentario);
        }

        if (typeof clpiStatus !== 'undefined' && clpiStatus !== '') {
            modal.find('#clpi_aprovado').val(clpiStatus);
        } else {
            modal.find('#clpi_aprovado').val(""); 
        }
    });

    // Limpa ao fechar
    $('#avaliarModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('input[name="trabalho_id"]').val(''); 
    });
});