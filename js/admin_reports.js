// public/js/admin_reports.js

// COMENTARIO NUEVO: Este script es para la interactividad de la página de reportes.
// Por ahora, solo añade un efecto de entrada.

document.addEventListener('DOMContentLoaded', () => {
    
    // COMENTARIO NUEVO: Selecciona todas las tarjetas de reporte
    const reportCards = document.querySelectorAll('.report-card');
    
    // COMENTARIO NUEVO: Aplica la animación de entrada a cada tarjeta con un pequeño retraso
    reportCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 100}ms`;
        card.classList.add('fade-in'); // COMENTARIO NUEVO: (Usaremos 'fade-in' del CSS)
    });

    console.log("Módulo de reportes cargado.");

    // COMENTARIO NUEVO: Aquí es donde podrías añadir lógica de AJAX en el futuro
    // para recargar los reportes sin recargar toda la página.
});