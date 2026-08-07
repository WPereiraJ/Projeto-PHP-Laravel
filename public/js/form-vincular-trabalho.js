/**
 * Filtra a lista de usuários baseada no Cargo selecionado
 */
function filterUsuariosByCargo() {
    var cargoId = document.getElementById('cargo-select').value;
    var usuarios = document.querySelectorAll('.usuario-item');

    usuarios.forEach(function(usuario) {
        // Se não tiver filtro (vazio) ou se o ID bater, mostra.
        if (cargoId === "" || usuario.getAttribute('data-cargo-id') == cargoId) {
            usuario.style.display = 'block';
        } else {
            usuario.style.display = 'none';
        }
    });
}

/**
 * Filtra a lista de trabalhos baseada na Categoria selecionada
 */
function filterTrabalhosByCategoria() {
    const selectedCategory = document.getElementById("categoria-select").value;
    const trabalhos = document.querySelectorAll(".trabalho-item");

    trabalhos.forEach((trabalho) => {
        if (selectedCategory === "" || trabalho.dataset.categoria === selectedCategory) {
            trabalho.style.display = "block";
        } else {
            trabalho.style.display = "none";
        }
    });
    
    // Reseta o checkbox de "Selecionar Todos" para evitar confusão visual
    const selectAll = document.getElementById("selectAllTrabalhos");
    if(selectAll) selectAll.checked = false;
}

/**
 * Seleciona todos os trabalhos que estão VISÍVEIS na tela
 */
function toggleSelectFilteredTrabalhos() {
    const selectAllCheckbox = document.getElementById("selectAllTrabalhos");
    const selectedCategory = document.getElementById("categoria-select").value;
    const trabalhos = document.querySelectorAll(".trabalho-item");

    trabalhos.forEach((trabalho) => {
        const checkbox = trabalho.querySelector(".trabalho-checkbox");
        
        // Verifica se o trabalho pertence à categoria (filtro) E se está visível (display != none)
        const isVisible = (selectedCategory === "" || trabalho.dataset.categoria === selectedCategory) && 
                          trabalho.style.display !== "none";

        if (isVisible) {
            checkbox.checked = selectAllCheckbox.checked;
        }
    });
}

/**
 * Mostra ou esconde os campos de data/hora dependendo se é Avaliador
 */
function togglePresentationFields() {
    var checkbox = document.getElementById("avaliador");
    var fields = document.getElementById("presentation-fields");
    var inputs = fields.querySelectorAll('input');

    if (checkbox.checked) {
        fields.style.display = "block";
        // Opcional: Torna os campos obrigatórios se for avaliador
        inputs.forEach(input => input.required = true);
    } else {
        fields.style.display = "none";
        inputs.forEach(input => input.required = false);
    }
}

// Garante que o estado inicial esteja correto ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    // Se o checkbox já vier marcado (edição), mostra os campos
    if(document.getElementById("avaliador")) {
        togglePresentationFields(); 
    }
});