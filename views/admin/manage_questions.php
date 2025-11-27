<?php
    // app/views/admin/manage_questions.php
    
    // COMENTARIO NUEVO: Definimos variables para la plantilla de header
    $page_title = 'Gestión de preguntas hechas por usuarios';
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Preguntas de clientes</h1>
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


<div class="card shadow-sm border-success">
    <div class="card-header bg-success-subtle">
        <h2 class="h4 mb-0 text-success fw-bold">
            <i class="bi bi-patch-question-fill me-2"></i>
            Bandeja de preguntas
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Estado</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Pregunta</th>
                        <th scope="col">Respuesta</th>
                        <th scope="col">Respondido por</th>
                        <th scope="col">Fecha de pregunta</th>
                        <th scope="col">Respondida</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($questions) && !$error_db): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay preguntas de clientes.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($questions as $q): ?>
                        <tr>
                            <td>
                                <?php if ($q['estado'] == 'pendiente'): ?>
                                    <span class="badge bg-danger">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Respondida</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($q['cliente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars(substr($q['pregunta_texto'], 0, 50)) . '...'; ?></td>
                            
                            <td><?php echo htmlspecialchars(substr($q['respuesta_texto'] ?? '', 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($q['responder_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($q['fecha_pregunta']))); ?></td>
                            
                            <td>
                                <?php if ($q['fecha_respuesta']): ?>
                                    <?php echo htmlspecialchars(date('d/m/Y', strtotime($q['fecha_respuesta']))); ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($q['estado'] == 'pendiente'): ?>
                                    <button class="btn btn-primary btn-sm answer-btn" 
                                            title="Responder"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#answerQuestionModal"
                                            data-id="<?php echo $q['id_pregunta']; ?>"
                                            data-pregunta="<?php echo htmlspecialchars($q['pregunta_texto']); ?>">
                                        <i class="bi bi-chat-left-text-fill"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm edit-btn" 
                                            title="Editar respuesta"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editAnswerModal"
                                            data-id="<?php echo $q['id_pregunta']; ?>"
                                            data-pregunta="<?php echo htmlspecialchars($q['pregunta_texto']); ?>"
                                            data-respuesta="<?php echo htmlspecialchars($q['respuesta_texto']); ?>">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                <?php endif; ?>

                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_question&id=<?php echo $q['id_pregunta']; ?>" 
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

<div class="modal fade" id="answerQuestionModal" tabindex="-1" aria-labelledby="answerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="answerModalLabel"><i class="bi bi-chat-left-text-fill me-2"></i>Responder pregunta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=answer_question" method="POST">
                <input type="hidden" id="answer_id_pregunta" name="id_pregunta">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pregunta del cliente:</label>
                <p class="form-control-plaintext  p-3 rounded" id="answer_pregunta_texto"></p>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta_texto" class="form-label fw-bold">Tu respuesta:</label>
                        <textarea class="form-control" id="respuesta_texto" name="respuesta_texto" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAnswerModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-dark">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar respuesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_answer" method="POST">
                <input type="hidden" id="edit_id_pregunta" name="edit_id_pregunta">
                
                <div class="modal-body ">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pregunta del cliente:</label>
                        <p class="form-control-plaintext  p-3 rounded" id="edit_pregunta_texto"></p>
                    </div>
                    <div class="mb-3">
                        <label for="edit_respuesta_texto" class="form-label fw-bold">Tu respuesta:</label>
                        <textarea class="form-control" id="edit_respuesta_texto" name="edit_respuesta_texto" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info fw-bold text-dark">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php 
    // JS (Sin cambios)
    $page_custom_js = <<<JS
    <script>
        // JS para el modal de RESPONDER (sin cambios)
        document.querySelectorAll('.answer-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const pregunta = button.getAttribute('data-pregunta');

                document.getElementById('answer_id_pregunta').value = id;
                document.getElementById('answer_pregunta_texto').textContent = pregunta;
                document.getElementById('respuesta_texto').value = ''; 
            });
        });

        // JS para el nuevo modal de EDITAR (sin cambios)
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const pregunta = button.getAttribute('data-pregunta');
                const respuesta = button.getAttribute('data-respuesta');

                document.getElementById('edit_id_pregunta').value = id;
                document.getElementById('edit_pregunta_texto').textContent = pregunta;
                document.getElementById('edit_respuesta_texto').value = respuesta;
            });
        });
    </script>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>