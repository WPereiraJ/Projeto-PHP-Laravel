$(document).ready(function () {
    // Quando o usuário abrir a lista de autores extras, foca no primeiro campo
    $('#collapseAuthors').on('shown.bs.collapse', function () {
        // Verifica se o elemento existe antes de tentar focar
        if (document.getElementById('nome_autor3')) {
            document.getElementById('nome_autor3').focus();
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
});

/**
 * Lógica da Página de Cadastro de Pesquisa
 */

$(document).ready(function () {

    // --- Lógica do Contador de Caracteres ---
    const maxChars = 1650;

    $('#resumo').on('input', function () {
        var atual = $(this).val().length;
        var restante = maxChars - atual;

        $('#contador').text(atual + ' / ' + maxChars + ' caracteres');

        if (atual > maxChars) {
            $('#contador').addClass('text-danger').removeClass('text-muted');
        } else {
            $('#contador').removeClass('text-danger').addClass('text-muted');
        }
    });

    // --- LÓGICA DO MODAL DE RESUMO ---

    window.inserirResumo = function () {
        var resumoTexto = $('#resumo').val();
        var termosTexto = $('#termosIndexacao').val();

        // Validação simples
        if (resumoTexto.length < 50) {
            alert('O resumo é muito curto. Por favor, descreva melhor seu trabalho (mínimo 50 caracteres).');
            return;
        }

        if (termosTexto.length < 3) {
            alert('Por favor, insira os termos de indexação.');
            return;
        }

        // 1. Copia para os inputs hidden do formulário principal
        $('#hiddenResumo').val(resumoTexto);
        $('#hiddenTermosIndexacao').val(termosTexto);

        // 2. Fecha o modal
        $('#resumoModal').modal('hide');

        // 3. Feedback Visual: Muda o botão para verde
        $('#btnResumo')
            .removeClass('btn-outline-secondary')
            .addClass('btn-success')
            .html('<i class="fas fa-check-circle mr-2"></i> Resumo Inserido');

        // Tocar um feedback visual rápido
        $('#btnResumo').fadeOut(100).fadeIn(100);
    };

    // --- LÓGICA DO MODAL DE TERMOS ---

    window.aceitarTermos = function () {
        var checkbox = $('#confirmarTermos');

        // Validação
        if (!checkbox.is(':checked')) {
            alert('Você precisa marcar a caixa de seleção para aceitar os termos.');
            return;
        }

        // 1. Transfere para o input hidden do Form Principal
        // O Laravel espera "1", "on" ou "true" para validação 'accepted'
        $('#hiddenTermos').val('1');

        // 2. Feedback Visual no Botão Principal
        var btn = $('#btnTermos'); // ID do botão que abre o modal
        btn.removeClass('btn-outline-info').addClass('btn-success');
        btn.html('<i class="fas fa-check-circle mr-2"></i> Termos Aceitos');

        // 3. Fecha Modal
        $('#termosModal').modal('hide');
    };
});