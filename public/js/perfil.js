/**
 * Alternar visibilidade de senhas na tela de Perfil
 */
document.addEventListener('DOMContentLoaded', function () {
    // Seleciona todos os botões com a classe 'btn-ver-senha'
    const botoesVerSenha = document.querySelectorAll('.btn-ver-senha');

    botoesVerSenha.forEach(function (botao) {
        botao.addEventListener('click', function () {
            // Encontra o input vizinho dentro do mesmo grupo
            const inputGroup = this.closest('.input-group');
            const input = inputGroup.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === "password") {
                input.type = "text"; // Mostra a senha
                icon.classList.remove('fa-lock');
                icon.classList.add('fa-unlock'); // Ícone de cadeado aberto
            } else {
                input.type = "password"; // Oculta a senha
                icon.classList.remove('fa-unlock');
                icon.classList.add('fa-lock'); // Ícone de cadeado fechado
            }
        });
    });
});