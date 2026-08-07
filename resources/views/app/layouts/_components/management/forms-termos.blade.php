@php
    // 1. Recupera o usuário logado
    $userLogado = auth()->user();
    $nivelLogado = $userLogado ? (int) $userLogado->nivel_de_acesso_user : 0;

    // 2. REGRA DE OURO: Apenas Presidente (Nível 3) pode editar
    $podeEditar = ($nivelLogado === 3);

    // 3. Define a trava
    $disabled = $podeEditar ? '' : 'disabled';
@endphp

{{-- Aviso visual para quem não é Presidente --}}
@if(!$podeEditar)
    <div class="alert alert-info shadow-sm mb-3">
        <i class="fas fa-info-circle"></i> <strong>Modo de Leitura:</strong> A edição dos Termos e Instruções é exclusiva do
        <strong>Presidente</strong> da comissão.
    </div>
@endif

<form action="{{ route('app.termos.salvar') }}" method="post">
    @csrf

    <div class="form-group">
        <label for="termos">Adicione os Termos e Acordo:</label>
        {{-- Adicionado o $disabled --}}
        <textarea name="termos" id="termos" class="form-control" rows="5" {{ $disabled }}>{{ $termosData->termos ?? '' }}</textarea>
    </div>

    <div class="form-group">
        <label for="instrucoes">Adicione as Instruções de Votação:</label>
        {{-- Adicionado o $disabled --}}
        <textarea name="instrucoes" id="instrucoes" class="form-control" rows="5" {{ $disabled }}>{{ $termosData->instrucoes ?? '' }}</textarea>
    </div>

    {{-- Botão travado se não for Presidente --}}
    <button type="submit" class="btn btn-primary" {{ $disabled }}>
        <i class="fas fa-save"></i> Enviar Atualizações
    </button>
</form>