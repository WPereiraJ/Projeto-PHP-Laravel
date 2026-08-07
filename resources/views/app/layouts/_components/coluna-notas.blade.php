<div class="row">
    {{-- Coluna de Notas dos Avaliadores --}}

    {{-- Se o evento terminou, mostrar a tabela -->
    Substitua este bloco com lógica Blade como: @if ($eventoTerminado) --}}
    <div class="col-md-4">
        <h2>Notas dos Avaliadores</h2>
        <table class="table table-bordered table-notas-avaliadores">
            <thead>
                <tr>
                    <th>Avaliador</th>
                    <th>Resumo</th>
                    <th>Importância</th>
                    <th>Apresentação</th>
                    <th>Desempenho</th>
                </tr>
            </thead>
            <tbody>
                {{-- Exemplo de linha estática. Substitua por loop Blade: @foreach ($notas as $nota) --}}
                <tr>
                    <td>João da Silva</td>
                    <td>8.5</td>
                    <td>9.0</td>
                    <td>7.5</td>
                    <td>8.0</td>
                </tr>
                <tr>
                    <td>Maria Oliveira</td>
                    <td>9.0</td>
                    <td>8.0</td>
                    <td>8.5</td>
                    <td>9.0</td>
                </tr>
                {{--  @endforeach --}}
            </tbody>
        </table>
    </div>

    {{-- Caso o evento não tenha terminado, exibe aviso -->
     Substitua este bloco com: @else -->
    
    <div class="col-md-4">
        <div class="alert alert-warning" role="alert">
            As notas dos avaliadores serão exibidas aqui após o término do evento.
        </div>
    </div>
    
     @endif --}}
</div>
