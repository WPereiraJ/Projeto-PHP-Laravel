// Função para alternar visibilidade da senha - Avaliadores
const togglePassword1 = document.querySelector('#togglePassword1');
const password1 = document.querySelector('#senha');

if (togglePassword1 && password1) {
    togglePassword1.addEventListener('mousedown', function () {
        password1.type = 'text';
    });

    togglePassword1.addEventListener('mouseup', function () {
        password1.type = 'password';
    });

    togglePassword1.addEventListener('mouseout', function () {
        password1.type = 'password';
    });
}

// Função para alternar visibilidade da senha - Comissão
const togglePassword2 = document.querySelector('#togglePassword2');
const password2 = document.querySelector('#pass');

if (togglePassword2 && password2) {
    togglePassword2.addEventListener('mousedown', function () {
        password2.type = 'text';
    });

    togglePassword2.addEventListener('mouseup', function () {
        password2.type = 'password';
    });

    togglePassword2.addEventListener('mouseout', function () {
        password2.type = 'password';
    });
}
