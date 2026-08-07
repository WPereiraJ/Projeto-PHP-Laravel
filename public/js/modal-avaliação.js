$(document).ready(function() {
    $('#avaliarModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botão que acionou o modal
        var id = button.data('id');         // Extrai o ID
        var nome = button.data('name');     // Extrai o Nome

        var modal = $(this);
        modal.find('.modal-title').text('Avaliar: ' + nome);
        modal.find('#trabalho_id').val(id); // Preenche o input hidden dentro do form do modal
    });
});