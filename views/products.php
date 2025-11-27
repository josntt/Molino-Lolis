<?php 
    include_once "app/views/templates/header.php";
?>

<div class="product-page-container">
    
    <div class="product-header">
        <h1>Nuestros productos</h1>
        <p>Calidad y frescura en cada producto.</p>
    </div>

    <!-- 🔎 BUSCADOR -->
    <div style="max-width: 450px; margin: 0 auto 30px;">
        <input 
            type="text" 
            id="productSearchInput" 
            placeholder="Buscar producto..." 
            style="
                width: 100%;
                padding: 12px 18px;
                border-radius: 25px;
                border: 2px solid #0F8A4B;
                font-size: 1rem;
                outline: none;
                transition: .2s;
            "
        >
    </div>

    <!-- Mensaje de “sin resultados” -->
    <p id="productSearchEmpty" class="product-empty-msg" style="display:none; text-align:center; font-size:1.1rem; color:#555;">
        No se encontraron productos que coincidan con tu búsqueda.
    </p>

    <!-- GRID -->
    <div class="product-grid" id="productGrid">
        
    <?php if (empty($products)): ?>
        <p class="product-empty-msg">No hay productos disponibles en este momento.</p>
    
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            
            <article 
                class="product-card"
                data-name="<?php echo htmlspecialchars($product['nombre']); ?>"
                data-desc="<?php echo htmlspecialchars($product['descripcion']); ?>"
            >
                
                <div class="product-image-container">
                    <img 
                        src="<?php echo BASE_URL . htmlspecialchars($product['imagen'] ?: 'public/media/placeholder.png'); ?>" 
                        alt="<?php echo htmlspecialchars($product['nombre']); ?>"
                    >
                </div>
                
                <div class="product-card-content">
                    <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($product['descripcion']); ?></p>
                </div>

            </article>

        <?php endforeach; ?>
    <?php endif; ?>

    </div> <!-- cierre product-grid -->

</div> <!-- cierre product-page-container -->

<!-- 🔗 JS EXTERNO DEL BUSCADOR -->
<script src="<?php echo BASE_URL; ?>public/js/product-search.js"></script>

<?php 
    include_once "app/views/templates/footer.php";
?>
