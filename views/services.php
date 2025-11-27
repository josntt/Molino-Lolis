<?php 
    include_once "app/views/templates/header.php";
?>

<div class="services-page">

    <div class="services-header">
        <h1>Estamos a su servicio</h1>
        <p>Ofreciendo distintas facilidades y servicios para nuestros clientes</p>

        <div class="services-tagline">
            <span>Envíos y pedidos especiales</span>
            <span>Atención cercana</span>
            <span>Servicios pensados para ti</span>
        </div>
    </div>
    
    <div class="search-container">
        <input type="search" id="serviceSearchInput" class="search-input" placeholder="Buscar servicio por nombre, tipo o día...">
    </div>

    <div class="services-grid">
        
        <?php if (isset($error_db)): ?>
            <p class="services-error">
                <?php echo htmlspecialchars($error_db); ?>
            </p>
        
        <?php elseif (empty($services)): ?>
            <p class="services-empty">
                No hay servicios disponibles en este momento.
            </p>
            
        <?php else: ?>
            <?php foreach ($services as $service): ?>
                <article class="service-card">
                    
                    <div class="service-card-header">
                        <div class="service-title-group">
                            <h3>
                                <?php echo htmlspecialchars($service['nombre_servicio']); ?>
                            </h3>
                            <span class="service-type">
                                <?php echo htmlspecialchars($service['tipo']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="service-card-body">
                        <p class="service-description">
                            <?php echo nl2br(htmlspecialchars($service['descripcion'])); ?>
                        </p>

                        <div class="service-meta">
                            <p>
                                <strong>Días:</strong>
                                <span>
                                    <?php echo htmlspecialchars($service['dias_disponibles'] ?? 'No especificado'); ?>
                                </span>
                            </p>
                            <p>
                                <strong>Horario:</strong>
                                <span>
                                    <?php if (!empty($service['horario_inicio']) && !empty($service['horario_fin'])): ?>
                                        <?php echo htmlspecialchars(date('g:i A', strtotime($service['horario_inicio']))); ?> –
                                        <?php echo htmlspecialchars(date('g:i A', strtotime($service['horario_fin']))); ?>
                                    <?php else: ?>
                                        No especificado
                                    <?php endif; ?>
                                </span>
                            </p>
                        </div>
                    </div>

                </article>
            <?php endforeach; ?>
            
        <?php endif; ?>

    </div>

    <!-- Mensaje dinámico para búsqueda sin resultados -->
    <div class="services-empty" id="noResultsMessage" style="display: none; margin-top: 25px;">
        No se encontraron servicios que coincidan con tu búsqueda.
    </div>
    
</div>

<script src="public/js/search-services.js"></script>

<?php 
    include_once "app/views/templates/footer.php";
?>