<?php
    $page_title = 'Gestión de Avisos';
    $body_class = 'bg-warning-subtle'; // fondo de elote
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Gestión de avisos</h1>
    
    <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createNewsModal">
        <i class="bi bi-plus-circle me-2"></i>
        Publicar
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
        <h2 class="h4 mb-0 text-success fw-bold">
            <i class="bi bi-list-ul me-2"></i>
            Avisos publicados
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Título</th>
                        <th scope="col">Fecha de publicación</th>
                        <th scope="col">Autor</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news) && !$error_db): ?>
                        <tr>
                            <td colspan="4" class="text-center">No hay avisos publicados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($news as $notice): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($notice['titulo']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($notice['fecha_publicacion']))); ?></td>
                            <td><?php echo htmlspecialchars($notice['autor_nombre'] ?? 'N/A'); ?></td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editNewsModal"
                                        data-id="<?php echo $notice['id_aviso']; ?>"
                                        data-titulo="<?php echo htmlspecialchars($notice['titulo']); ?>"
                                        data-contenido="<?php echo htmlspecialchars($notice['contenido']); ?>"
                                        data-fecha="<?php echo htmlspecialchars(date('d/m/Y', strtotime($notice['fecha_publicacion']))); ?>">
                                        <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_news&id=<?php echo $notice['id_aviso']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar este aviso?');">
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

<div class="modal fade" id="createNewsModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-bell-fill me-2"></i>Publicar nuevo aviso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=create_news" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del aviso</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de publicación</label>
                        <p class="form-control-plaintext"><strong><?php echo date('d/m/Y'); ?></strong> </p>
                    </div>

                    <div class="mb-3">
                        <label for="contenido" class="form-label">Introduzca el contenido del aviso</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Publicar </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar aviso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_news" method="POST">
                <input type="hidden" id="edit_id_aviso" name="edit_id_aviso">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_titulo" class="form-label">Título del aviso</label>
                        <input type="text" class="form-control" id="edit_titulo" name="edit_titulo" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de publicación</label>
                        <p class="form-control-plaintext"><strong id="edit_fecha_publicacion_static"></strong> </p>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contenido" class="form-label">Contenido del aviso</label>
                        <textarea class="form-control" id="edit_contenido" name="edit_contenido" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php 
    // COMENTARIO NUEVO: JS personalizado para poblar el modal de edición
    $page_custom_js = <<<JS
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Extraemos todos los data-attributes del botón
                const id = button.getAttribute('data-id');
                const titulo = button.getAttribute('data-titulo');
                const contenido = button.getAttribute('data-contenido');
                const fecha = button.getAttribute('data-fecha');

                document.getElementById('edit_id_aviso').value = id;
                document.getElementById('edit_titulo').value = titulo;
                document.getElementById('edit_contenido').value = contenido;
                
                document.getElementById('edit_fecha_publicacion_static').textContent = fecha;
            });
        });
    </sCRIPT>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>