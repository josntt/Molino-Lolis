<?php
    // El PageController nos pasa $products, $error_db,
    // $usuario_nombre, $usuario_rol, y los mensajes de éxito/error
    //Definimos las variables para la plantilla de header
    $page_title = 'Gestión de Productos';
    $body_class = 'bg-warning-subtle'; // Clase de body personalizada para esta página
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Productos del molino</h1>
    
    <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
        <i class="bi bi-plus-circle me-2"></i>
        Añadir
    </button>
</div>

<?php if(isset($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if(isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if(isset($error_db) && $error_db) echo "<div class='alert alert-danger'>$error_db</div>"; ?>


<div class="card shadow-sm border-warning">
    <div class="card-header bg-warning">
        <h2 class="h4 mb-0 text-success fw-bold">Lista de productos</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Imagen</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products) && !$error_db): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay productos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $prod): ?>
                        <tr>
                            <td>
                                <img src="<?php echo BASE_URL . htmlspecialchars($prod['imagen'] ?: 'public/media/placeholder.png'); ?>" alt="img" width="60" height="60" class="rounded object-fit-cover">
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($prod['nombre']); ?></td>
                            <td><?php echo htmlspecialchars(substr($prod['descripcion'], 0, 100)) . '...'; ?></td>
                            <td>
                                <?php if ($prod['estado'] == 'activo'): ?>
                                    <span class="badge bg-success bg-opacity-75">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-75">Inactivo</span>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=toggle_product_status&id=<?php echo $prod['id_producto']; ?>" 
                                class="btn btn-sm <?php echo ($prod['estado'] == 'activo') ? 'btn-success' : 'btn-secondary'; ?>" 
                                title="<?php echo ($prod['estado'] == 'activo') ? 'Desactivar' : 'Activar'; ?>">
                                    <i class="bi <?php echo ($prod['estado'] == 'activo') ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?>"></i>
                                </a>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProductModal"
                                        data-id="<?php echo $prod['id_producto']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($prod['descripcion']); ?>"
                                        data-estado="<?php echo $prod['estado']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_product&id=<?php echo $prod['id_producto']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-plus-circle me-2"></i>Añadir un producto nuevo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=create_product" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_nombre" class="form-label">Nombre del producto</label>
                        <input type="text" class="form-control" id="create_nombre" name="create_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="create_descripcion" name="create_descripcion" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="create_imagen" class="form-label">Imagen del producto (Opcional)</label>
                        <input class="form-control" type="file" id="create_imagen" name="create_imagen" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_product" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="edit_id_producto" name="edit_id_producto">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre del producto</label>
                        <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="edit_descripcion" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="edit_estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagen" class="form-label">Cambiar imagen (Opcional)</label>
                        <input class="form-control" type="file" id="edit_imagen" name="edit_imagen" accept="image/*">
                        <small class="form-text text-muted">Dejar en blanco para conservar la imagen actual.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Actualizar </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php 
    //  JS personalizado para esta página usando HEREDOC
    $page_custom_js = <<<JS
    <script>
        // Script para poblar el modal de edición
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                const descripcion = button.getAttribute('data-descripcion');
                const estado = button.getAttribute('data-estado');

                document.getElementById('edit_id_producto').value = id;
                document.getElementById('edit_nombre').value = nombre;
                document.getElementById('edit_descripcion').value = descripcion;
                document.getElementById('edit_estado').value = estado;
            });
        });
    </script>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>