<div class="modal fade" id="modalVisualizarApresentacao" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-list-alt mr-2"></i> Detalhes da Avaliação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="text-muted small text-uppercase font-weight-bold mb-0">Trabalho</label>
                    <h5 class="font-weight-bold text-dark" id="viewTituloTrabalho">...</h5>
                    <p class="text-secondary mb-0"><i class="fas fa-user mr-1"></i> <span
                            id="viewAutorTrabalho">...</span></p>
                </div>

                <div class="card bg-light border-0 rounded-lg">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pl-4 text-muted small text-uppercase">Avaliador</th>
                                        <th class="text-center text-muted small text-uppercase">Status</th>
                                        {{-- Mudei apenas o texto deste TH para abranger todas as notas --}}
                                        <th class="text-left text-muted small text-uppercase">Notas Detalhadas</th>
                                    </tr>
                                </thead>
                                <tbody id="listaAvaliadoresBody">
                                    {{-- O JavaScript vai preencher os <tr> aqui --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 bg-white" style="border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>