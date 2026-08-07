document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (!sidebarToggle || !sidebar) return;

    // Abrir/fechar sidebar ao clicar no botão
    sidebarToggle.addEventListener('click', function (event) {
        event.stopPropagation(); // Impede o fechamento imediato
        sidebar.classList.toggle('open');
    });

    // Fechar sidebar ao clicar fora dela
    document.addEventListener('click', function (event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = sidebarToggle.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
        }
    });
});

console.log('isso está rodando')