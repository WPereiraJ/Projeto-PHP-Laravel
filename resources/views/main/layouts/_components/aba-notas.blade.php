<h4 class="section-title text-success"><i class="fas fa-chart-bar mr-2"></i> Quadro de Notas Detalhadas</h4>

<div class="alert alert-info shadow-sm border-0 mt-4 mb-4">
    <i class="fas fa-info-circle mr-2"></i> Abaixo estão as notas individuais atribuídas ao seu trabalho em cada um dos critérios de avaliação.
</div>

<div class="row">
    {{-- Card: Nota do Resumo --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-left-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="font-weight-bold text-dark mb-1">Resumo</h6>
                    <small class="text-muted">Avaliação do texto submetido</small>
                </div>
                <span class="badge badge-pill badge-primary px-3 py-2" style="font-size: 1.1rem;">
                    {{ $notaResumo ?? '--' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Card: Nota da Apresentação --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-left-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="font-weight-bold text-dark mb-1">Apresentação</h6>
                    <small class="text-muted">Avaliação da apresentação oral/banner</small>
                </div>
                <span class="badge badge-pill badge-info px-3 py-2" style="font-size: 1.1rem;">
                    {{ $notaApresentacao ?? '--' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Card: Nota de Importância --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-left-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="font-weight-bold text-dark mb-1">Importância</h6>
                    <small class="text-muted">Relevância do tema abordado</small>
                </div>
                <span class="badge badge-pill badge-success px-3 py-2" style="font-size: 1.1rem;">
                    {{ $notaImportancia ?? '--' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Card: Nota de Desempenho --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-left-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="font-weight-bold text-dark mb-1">Desempenho</h6>
                    <small class="text-muted">Domínio do conteúdo e respostas</small>
                </div>
                <span class="badge badge-pill badge-warning text-white px-3 py-2" style="font-size: 1.1rem;">
                    {{ $notaDesempenho ?? '--' }}
                </span>
            </div>
        </div>
    </div>
</div>