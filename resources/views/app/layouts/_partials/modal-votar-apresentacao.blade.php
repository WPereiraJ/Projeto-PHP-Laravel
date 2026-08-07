  {{-- Modal Votar --}}
  <div class="modal fade" id="votarModal" tabindex="-1" aria-labelledby="votarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content custom-modal-content">
        <div class="modal-header custom-modal-header">
          <h5 class="modal-title custom-modal-title" id="votarModalLabel">Votação</h5>
          <button type="button" class="close custom-modal-close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body custom-modal-body">
          <form id="votarForm" method="POST" action="{{route('app.apresentacao.votacao')}}">
          @csrf
            <input type="hidden" id="trabalhoId" name="trabalho_id">
            <input type="hidden" id="avaliadorId" name="avaliador_id" value="{{$usuario['id'] ?? ''}}">
            <div class="form-group">
              <label for="resumo" class="custom-label">Qualidade do Resumo</label>
              <input type="number" class="form-control custom-input" id="resumo" name="resumo" step="0.01" max="10" required>
            </div>
            <div class="form-group">
              <label for="importancia" class="custom-label">Importância técnico-científica</label>
              <input type="number" class="form-control custom-input" id="importancia" name="importancia" step="0.01" max="10" required>
            </div>
            <div class="form-group">
              <label for="apresentacao" class="custom-label">Qualidade da Apresentação Oral</label>
              <input type="number" class="form-control custom-input" id="apresentacao" name="apresentacao" step="0.01" max="10" required>
            </div>
            <div class="form-group">
              <label for="desempenho" class="custom-label">Desempenho na interação com a banca</label>
              <input type="number" class="form-control custom-input" id="desempenho" name="desempenho" step="0.01" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary custom-submit-button">Enviar</button>
          </form>
        </div>
      </div>
    </div>
  </div>