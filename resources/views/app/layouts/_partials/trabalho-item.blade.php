@php
    $nivel = $nivel ?? 0;
@endphp


<li class="list-group-item d-flex justify-content-between align-items-center">
    Nome do Trabalho:
    <span class="work-name">{{ $trabalho->inscricao->nome_trabalho }}</span>

    <span class="ml-auto">
        Nome do Autor:
        <span class="author-name">{{ $trabalho->inscricao->nome_autor }}</span><br>

        @if ($nivel >= 1)
            <span>
                Data:
                <span class="presentation-date">{{ $trabalho->data_apresentacao }}</span>
            </span>
        @endif

        Horário:
        <span class="presentation-time">{{ $trabalho->horario_apresentacao }}</span>

        @if ($nivel == 0)
            <button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#votarModal"
                data-id="{{ $trabalho->id }}" data-resumo="{{ $trabalho->avaliacoes->first()->resumo ?? '' }}"
                data-importancia="{{ $trabalho->avaliacoes->first()->importancia ?? '' }}"
                data-apresentacao="{{ $trabalho->avaliacoes->first()->apresentacao ?? '' }}"
                data-desempenho="{{ $trabalho->avaliacoes->first()->desempenho ?? '' }}">
                Avaliar
            </button>

        @endif
    </span>

    @if ($nivel >= 1)
        <div class="ml-3">
            <strong>Avaliadores:</strong>
            <ul>
                @foreach ($trabalho_avaliadores as $avaliador)
                    @if ($avaliador->trabalho_id == $trabalho->id)
                        <li>{{ $avaliador->avaliador_nome }}</li>
                    @endif
                @endforeach

            </ul>
        </div>
    @endif
</li>