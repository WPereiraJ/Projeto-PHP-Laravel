@php
    // 1. Recupera o usuário logado via Auth
    $userLogado = auth()->user();
    $nivelLogado = $userLogado ? (int) $userLogado->nivel_de_acesso_user : 0;

    // 2. WHITELIST
    // 1 = Comissão | 3 = Presidente
    $quemPodeMexer = [1, 3];

    // 3. Verifica a permissão
    $podeEditar = in_array($nivelLogado, $quemPodeMexer);

    // 4. Variável de trava
    $disabled = $podeEditar ? '' : 'disabled';
@endphp

{{-- Aviso visual para quem está bloqueado --}}
@if(!$podeEditar)
    <div class="alert alert-info shadow-sm mb-3">
        <i class="fas fa-info-circle"></i> <strong>Modo de Leitura:</strong> A definição de prazos é exclusiva para membros
        da <strong>Comissão</strong> e <strong>Presidente</strong>.
    </div>
@endif

<form action="{{ route('app.prazos.update') }}" method="post">
    @csrf

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">

            {{-- SEÇÃO 1: INSCRIÇÕES --}}
            <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">Data de Inscrições</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="data_inicio_inscricoes">Data de Início <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="data_inicio_inscricoes"
                        value="{{ isset($prazos->data_inicio_inscricoes) ? \Carbon\Carbon::parse($prazos->data_inicio_inscricoes)->format('Y-m-d') : '' }}"
                        required {{ $disabled }}>
                </div>
                <div class="form-group col-md-6">
                    <label for="data_final_inscricoes">Data de Término <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_final_inscricoes" name="data_final_inscricoes"
                        value="{{ isset($prazos->data_final_inscricoes) ? \Carbon\Carbon::parse($prazos->data_final_inscricoes)->format('Y-m-d') : '' }}"
                        required {{ $disabled }}>
                </div>
            </div>

            {{-- SEÇÃO 2: EVENTO --}}
            <h6 class="font-weight-bold text-dark mt-4 mb-3 border-bottom pb-2">Data do Evento</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="data_inicio_evento">Data de Início <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_inicio_evento" name="data_inicio_evento"
                        value="{{ isset($prazos->data_inicio_evento) ? \Carbon\Carbon::parse($prazos->data_inicio_evento)->format('Y-m-d') : ''}}"
                        required {{ $disabled }}>
                </div>
                <div class="form-group col-md-6">
                    <label for="data_final_evento">Data de Término <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_final_evento" name="data_final_evento"
                        value="{{ isset($prazos->data_final_evento) ? \Carbon\Carbon::parse($prazos->data_final_evento)->format('Y-m-d') : ''}}"
                        required {{ $disabled }}>
                </div>
            </div>

            {{-- SEÇÃO 3: ARQUIVOS --}}
            <h6 class="font-weight-bold text-dark mt-4 mb-3 border-bottom pb-2">Data de Envio de Arquivo</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="data_inicio_arquivo">Data de Início <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_inicio_arquivo" name="data_inicio_arquivo"
                        value="{{ isset($prazos->data_inicio_arquivo) ? \Carbon\Carbon::parse($prazos->data_inicio_arquivo)->format('Y-m-d') : ''}}"
                        required {{ $disabled }}>
                </div>
                <div class="form-group col-md-6">
                    <label for="data_final_arquivo">Data de Término <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_final_arquivo" name="data_final_arquivo"
                        value="{{ isset($prazos->data_final_arquivo) ? \Carbon\Carbon::parse($prazos->data_final_arquivo)->format('Y-m-d') : ''}}"
                        required {{ $disabled }}>
                </div>
            </div>

            <div class="mt-4">
                {{-- Botão travado também --}}
                <button type="submit" class="btn btn-primary" {{ $disabled }}>Definir Datas do Evento</button>
            </div>
        </div>
    </div>
</form>