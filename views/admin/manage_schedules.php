<?php
    $page_title = 'Gestión de Horarios';
    $body_class = 'bg-warning-subtle'; 
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Horarios programados</h1>
    
    <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
        <i class="bi bi-calendar-plus me-2"></i>
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
            Lista de horarios
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Tipo Molida</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Creado por</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($schedules) && !$error_db): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay horarios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($schedules as $sched): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($sched['product_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($sched['fecha']))); ?></td>
                            <td><?php echo htmlspecialchars(date('g:i A', strtotime($sched['hora_inicio']))); ?></td>
                            <td><?php echo htmlspecialchars(date('g:i A', strtotime($sched['hora_fin']))); ?></td>
                            <td><?php echo htmlspecialchars($sched['tipo_molida']); ?></td>
                            <td><?php echo htmlspecialchars(substr($sched['observaciones'] ?? '', 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($sched['creator_name'] ?? 'N/A'); ?></td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editScheduleModal"
                                        data-id="<?php echo $sched['id_horario']; ?>"
                                        data-id-producto="<?php echo $sched['id_producto']; ?>"
                                        data-fecha="<?php echo $sched['fecha']; ?>"
                                        data-hora-inicio="<?php echo $sched['hora_inicio']; ?>"
                                        data-hora-fin="<?php echo $sched['hora_fin']; ?>"
                                        data-tipo-molida="<?php echo htmlspecialchars($sched['tipo_molida']); ?>"
                                        data-observaciones="<?php echo htmlspecialchars($sched['observaciones'] ?? ''); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_schedule&id=<?php echo $sched['id_horario']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar este horario?');">
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

<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-calendar-plus me-2"></i>Añadir horario nuevo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=create_schedule" method="POST" id="createScheduleForm">
                <div class="modal-body">
                
                    <div id="createScheduleError" class="alert alert-danger d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="id_producto" class="form-label">Producto</label>
                            <select class="form-select" id="id_producto" name="id_producto" required>
                                <option value="" selected disabled>-- Selecciona un producto --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['id_producto']; ?>">
                                        <?php echo htmlspecialchars($product['nombre']); ?> (<?php echo htmlspecialchars($product['estado']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="col-md-4">
                            <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        <div class="col-md-4">
                            <label for="hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                        </div>
                        <div class="col-md-12">
                            <label for="tipo_molida" class="form-label">Tipo de Molida (Ej. Nixtamal, Seco)</label>
                            <input type="text" class="form-control" id="tipo_molida" name="tipo_molida" required>
                        </div>
                        <div class="col-md-12">
                            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar horario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_schedule" method="POST" id="editScheduleForm">
                <input type="hidden" id="edit_id_horario" name="edit_id_horario">
                
                <div class="modal-body">

                    <div id="editScheduleError" class="alert alert-danger d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="edit_id_producto" class="form-label">Producto</label>
                            <select class="form-select" id="edit_id_producto" name="edit_id_producto" required>
                                <option value="" disabled>-- Selecciona un producto --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['id_producto']; ?>">
                                        <?php echo htmlspecialchars($product['nombre']); ?> (<?php echo htmlspecialchars($product['estado']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="edit_fecha" name="edit_fecha" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="edit_hora_inicio" name="edit_hora_inicio" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" id="edit_hora_fin" name="edit_hora_fin" required>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_tipo_molida" class="form-label">Tipo de Molida</label>
                            <input type="text" class="form-control" id="edit_tipo_molida" name="edit_tipo_molida" required>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea class="form-control" id="edit_observaciones" name="edit_observaciones" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Actualizar</button>
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
                const idProducto = button.getAttribute('data-id-producto');
                const fecha = button.getAttribute('data-fecha');
                const horaInicio = button.getAttribute('data-hora-inicio');
                const horaFin = button.getAttribute('data-hora-fin');
                const tipoMolida = button.getAttribute('data-tipo-molida');
                const observaciones = button.getAttribute('data-observaciones');

                document.getElementById('edit_id_horario').value = id;
                document.getElementById('edit_id_producto').value = idProducto;
                document.getElementById('edit_fecha').value = fecha;
                document.getElementById('edit_hora_inicio').value = horaInicio;
                document.getElementById('edit_hora_fin').value = horaFin;
                document.getElementById('edit_tipo_molida').value = tipoMolida;
                document.getElementById('edit_observaciones').value = observaciones;
            });
        });

        // -----------------------------------------------------------------
        // LÓGICA DE VALIDACIÓN DE HORARIOS
        // -----------------------------------------------------------------

        // Espera a que el DOM esté cargado
        document.addEventListener('DOMContentLoaded', () => {

            // Selecciona los formularios y divs de error
            const createForm = document.getElementById('createScheduleForm');
            const editForm = document.getElementById('editScheduleForm');
            const createErrorDiv = document.getElementById('createScheduleError');
            const editErrorDiv = document.getElementById('editScheduleError');

            // Función para mostrar un error
            function showError(errorDiv, message) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('d-none'); // COMENTARIO NUEVO: Muestra la alerta
            }

            // Función para limpiar el error
            function clearError(errorDiv) {
                if (!errorDiv.classList.contains('d-none')) {
                    errorDiv.textContent = '';
                    errorDiv.classList.add('d-none'); // COMENTARIO NUEVO: Oculta la alerta
                }
            }

            // FUNCION PARA validación principal
            function validateSchedule(formType) {
                let fechaInput, inicioInput, finInput, errorDiv;

                // Asigna los elementos correctos (Crear o editar)
                if (formType === 'create') {
                    fechaInput = document.getElementById('fecha');
                    inicioInput = document.getElementById('hora_inicio');
                    finInput = document.getElementById('hora_fin');
                    errorDiv = createErrorDiv;
                } else {
                    fechaInput = document.getElementById('edit_fecha');
                    inicioInput = document.getElementById('edit_hora_inicio');
                    finInput = document.getElementById('edit_hora_fin');
                    errorDiv = editErrorDiv;
                }

                // Limpia errores anteriores
                clearError(errorDiv);

                // Obtiene valores de los campos
                const selectedDate = fechaInput.value;
                const selectedStartTime = inicioInput.value;
                const selectedEndTime = finInput.value;

                // Obtiene la fecha y hora actual
                const now = new Date();
                const today = now.toISOString().split('T')[0];
                const currentTime = now.toTimeString().split(' ')[0].substring(0, 5);

                // 1. Validación: No se puede crear en una fecha pasada
                if (selectedDate < today) {
                    showError(errorDiv, 'Error: No se puede programar un horario en una fecha pasada.');
                    return false; // COMENTARIO NUEVO: Detiene el envío
                }

                // 2. Validación: Si es hoy, no se puede crear en una hora pasada
                if (selectedDate === today && selectedStartTime < currentTime) {
                    showError(errorDiv, 'Error: No se puede programar un horario en una hora que ya pasó.');
                    return false; // Detiene el envío
                }

                // La hora de fin debe ser mayor a la hora de inicio
                if (selectedEndTime <= selectedStartTime) {
                    showError(errorDiv, 'Error: La hora de fin debe ser mayor que la hora de inicio.');
                    return false; // Detiene el envío
                }

                return true; // Si todo está bien permite el envío
            }

            // Añade el listener al formulario de CREAR
            if (createForm) {
                createForm.addEventListener('submit', (e) => {
                    if (!validateSchedule('create')) {
                        e.preventDefault(); // Detiene el envío si la validación falla
                    }
                });

                // Limpia el error si el usuario corrige
                document.querySelectorAll('#createScheduleModal input').forEach(input => {
                    input.addEventListener('input', () => clearError(createErrorDiv));
                });
            }

            // COMENTARIO NUEVO: Añade el listener al formulario de EDITAR
            if (editForm) {
                editForm.addEventListener('submit', (e) => {
                    if (!validateSchedule('edit')) {
                        e.preventDefault(); // Detiene el envío si la validación falla
                    }
                });

                // Limpia el error si el usuario corrige
                document.querySelectorAll('#editScheduleModal input').forEach(input => {
                    input.addEventListener('input', () => clearError(editErrorDiv));
                });
            }
        });
    </script>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>