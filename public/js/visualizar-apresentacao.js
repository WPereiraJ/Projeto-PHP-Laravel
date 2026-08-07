document.addEventListener('DOMContentLoaded', function () {

    // Captura o evento de abertura do modal
    $('#modalVisualizarApresentacao').on('show.bs.modal', function (event) {

        let button = $(event.relatedTarget);

        // Extrai as informações
        let titulo = button.data('titulo');
        let autor = button.data('autor');
        let avaliadores = button.data('avaliadores');

        // Atualiza os cabeçalhos do modal
        let modal = $(this);
        modal.find('#viewTituloTrabalho').text(titulo);
        modal.find('#viewAutorTrabalho').text(autor);

        // O corpo da tabela original
        let container = modal.find('#listaAvaliadoresBody');
        container.empty();

        // Validação se não tem avaliador
        if (!avaliadores || avaliadores.length === 0) {
            container.append(`
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle mr-2"></i> Este trabalho ainda não possui avaliadores vinculados.
                    </td>
                </tr>
            `);
            return;
        }

        // Loop por cada avaliador para criar a linha (tr) da tabela
        avaliadores.forEach(function (avaliador) {

            let notasDadas = 0;
            let notasPendentes = 0;
            let htmlNotas = `<ul class="list-unstyled mb-0">`;

            // Loop dinâmico pelas notas da tabela pivot
            for (const [criterio, nota] of Object.entries(avaliador.pivot)) {

                // === FILTRO BLINDADO (Ignora IDs e Datas) ===
                if (criterio === 'id' || criterio.endsWith('_id') || criterio === 'created_at' || criterio === 'updated_at') {
                    continue;
                }

                // Formata o nome da coluna (ex: "nota_apresentacao" vira "Nota Apresentacao")
                let nomeFormatado = criterio.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                // Verifica se o avaliador preencheu esta nota específica
                if (nota !== null && nota !== "") {
                    notasDadas++;
                    htmlNotas += `
                        <li class="mb-1">
                            <span class="text-muted small">${nomeFormatado}:</span> 
                            <span class="badge badge-primary badge-pill ml-1">${nota}</span>
                        </li>
                    `;
                } else {
                    notasPendentes++;
                    htmlNotas += `
                        <li class="mb-1">
                            <span class="text-muted small">${nomeFormatado}:</span> 
                            <span class="badge badge-warning text-white badge-pill ml-1">Pendente</span>
                        </li>
                    `;
                }
            }

            htmlNotas += `</ul>`;

            // Caso o banco não retorne nenhum critério válido
            if (notasDadas === 0 && notasPendentes === 0) {
                htmlNotas = '<span class="text-muted small">Nenhum critério definido.</span>';
            }

            // Define o visual do Status Geral baseado na contagem
            let badgeStatus = '';
            if (notasDadas > 0 && notasPendentes === 0) {
                // Deu todas as notas
                badgeStatus = '<span class="badge badge-success px-3 py-2 rounded-pill"><i class="fas fa-check mr-1"></i> Avaliado</span>';
            } else if (notasDadas > 0 && notasPendentes > 0) {
                // Deu algumas notas, mas faltam outras
                badgeStatus = '<span class="badge badge-info px-3 py-2 rounded-pill"><i class="fas fa-exclamation-circle mr-1"></i> Incompleto</span>';
            } else {
                // Não deu nenhuma nota
                badgeStatus = '<span class="badge badge-warning px-3 py-2 rounded-pill text-white"><i class="fas fa-clock mr-1"></i> Pendente</span>';
            }

            // Monta a linha da tabela
            let tr = `
                <tr class="border-bottom">
                    <td class="pl-4 align-middle">
                        <div class="font-weight-bold text-dark"><i class="fas fa-user-circle text-secondary mr-2"></i> ${avaliador.nome}</div>
                    </td>
                    <td class="text-center align-middle">
                        ${badgeStatus}
                    </td>
                    <td class="text-left align-middle">
                        ${htmlNotas}
                    </td>
                </tr>
            `;

            container.append(tr);
        });
    });

    // Limpa o modal ao fechar para evitar falhas visuais no próximo clique
    $('#modalVisualizarApresentacao').on('hidden.bs.modal', function () {
        $(this).find('#viewTituloTrabalho').text('...');
        $(this).find('#viewAutorTrabalho').text('...');
        $(this).find('#listaAvaliadoresBody').empty();
    });

});