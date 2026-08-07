<div class="modal fade" id="termosModal" tabindex="-1" role="dialog" aria-labelledby="termosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document"> {{-- modal-scrollable é importante para
        textos longos --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="termosModalLabel">Termos e Condições</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-justify">
                @if(isset($termos) && !empty($termos->termos))
                    <div class="termos-texto p-3 bg-light rounded border">
                        {{-- nl2br converte quebras de linha do banco em <br> --}}
                        {!! nl2br(e($termos->termos)) !!}
                    </div>
                @else
                    <div class="alert alert-warning">
                        Os termos de uso ainda não foram cadastrados no sistema.
                    </div>
                @endif

                <hr>

                <div class="form-check mt-3 p-3 bg-white border rounded">
                    <input type="checkbox" class="form-check-input" id="confirmarTermos"
                        style="transform: scale(1.5); margin-left: -5px; margin-right: 10px;">
                    <label class="form-check-label font-weight-bold ml-2" for="confirmarTermos"
                        style="cursor: pointer;">
                        Declaro que li e aceito integralmente os Termos e Condições acima.
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                {{-- A função JS aceitarTermos() será chamada aqui --}}
                <button type="button" class="btn btn-primary" onclick="aceitarTermos()">Confirmar e Aceitar</button>
            </div>
        </div>
    </div>
</div>