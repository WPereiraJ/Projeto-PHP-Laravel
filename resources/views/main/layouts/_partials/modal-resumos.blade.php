<!-- Modal Resumo -->
<div class="modal fade" id="resumoModal" tabindex="-1" role="dialog" aria-labelledby="resumoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumoModalLabel">Inserir Resumo e Termos de Indexação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="resumo">Resumo</label>
                    <textarea class="form-control" id="resumo" name="resumo" rows="5" maxlength="1650"
                        placeholder="Digite o resumo aqui" required></textarea>
                    <small id="contador">0 / 1650 caracteres</small>
                </div>
                <div class="form-group">
                    <label for="termosIndexacao">Termos de Indexação</label>
                    <textarea class="form-control" id="termosIndexacao" name="termosIndexacao" placeholder="Digite os termos de indexação"
                        required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="inserirResumo()">Salvar</button>
            </div>
        </div>
    </div>
</div>
