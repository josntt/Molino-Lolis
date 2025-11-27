/* ==============================================
   FILTRO DE BÚSQUEDA PARA SERVICIOS (FINAL V2 - Con "No Resultados")
   ============================================== */
document.addEventListener('DOMContentLoaded', () => {

    // 1. Apunta a los elementos
    const searchInput = document.getElementById('serviceSearchInput');
    const serviceGrid = document.querySelector('.services-grid');
    
    // ✅ 2. Apunta al nuevo mensaje de "no resultados"
    const noResultsMessage = document.getElementById('noResultsMessage'); 
    
    // 3. Si no encuentra los elementos en esta página, no hace nada.
    //    (Añadimos la comprobación del 'noResultsMessage')
    if (!searchInput || !serviceGrid || !noResultsMessage) {
        return; 
    }

    // 4. Busca todas las tarjetas (solo si el grid existe)
    const allCards = serviceGrid.querySelectorAll('.service-card');

    // 5. Crea la función de filtrado
    const filterServices = () => {
        const query = searchInput.value.toLowerCase().trim();
        
        // ✅ 6. Añade un contador de tarjetas visibles
        let visibleCards = 0;

        // 7. Recorre cada tarjeta
        allCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();

            // 8. Compara y decide si mostrar u ocultar
            if (cardText.includes(query)) {
                card.style.display = ''; // Muestra la tarjeta
                visibleCards++; // ✅ 9. Incrementa el contador
            } else {
                card.style.display = 'none'; // Oculta la tarjeta
            }
        });

        // ✅ 10. Muestra o oculta el mensaje de "no resultados"
        if (visibleCards === 0) {
            noResultsMessage.style.display = 'block'; // Muestra el mensaje
        } else {
            noResultsMessage.style.display = 'none'; // Oculta el mensaje
        }
    };

    // 11. Activa el filtro cada vez que el usuario escribe
    searchInput.addEventListener('keyup', filterServices);
});