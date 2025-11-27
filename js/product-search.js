document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("productSearchInput");
    const grid = document.getElementById("productGrid");
    const emptyMsg = document.getElementById("productSearchEmpty");

    if (!input || !grid) return;

    const cards = Array.from(grid.querySelectorAll(".product-card"));

    // contenedor para sugerencias (debajo del input)
    const wrapper = input.parentElement;
    wrapper.style.position = "relative";

    const suggestionBox = document.createElement("ul");
    suggestionBox.id = "productSearchSuggestions";
    wrapper.appendChild(suggestionBox);

    // función para normalizar texto (para que A / Á sea lo mismo)
    const normalize = (str) =>
        str
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

    function aplicarFiltroYAutocomplete() {
        const query = normalize(input.value.trim());
        let visibles = 0;

        // 🔍 FILTRO DE TARJETAS (solo por NOMBRE, no por descripción)
        cards.forEach((card) => {
            const name = normalize(card.dataset.name || "");
            const match = query === "" || name.includes(query);

            card.style.display = match ? "" : "none";
            if (match) visibles++;
        });

        // mensaje cuando no hay resultados
        if (emptyMsg) {
            emptyMsg.style.display =
                query !== "" && visibles === 0 ? "" : "none";
        }

        // 🔽 SUGERENCIAS TIPO GOOGLE (solo por nombre, empieza con lo escrito)
        suggestionBox.innerHTML = "";

        if (query === "") {
            suggestionBox.style.display = "none";
            return;
        }

        // nombres únicos
        const uniqueNames = [
            ...new Set(cards.map((card) => card.dataset.name || "")),
        ];

        const matches = uniqueNames
            .filter((name) => normalize(name).startsWith(query))
            .slice(0, 6); // máx 6 sugerencias

        if (matches.length === 0) {
            suggestionBox.style.display = "none";
            return;
        }

        matches.forEach((name) => {
            const li = document.createElement("li");
            li.textContent = name;

            // usar mousedown para que no se pierda el focus del input antes del click
            li.addEventListener("mousedown", (e) => {
                e.preventDefault();

                // rellenar input con el nombre exacto
                input.value = name;

                // volver a aplicar filtro completo
                aplicarFiltroYAutocomplete();

                // ocultar caja
                suggestionBox.style.display = "none";

                // hacer scroll suave al producto
                const target = cards.find(
                    (card) => card.dataset.name === name
                );
                if (target) {
                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            });

            suggestionBox.appendChild(li);
        });

        suggestionBox.style.display = "block";
    }

    input.addEventListener("input", aplicarFiltroYAutocomplete);
    input.addEventListener("focus", () => {
        if (input.value.trim() !== "") aplicarFiltroYAutocomplete();
    });

    // cerrar sugerencias si hace click fuera
    document.addEventListener("click", (e) => {
        if (e.target !== input && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
        }
    });

    // estado inicial
    aplicarFiltroYAutocomplete();
});
