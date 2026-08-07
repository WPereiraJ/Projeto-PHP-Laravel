<h4 class="section-title text-warning">Comentários e Pareceres</h4>

<div class="alert alert-info">
    <i class="fas fa-info-circle mr-2"></i> Aqui é onde você verá os comentários da sua pesquisa.
</div>

{{-- Lista de Comentários --}}
<div class="comentarios-lista mt-4">
    
    @forelse($comentarios ?? [] as $comentario)
        <div class="comment-box mb-3 p-3 border rounded shadow-sm bg-white">
            <div class="d-flex justify-content-between mb-2">
                <strong class="text-dark"><i class="fas fa-user-tie mr-2 text-primary"></i>{{ $comentario->avaliador_nome ?? 'Avaliador' }}</strong>
                {{-- Data removida pois a tabela não armazena o momento do comentário --}}
            </div>
            <p class="mb-0 text-secondary">
                {{ $comentario->texto }}
            </p>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="far fa-comments fa-3x mb-3"></i>
            <p>Nenhum comentário registrado até o momento.</p>
        </div>
    @endforelse

</div>