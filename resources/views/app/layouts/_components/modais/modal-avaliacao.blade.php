{{-- Modal Avaliação de Resumo --}}
<div class="modal fade" id="avaliarModal" tabindex="-1" aria-labelledby="avaliarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title custom-modal-title" id="avaliarModalLabel">
                    <i class="fas fa-clipboard-check mr-2"></i> Avaliação de Resumo
                </h5>
                <button type="button" class="close custom-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body custom-modal-body">
                <form id="avaliarForm" method="POST" action="{{ route('app.resumos.avaliar') }}">
                    @csrf
                    <input type="hidden" id="modal_trabalho_id" name="trabalho_id" value="">

                        {{-- SEÇÃO PARA AVALIADORES (Nível 0) --}}
                        @if(auth()->user()->nivel_de_acesso_user == 0)
                            <div class="form-group mb-4">
                                <label for="resumo" class="custom-label">
                                    <i class="fas fa-pen-nib mr-1 text-success"></i> Qualidade do Resumo
                                </label>
                                <input type="number" class="form-control custom-input" id="resumo" name="qualidadeResumo"
                                    step="0.01" min="0" max="10" placeholder="Nota de 0 a 10" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="importancia" class="custom-label">
                                    <i class="fas fa-flask mr-1 text-success"></i> Importância Técnico-Científica
                                </label>
                                <input type="number" class="form-control custom-input" id="importancia"
                                    name="importanciaCientifica" step="0.01" min="0" max="10" placeholder="Nota de 0 a 10"
                                    required>
                            </div>
                        @endif

                        {{-- SEÇÃO PARA COMISSÃO (1) E CLPI (2) --}}
                        @if(in_array(auth()->user()->nivel_de_acesso_user, [1, 2]))
                            <div class="form-group mb-4">
                                <label for="comentarios" class="custom-label">
                                    <i class="fas fa-comment-dots mr-1 text-success"></i> Comentários / Observações
                                </label>
                                <textarea class="form-control custom-input" id="comentarios" name="comentarios" rows="4"
                                    placeholder="Insira suas observações técnicas aqui..."></textarea>
                            </div>

                            {{-- APENAS CLPI (2) VÊ A APROVAÇÃO --}}
                            @if(auth()->user()->nivel_de_acesso_user == 2)
                                <div class="form-group mb-4">
                                    <label for="clpi_aprovado" class="custom-label">
                                        <i class="fas fa-check-double mr-1 text-success"></i> Decisão CLPI
                                    </label>
                                    <select class="form-control custom-input" name="clpi_aprovado" id="clpi_aprovado" required>
                                        <option value="" disabled selected>Selecione uma decisão</option>
                                        <option value="Aprovado">Aprovado</option>
                                        <option value="Reprovado">Reprovado</option>
                                        <option value="Não avaliado">Não avaliado</option>
                                    </select>
                                </div>
                            @endif
                        @endif

                        <div class="d-flex justify-content-end align-items-center mt-5">
                            <button type="button" class="btn btn-link text-muted mr-3"
                                data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary custom-submit-button">Confirmar
                                Avaliação</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>