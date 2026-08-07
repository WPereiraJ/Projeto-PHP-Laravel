{{-- <!-- Coluna de Comentários --> --}}

    <h2>Comentários</h2>

    {{-- <!-- Comentários da Comissão (exibir apenas se o evento terminou) -->
    <!-- Substitua essa condição por lógica do seu framework -->
    <!-- Exemplo com Blade: @if ($eventoTerminado) --> --}}

    <h3>Comissão</h3>

    {{-- <!-- Se houver comentários da comissão -->
    <!-- Exemplo com Blade: @if (count($comentariosComissao)) --> --}}
    <table class="table table-bordered comentarios-table">
        <thead>
            <tr>
                <th>Comentário</th>
            </tr>
        </thead>
        <tbody>
            {{-- <!-- Loop de comentários -->
            <!-- Exemplo com Blade: @foreach ($comentariosComissao as $comentario) --> --}}
            <tr>
                <td>Comentário da comissão aqui</td>
            </tr>
            <tr>
                <td>Outro comentário da comissão aqui</td>
            </tr>
            {{-- <!-- @endforeach --> --}}
        </tbody>
    </table>
    {{-- <!-- @else --> --}}
    <p class="no-comments-message">Nenhum comentário da Comissão encontrado para este trabalho.</p>
    {{-- <!-- @endif --> --}}

    {{-- <!-- @else --> --}}
    <div class="alert alert-warning" role="alert">
        Os comentários da Comissão serão exibidos aqui após o término do evento.
    </div>
    {{-- <!-- @endif --> --}}


{{-- <!-- Comentários do CLPI --> --}}
<h3>CLPI</h3>

{{-- <!-- Se houver comentários do CLPI -->
<!-- Exemplo com Blade: @if (count($comentariosCLPI)) --> --}}
<table class="table table-bordered comentarios-table">
    <thead>
        <tr>
            <th>Comentário</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        {{-- <!-- Loop de comentários -->
        <!-- Exemplo com Blade: @foreach ($comentariosCLPI as $comentario) --> --}}
        <tr>
            <td>Comentário do CLPI exemplo</td>
            <td class="status-column">Aprovado</td>
        </tr>
        <tr>
            <td>Outro comentário do CLPI</td>
            <td class="status-column">Não Aprovado</td>
        </tr>
        {{-- <!-- @endforeach --> --}}
    </tbody>
</table>
{{-- <!-- @else --> --}}
<p class="no-comments-message">Nenhum comentário do CLPI encontrado para este trabalho.</p>
{{-- <!-- @endif --> --}}
