<tr class="table-primary" onclick="toggleTrabalhos('{{ md5($categoria) }}')">
    <td colspan="7" class="categoria-section">{{ $categoria }}</td>
</tr>

@foreach ($trabalhos as $trabalho)
    <tr onclick="toggleTrabalhos('{{ md5($categoria . $trabalho->trabalho_id) }}')">
        <td>{{ $categoria }}</td>
        <td>{{ $trabalho->nome_trabalho }}</td>
        <td>{{ $trabalho->nome_autor }}</td>

        @if ($nivel != 0)
            <td>{{ $trabalho->orientador }}</td>
            <td>{{ $trabalho->sisgen }}</td>
            <td>{{ $trabalho->seg_contrato }}</td>
            @if ($nivel > 0)
                <td>{{ $trabalho->clpi_aprovado }}</td>
            @endif
        @endif
    </tr>

    <tr id="{{ md5($categoria . $trabalho->trabalho_id) }}" class="collapse-row">
        <td colspan="7">
            <div class="ml-3">
                <strong><h2>Resumo</h2></strong>
                <div class="trabalho-detalhes">{{ $trabalho->resumo }}</div>

                <strong><h4>Termos de Indexação</h4></strong>
                <div class="trabalho-detalhes">{{ $trabalho->termos_indexacao }}</div>

                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#avaliarModal"
                        data-id="{{ $trabalho->trabalho_id }}"
                        data-name="{{ $trabalho->nome_trabalho }}">
                        Avaliar
                    </button>
                </div>
                <hr>
            </div>
        </td>
    </tr>
@endforeach
