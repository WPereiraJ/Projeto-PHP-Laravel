{{ $slot }}

<form action="" method="post">
    @csrf

    {{-- DADOS DA PESQUISA --}}
    <h4 class="section-title"><i class="fas fa-flask"></i> Dados da Pesquisa</h4>

    <div class="form-group">
        <label>Título da Pesquisa <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nome_trabalho" placeholder="Título completo do trabalho" required>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Financiamento <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="financiamento" placeholder="Ex: CNPq" required>
        </div>
        <div class="form-group col-md-4">
            <label>SEG/Contrato <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="seg_contrato" placeholder="Nº SEG" required>
        </div>
        <div class="form-group col-md-4">
            <label>SISGEN</label>
            <input type="text" class="form-control" name="sisgen" placeholder="Registro (Opcional)">
        </div>
    </div>

    {{-- AUTOR PRINCIPAL --}}
    <h4 class="section-title"><i class="fas fa-user-graduate"></i> Autor Principal</h4>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Nome Completo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nome_autor" required>
        </div>
        <div class="form-group col-md-6">
            <label>E-mail <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Afiliação (Instituição) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="afiliacao_autor" required>
        </div>
        <div class="form-group col-md-6">
            <label>CPF ou RG <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="cpf_rg" required>
        </div>
    </div>

    {{-- ORIENTADOR --}}
    <h4 class="section-title"><i class="fas fa-chalkboard-teacher"></i> Orientador</h4>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Nome do Orientador <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="orientador" required>
        </div>
        <div class="form-group col-md-6">
            <label>E-mail do Orientador <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email_orientador" required>
        </div>
    </div>

    {{-- CO-AUTORES --}}
    <h4 class="section-title d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users"></i> Co-autores</span>
        <button class="btn btn-sm btn-outline-success" type="button" data-toggle="collapse"
            data-target="#collapseAuthors">
            <i class="fas fa-plus-circle mr-1"></i> Adicionar Autores
        </button>
    </h4>

    {{-- 2º Autor (Sempre visível) --}}
    <div class="p-3 mb-3 co-autor-card">
        <h6 class="font-weight-bold text-dark">2º Autor</h6>
        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control" name="nome_autor2" placeholder="Nome" required>
            </div>
            <div class="form-group col-md-6">
                <input type="text" class="form-control" name="afiliacao_autor2" placeholder="Instituição" required>
            </div>
        </div>
    </div>

    {{-- Lista Colapsável (3 ao 10) --}}
    <div class="collapse" id="collapseAuthors">
        @for ($i = 3; $i <= 10; $i++)
            <div class="p-3 mb-3 co-autor-card">
                <h6 class="font-weight-bold text-muted">{{ $i }}º Autor (Opcional)</h6>
                <div class="form-row">
                    <div class="form-group col-md-6 mb-2">
                        <input id="nome_autor{{ $i }}" type="text" class="form-control form-control-sm"
                            name="nome_autor{{ $i }}" placeholder="Nome">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input type="text" class="form-control form-control-sm" name="afiliacao_autor{{ $i }}"
                            placeholder="Instituição">
                    </div>
                </div>
            </div>
        @endfor
    </div>

    {{-- CATEGORIA --}}
    <h4 class="section-title"><i class="fas fa-tag"></i> Categoria</h4>
    <div class="form-group text-center mb-5">
        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
            <label class="btn btn-outline-secondary">
                <input type="radio" name="category" value="PIBIC/PIBIT" required> PIBIC/PIBIT
            </label>
            <label class="btn btn-outline-secondary">
                <input type="radio" name="category" value="Graduação"> Graduação
            </label>
            <label class="btn btn-outline-secondary">
                <input type="radio" name="category" value="Pós-Graduação"> Pós-Graduação
            </label>
        </div>
    </div>

    {{-- BOTÕES DE AÇÃO (MODAIS) --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-2">
            <button type="button" class="btn btn-outline-info btn-block py-2" id="btnTermos" data-toggle="modal"
                data-target="#termosModal">
                <i class="fas fa-file-contract mr-2"></i> Ler Termos e Condições
            </button>
        </div>
        <div class="col-md-6 mb-2">
            <button type="button" class="btn btn-outline-secondary btn-block py-2" id="btnResumo" data-toggle="modal"
                data-target="#resumoModal">
                <i class="fas fa-file-alt mr-2"></i> Inserir Resumo
            </button>
        </div>
    </div>

    {{-- INPUTS OCULTOS --}}
    <input type="hidden" id="hiddenTermos" name="termos" value="0">
    <input type="hidden" id="hiddenResumo" name="resumo" value="">
    <input type="hidden" id="hiddenTermosIndexacao" name="termosIndexacao" value="">

    {{-- Botão de Submit --}}
    <div class="mt-4">
        <button class="btn btn-success btn-block btn-action shadow" type="submit">
            <i class="fas fa-check mr-2"></i> Finalizar Cadastro
        </button>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('main.login-pesquisas') }}" class="text-muted small">Já tem cadastro? Login</a>
    </div>
</form>

@push('scripts')
    <script src="{{ asset('js/cadastro.js') }}"></script>
@endpush