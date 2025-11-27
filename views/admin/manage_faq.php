<?php
    // app/views/admin/manage_faq.php
    
    // COMENTARIO NUEVO: Definimos variables para la plantilla de header
    $page_title = 'Gestión de FAQ';
    $body_class = 'bg-warning-subtle'; // COMENTARIO NUEVO: Fondo 'elote'
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Gestión de (FAQ)Preguntas frecuentes</h1>
    
    <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createFaqModal">
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
        <h2 class="h4 mb-0 text-success fw-bold">
            <i class="bi bi-list-ul me-2"></i>
            Preguntas agregadas
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Pregunta</th>
                        <th scope="col">Respuesta</th>
                        <th scope="col">Visibilidad</th>
                        <th scope="col">Autor</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($faqs) && !$error_db): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay preguntas registradas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($faq['pregunta']); ?></td>
                            <td><?php echo htmlspecialchars(substr($faq['respuesta'], 0, 80)) . '...'; ?></td>
                            <td>
                                <?php if ($faq['visible'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-75">Visible</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-75">Oculto</span>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=toggle_faq_visibility&id=<?php echo $faq['id_faq']; ?>" 
                                class="btn btn-sm <?php echo ($faq['visible'] == 1) ? 'btn-success' : 'btn-secondary'; ?>" 
                                title="<?php echo ($faq['visible'] == 1) ? 'Ocultar' : 'Mostrar'; ?>">
                                    <i class="bi <?php echo ($faq['visible'] == 1) ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?>"></i>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($faq['autor_nombre'] ?? 'N/A'); ?></td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editFaqModal"
                                        data-id="<?php echo $faq['id_faq']; ?>"
                                        data-pregunta="<?php echo htmlspecialchars($faq['pregunta']); ?>"
                                        data-respuesta="<?php echo htmlspecialchars($faq['respuesta']); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_faq&id=<?php echo $faq['id_faq']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar esta pregunta?');">
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

<div class="modal fade" id="createFaqModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-question-circle-fill me-2"></i>Añadir nueva pregunta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=create_faq" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pregunta" class="form-label">Pregunta</label>
                        <input type="text" class="form-control" id="pregunta" name="pregunta" required>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta" class="form-label">Respuesta</label>
                        <textarea class="form-control" id="respuesta" name="respuesta" rows="5" required></textarea>
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

<div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar pregunta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_faq" method="POST">
                <input type="hidden" id="edit_id_faq" name="edit_id_faq">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_pregunta" class="form-label">Pregunta</label>
                        <input type="text" class="form-control" id="edit_pregunta" name="edit_pregunta" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_respuesta" class="form-label">Respuesta</label>
                        <textarea class="form-control" id="edit_respuesta" name="edit_respuesta" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Actualizar </button>
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
                // COMENTARIO NUEVO: Extraemos todos los data-attributes del botón
                const id = button.getAttribute('data-id');
                const pregunta = button.getAttribute('data-pregunta');
                const respuesta = button.getAttribute('data-respuesta');

                // COMENTARIO NUEVO: Poblamos el formulario del modal de edición
                document.getElementById('edit_id_faq').value = id;
                document.getElementById('edit_pregunta').value = pregunta;
                document.getElementById('edit_respuesta').value = respuesta;
            });
        });
    </sCRIPT>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>