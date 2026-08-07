document.addEventListener('DOMContentLoaded', function() {
    // 1. Descrições Dinâmicas
    const descricoes = {
        "0": "Avaliador: Acesso limitado para avaliações.",
        "1": "Comissão: Gestão de relatórios e progresso.",
        "2": "CLPI: Coordenação de projetos.",
        "3": "Presidente: Controle total do sistema.",
        "4": "P&D: Gestão de dados de pesquisa.",
        "5": "Desenvolvedor: Acesso técnico completo.",
        "6": "CTI: Verificação de participantes."
    };

    document.querySelectorAll('.js-nivel-acesso').forEach(select => {
        select.addEventListener('change', function() {
            const container = this.closest('form').querySelector('.js-descricao-texto');
            if (container) {
                container.textContent = descricoes[this.value] || "Selecione um nível.";
            }
        });
    });

    // 2. Toggle Matrícula
    const checkExterno = document.getElementById("usuario_externo");
    const groupMatricula = document.getElementById("matricula-group");

    if (checkExterno) {
        checkExterno.addEventListener("change", function() {
            groupMatricula.style.display = this.checked ? "none" : "block";
        });
    }
});