<div class="modal fade" id="instrucoesModal" tabindex="-1" role="dialog" aria-labelledby="instrucoesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="instrucoesModalLabel">
                    <i class="fas fa-info-circle"></i> Instruções de Votação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <p class="text-dark" style="font-size: 1.1rem; line-height: 1.6;">
                        {!! nl2br(e($instrucoes)) !!}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary px-4" data-dismiss="modal">Entendi</button>
            </div>
        </div>
    </div>
</div>