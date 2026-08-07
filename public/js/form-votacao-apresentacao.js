document.addEventListener('DOMContentLoaded', function () {
    $('#votarModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const modal = $(this);

        modal.find('#trabalhoId').val(button.data('id') || '');
        
        modal.find('#resumo').val(button.data('resumo') != null ? button.data('resumo') : '');
        modal.find('#importancia').val(button.data('importancia') != null ? button.data('importancia') : '');
        modal.find('#apresentacao').val(button.data('apresentacao') != null ? button.data('apresentacao') : '');
        modal.find('#desempenho').val(button.data('desempenho') != null ? button.data('desempenho') : '');

        const avaliadorId = button.data('avaliador');
        if (avaliadorId) {
            modal.find('#avaliadorId').val(avaliadorId);
        }
    });
});