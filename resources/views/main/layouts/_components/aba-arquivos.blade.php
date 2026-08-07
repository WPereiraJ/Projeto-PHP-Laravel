<h4 class="section-title text-info">Gerenciamento de Arquivos</h4>

{{-- Feedbacks --}}
@if($errors->has('arquivo'))
    <div class="alert alert-danger">{{ $errors->first('arquivo') }}</div>
@endif
@if($errors->has('tipo_arquivo'))
    <div class="alert alert-danger">{{ $errors->first('tipo_arquivo') }}</div>
@endif

@if($edicaoPermitida ?? false)
    {{-- AVISO DE PRAZOS SEPARADOS MANTENDO SEU ESTILO --}}
    <div class="alert alert-info py-2 small shadow-sm border-0 mb-4">
        <i class="fas fa-info-circle mr-1"></i> <strong>Prazos de Envio:</strong>
        <ul class="mb-0 mt-1 pl-4">
            <li><b>Resumo:</b> Permitido até {{ isset($datasEvento->data_final_inscricoes) ? \Carbon\Carbon::parse($datasEvento->data_final_inscricoes)->format('d/m/Y') : 'indefinido' }}</li>
            <li><b>Apresentação:</b> Permitido até {{ isset($datasEvento->data_final_arquivo) ? \Carbon\Carbon::parse($datasEvento->data_final_arquivo)->format('d/m/Y') : 'indefinido' }}</li>
        </ul>
    </div>

    <div class="upload-area mb-5 border rounded p-4 bg-white shadow-sm text-center">
        <form action="{{ route('pesquisa.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
            <h5 class="text-dark font-weight-bold">Enviar Arquivo</h5>
            <p class="text-muted small mb-4">Formatos aceitos: PDF. Tamanho máx: 5MB.</p>
            
            <div class="form-row justify-content-center mb-3">
                <div class="form-group col-md-8 text-left">
                    <label class="font-weight-bold">O que você está enviando? <span class="text-danger">*</span></label>
                    <select name="tipo_arquivo" class="form-control" required>
                        <option value="">-- Selecione --</option>
                        <option value="resumo">Resumo Simples (PDF)</option>
                        <option value="apresentacao">Slides da Apresentação (PDF)</option>
                    </select>
                </div>
            </div>

            <div class="custom-file w-75 mx-auto text-left mb-3">
                <input type="file" class="custom-file-input" id="arquivo" name="arquivo" required accept="application/pdf">
                <label class="custom-file-label" for="arquivo">Escolher PDF...</label>
            </div>

            <button type="submit" class="btn btn-primary px-5 rounded-pill font-weight-bold">
                <i class="fas fa-paper-plane mr-2"></i> Enviar
            </button>
        </form>
    </div>
@else
    <div class="alert alert-warning shadow-sm">
        <i class="fas fa-lock mr-2"></i> O prazo para envio e alteração de arquivos está encerrado.
    </div>
@endif

{{-- Apresentação Atual (Fica na tb_inscricoes) --}}
<h5 class="font-weight-bold mb-3 mt-5"><i class="fas fa-project-diagram mr-2"></i> Apresentação Atual</h5>
<div class="card border-left-primary shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            @if(!empty($inscricao->nome_arquivo))
                <h6 class="mb-0 font-weight-bold text-dark">{{ $inscricao->nome_arquivo }}</h6>
                <small class="text-success"><i class="fas fa-check-circle mr-1"></i> Arquivo principal recebido</small>
            @else
                <h6 class="mb-0 text-muted">Nenhuma apresentação enviada ainda.</h6>
            @endif
        </div>
    </div>
</div>

{{-- Histórico de Resumos e Apresentações Antigas (Vem da tb_arquivos_resumos) --}}
<h5 class="font-weight-bold mb-3"><i class="fas fa-history mr-2"></i> Histórico de Envios</h5>

<div class="table-responsive">
    <table class="table table-hover table-bordered bg-white shadow-sm">
        <thead class="thead-light">
            <tr>
                <th>Data / Hora</th>
                <th>Tipo</th>
                <th>Nome do Arquivo</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historicoArquivos as $arquivo)
            <tr>
                <td class="align-middle">{{ \Carbon\Carbon::parse($arquivo->created_at)->format('d/m/Y H:i') }}</td>
                <td class="align-middle">
                    {{-- LÓGICA DE BADGES ATUALIZADA (RESUMO ANTIGO) --}}
                    @if($arquivo->tipo == 'resumo')
                        @if($loop->first)
                            <span class="badge badge-info px-2 py-1">Resumo</span>
                        @else
                            <span class="badge badge-warning px-2 py-1 text-white">Resumo Antigo</span>
                        @endif
                    @else
                        <span class="badge badge-secondary px-2 py-1">Apresentação Antiga</span>
                    @endif
                </td>
                <td class="align-middle text-truncate" style="max-width: 200px;">{{ $arquivo->nome_arquivo }}</td>
                <td class="text-center align-middle">
                    @if($loop->first && $arquivo->tipo == 'resumo')
                        <span class="badge badge-success">Versão Final</span>
                    @else
                        <span class="badge badge-light border text-muted">Histórico</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i><br>
                    Nenhum arquivo no histórico.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>