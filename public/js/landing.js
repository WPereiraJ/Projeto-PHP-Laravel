/* public/js/landing.js */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Efeito Navbar Transparente -> Branca ---
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // --- Rolagem Suave para Links Internos ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Compensa a altura da navbar
                    behavior: 'smooth'
                });
            }
        });
    });
});