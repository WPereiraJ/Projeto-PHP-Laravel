<h4 class="section-title text-primary">Dados da Pesquisa</h4>

<form action="{{ route('pesquisa.update') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- DADOS BLOQUEADOS (Readonly) --}}
    <h5 class="text-secondary mb-3 mt-4"><i class="fas fa-lock mr-2"></i>Dados Fixos da Inscrição</h5>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Nome do Autor <small class="text-danger ml-1">(Não editável)</small></label>
            <input type="text" class="form-control bg-light" value="{{ $inscricao->nome_autor ?? '' }}" readonly>
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Orientador <small class="text-danger ml-1">(Não editável)</small></label>
            <input type="text" class="form-control bg-light" value="{{ $inscricao->orientador ?? '' }}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Título do Trabalho <small class="text-danger ml-1">(Não editável)</small></label>
        <input type="text" class="form-control bg-light" value="{{ $inscricao->nome_trabalho ?? '' }}" readonly>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Financiamento <small class="text-danger ml-1">(Não editável)</small></label>
            <input type="text" class="form-control bg-light" value="{{ $inscricao->financiamento ?? '' }}" readonly>
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">Categoria <small class="text-danger ml-1">(Não editável)</small></label>
            <input type="text" class="form-control bg-light" value="{{ strtoupper($inscricao->categoria ?? '') }}" readonly>
        </div>
    </div>

    <hr class="my-4">

    {{-- DADOS EDITÁVEIS --}}
    <h5 class="text-secondary mb-3"><i class="fas fa-edit mr-2"></i>Conteúdo do Trabalho</h5>

    @if($edicaoPermitida ?? false)
        <div class="alert alert-info py-2 small shadow-sm border-0">
            <i class="fas fa-info-circle mr-1"></i> Você pode editar os campos abaixo até o dia <b>{{ isset($datasEvento->data_final_arquivo) ? \Carbon\Carbon::parse($datasEvento->data_final_arquivo)->format('d/m/Y') : 'indefinido' }}</b>.
        </div>
    @else
        <div class="alert alert-warning py-2 small shadow-sm border-0">
            <i class="fas fa-lock mr-1"></i> O prazo para edição de dados está encerrado.
        </div>
    @endif

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">SEG Contrato</label>
            <input type="text" name="seg_contrato" 
                   class="form-control @error('seg_contrato') is-invalid @enderror {{ !($edicaoPermitida ?? false) ? 'bg-light' : '' }}" 
                   value="{{ old('seg_contrato', $inscricao->seg_contrato ?? '') }}" 
                   {{ !($edicaoPermitida ?? false) ? 'readonly' : '' }}>
            @error('seg_contrato')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">SISGEN</label>
            <input type="text" name="sisgen" 
                   class="form-control @error('sisgen') is-invalid @enderror {{ !($edicaoPermitida ?? false) ? 'bg-light' : '' }}" 
                   value="{{ old('sisgen', $inscricao->sisgen ?? '') }}" 
                   {{ !($edicaoPermitida ?? false) ? 'readonly' : '' }}>
            @error('sisgen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="font-weight-bold d-flex justify-content-between">
            <span>Resumo</span>
            @if($edicaoPermitida ?? false)
                <small id="contador_resumo" class="text-muted">0 / 1650</small>
            @endif
        </label>
        <textarea id="resumo_texto" name="resumo" rows="6" maxlength="1650"
                  class="form-control @error('resumo') is-invalid @enderror {{ !($edicaoPermitida ?? false) ? 'bg-light' : '' }}" 
                  {{ !($edicaoPermitida ?? false) ? 'readonly' : 'required' }}>{{ old('resumo', $inscricao->resumo ?? '') }}</textarea>
        @error('resumo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Termos de Indexação</label>
        <input type="text" name="termos_indexacao" 
               class="form-control @error('termos_indexacao') is-invalid @enderror {{ !($edicaoPermitida ?? false) ? 'bg-light' : '' }}" 
               value="{{ old('termos_indexacao', $inscricao->termos_indexacao ?? '') }}" 
               {{ !($edicaoPermitida ?? false) ? 'readonly' : 'required' }}>
        @error('termos_indexacao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- O botão de salvar só aparece se o prazo estiver ativo --}}
    @if($edicaoPermitida ?? false)
        <div class="text-right mt-4">
            <button type="submit" class="btn btn-success px-4 font-weight-bold"><i class="fas fa-save mr-2"></i> Salvar Alterações</button>
        </div>
    @endif
</form>

{{-- Chama o script do JS apenas se a edição for permitida --}}
@if($edicaoPermitida ?? false)
    @push('scripts')
        <script src="{{ asset('js/contador-resumo.js') }}"></script>
    @endpush
@endif