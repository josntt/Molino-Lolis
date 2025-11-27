// public/js/admin_darkmode.js

document.addEventListener('DOMContentLoaded', () => {
    
    //  Selecciona el botón y el ícono
    const toggleBtn = document.getElementById('theme-toggle-btn');
    //  Asegurarse de que el botón exista antes de agregarle un listener
    if (!toggleBtn) {
        // Si el botón no existe (ej. en el login), no intentes agregar el listener. PERO, sí queremos que se ejecute la animación del dashboard más abajo.
        console.log("Botón de tema no encontrado en esta página.");
    } else {
        const toggleIcon = toggleBtn.querySelector('i');
        const htmlEl = document.documentElement;

        //: Función para actualizar el ícono (luna o sol)
        function updateIcon(theme) {
            if (!toggleIcon) return;
            if (theme === 'dark') {
                toggleIcon.classList.remove('bi-moon-fill');
                toggleIcon.classList.add('bi-sun-fill');
            } else {
                toggleIcon.classList.remove('bi-sun-fill');
                toggleIcon.classList.add('bi-moon-fill');
            }
        }

        // CListener para el clic del botón
        toggleBtn.addEventListener('click', () => {
            //  Revisa cuál es el tema actual
            const currentTheme = htmlEl.getAttribute('data-bs-theme');
            
            // Decide cuál será el nuevo tema
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Aplica el nuevo tema al HTML
            htmlEl.setAttribute('data-bs-theme', newTheme);
            
            // Guarda la preferencia en el navegador
            localStorage.setItem('theme', newTheme);
            
            // Actualiza el ícono
            updateIcon(newTheme);
        });
    } 


    // ---Animación para las tarjetas del dashboard
    // Este bloque se ejecuta independientemente del botón de tema y Selecciona todas las tarjetas de gestión
    const adminCards = document.querySelectorAll('.admin-card');
    
    if (adminCards.length > 0) {
        adminCards.forEach((card, index) => {
            // Aplica la animación con un retraso escalonado para que aparezcan una tras otra
            setTimeout(() => {
                card.classList.add('is-visible');
            }, 100 * index); // 100ms de retraso entre cada tarjeta
        });
    }

}); 