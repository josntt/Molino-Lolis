<?php
    $page_title = 'Gestión de Servicios';
    $body_class = 'bg-warning-subtle'; 
    include_once "app/views/admin/templates/admin_header.php";
    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Gestión de servicios</h1>
    
    <button class="btn btn-warning btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createServiceModal">
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
            Lista de servicios
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Nombre del servicio</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Días</th>
                        <th scope="col">Horario</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($services) && !$error_db): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay servicios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($service['tipo']); ?></span></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($service['nombre_servicio']); ?></td>
                            <td><?php echo htmlspecialchars(substr($service['descripcion'], 0, 70)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars(str_replace(',', ', ', $service['dias_disponibles'] ?? 'N/A')); ?></td>
                            <td>
                                <?php if (!empty($service['horario_inicio']) && !empty($service['horario_fin'])): ?>
                                    <?php echo htmlspecialchars(date('g:i A', strtotime($service['horario_inicio']))); ?> - 
                                    <?php echo htmlspecialchars(date('g:i A', strtotime($service['horario_fin']))); ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editServiceModal"
                                        data-id="<?php echo $service['id_servicio']; ?>"
                                        data-tipo="<?php echo htmlspecialchars($service['tipo']); ?>"
                                        data-nombre="<?php echo htmlspecialchars($service['nombre_servicio']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($service['descripcion']); ?>"
                                        data-horario-inicio="<?php echo htmlspecialchars($service['horario_inicio'] ?? ''); ?>"
                                        data-horario-fin="<?php echo htmlspecialchars($service['horario_fin'] ?? ''); ?>"
                                        data-dias="<?php echo htmlspecialchars($service['dias_disponibles'] ?? ''); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_service&id=<?php echo $service['id_servicio']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que desea eliminar este servicio?');">
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

<div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-plus-circle me-2"></i>Añadir servicio nuevo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=create_service" method="POST" id="createServiceForm">
                <div class="modal-body">
                    
                    <div id="createServiceError" class="alert alert-danger d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo de servicio</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" selected disabled>-- Selecciona un tipo --</option>
                                <option value="Entrega">Entrega</option>
                                <option value="Recepción">Recepción</option>
                                <option value="Envío">Envío</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre_servicio" class="form-label">Nombre del servicio</label>
                            <input type="text" class="form-control" id="nombre_servicio" name="nombre_servicio" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="horario_inicio" class="form-label">Hora de Inicio (Opcional)</label>
                            <input type="time" class="form-control" id="horario_inicio" name="horario_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="horario_fin" class="form-label">Hora de Fin (Opcional)</label>
                            <input type="time" class="form-control" id="horario_fin" name="horario_fin">
                        </div>
                         
                         <div class="col-12">
                            <label class="form-label">Días disponibles (Opcional)</label>
                            <div class="d-flex flex-wrap" style="gap: 15px;">
                                <?php foreach ($dias_semana as $dia): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_disponibles[]" value="<?php echo $dia; ?>" id="create_dia_<?php echo $dia; ?>">
                                    <label class="form-check-label" for="create_dia_<?php echo $dia; ?>">
                                        <?php echo $dia; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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

<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=update_service" method="POST" id="editServiceForm">
                <input type="hidden" id="edit_id_servicio" name="edit_id_servicio">
                
                <div class="modal-body">

                    <div id="editServiceError" class="alert alert-danger d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_tipo" class="form-label">Tipo de servicio</label>
                            <select class="form-select" id="edit_tipo" name="edit_tipo" required>
                                <option value="Entrega">Entrega</option>
                                <option value="Recepción">Recepción</option>
                                <option value="Envío">Envío</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_nombre_servicio" class="form-label">Nombre de servicio</label>
                            <input type="text" class="form-control" id="edit_nombre_servicio" name="edit_nombre_servicio" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_descripcion" class="form-label">Descripción (Opcional)</label>
                            <textarea class="form-control" id="edit_descripcion" name="edit_descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_horario_inicio" class="form-label">Hora de Inicio (Opcional)</label>
                            <input type="time" class="form-control" id="edit_horario_inicio" name="edit_horario_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_horario_fin" class="form-label">Hora de Fin (Opcional)</label>
                            <input type="time" class="form-control" id="edit_horario_fin" name="edit_horario_fin">
                        </div>
                         
                         <div class="col-12">
                            <label class="form-label">Días disponibles (Opcional)</label>
                            <div class="d-flex flex-wrap" style="gap: 15px;">
                                <?php foreach ($dias_semana as $dia): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="edit_dias_disponibles[]" value="<?php echo $dia; ?>" id="edit_dia_<?php echo $dia; ?>">
                                    <label class="form-check-label" for="edit_dia_<?php echo $dia; ?>">
                                        <?php echo $dia; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
    $page_custom_js = <<<JS
    <script>
        // -----------------------------------------------------------------
        // COMENTARIO NUEVO: INICIO DE LÓGICA DE VALIDACIÓN DE HORARIOS
        // -----------------------------------------------------------------

        // Función para mostrar un error
        function showServiceError(errorDiv, message) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('d-none'); // Muestra la alerta
        }

        // Función para limpiar el error
        function clearServiceError(errorDiv) {
            if (!errorDiv.classList.contains('d-none')) {
                errorDiv.textContent = '';
                errorDiv.classList.add('d-none'); // Oculta la alerta
            }
        }

        // Función de validación principal
        function validateService(formType) {
            let inicioInput, finInput, errorDiv;

            if (formType === 'create') {
                inicioInput = document.getElementById('horario_inicio');
                finInput = document.getElementById('horario_fin');
                errorDiv = document.getElementById('createServiceError');
            } else {
                inicioInput = document.getElementById('edit_horario_inicio');
                finInput = document.getElementById('edit_horario_fin');
                errorDiv = document.getElementById('editServiceError');
            }
            
            clearServiceError(errorDiv); // Limpia errores anteriores

            const startTime = inicioInput.value;
            const endTime = finInput.value;

            //Solo valida si AMBOS campos están llenos
            if (startTime && endTime) {
                if (endTime <= startTime) {
                    showServiceError(errorDiv, 'Error: La hora de fin debe ser mayor que la hora de inicio.');
                    return false; // Detiene el envío
                }
            }
            
            // No valida si uno o ambos están vacíos (son opcionales)
            return true; // Permite el envío
        }

        // Listeners para los formularios
        document.addEventListener('DOMContentLoaded', () => {
            const createForm = document.getElementById('createServiceForm');
            const editForm = document.getElementById('editServiceForm');

            if (createForm) {
                createForm.addEventListener('submit', (e) => {
                    if (!validateService('create')) {
                        e.preventDefault(); // Detiene el envío si la validación falla
                    }
                });
                // Limpia el error si el usuario corrige
                document.querySelectorAll('#createServiceModal input[type="time"]').forEach(input => {
                    input.addEventListener('input', () => clearServiceError(document.getElementById('createServiceError')));
                });
            }

            if (editForm) {
                editForm.addEventListener('submit', (e) => {
                    if (!validateService('edit')) {
                        e.preventDefault(); // Detiene el envío si la validación falla
                    }
                });
                //  Limpia el error si el usuario corrige
                document.querySelectorAll('#editServiceModal input[type="time"]').forEach(input => {
                    input.addEventListener('input', () => clearServiceError(document.getElementById('editServiceError')));
                });
            }
        });


        // -----------------------------------------------------------------
        // INICIO DE LÓGICA PARA POBLAR EL MODAL DE EDICIÓN
        
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const tipo = button.getAttribute('data-tipo');
                const nombre = button.getAttribute('data-nombre');
                const descripcion = button.getAttribute('data-descripcion');
                const horarioInicio = button.getAttribute('data-horario-inicio');
                const horarioFin = button.getAttribute('data-horario-fin');
                const dias = button.getAttribute('data-dias');

                // Poblamos el formulario (campos de texto)
                document.getElementById('edit_id_servicio').value = id;
                document.getElementById('edit_tipo').value = tipo;
                document.getElementById('edit_nombre_servicio').value = nombre;
                document.getElementById('edit_descripcion').value = descripcion;
                document.getElementById('edit_horario_inicio').value = horarioInicio;
                document.getElementById('edit_horario_fin').value = horarioFin;

                // --- Lógica para poblar checkboxes
                
                //  Convertimos el string en un array. Usamos map() para quitar espacios.
                const diasArray = dias ? dias.split(',').map(d => d.trim()) : [];

                //  Seleccionamos TODOS los checkboxes del modal de EDICIÓN
                const checkboxes = document.querySelectorAll('#editServiceModal .form-check-input');

                //  Iteramos sobre ellos
                checkboxes.forEach(checkbox => {
                    //  Marcamos la casilla (true) si su 'value' (ej. "Lunes")
                    //    está incluido en nuestro array de días
                    checkbox.checked = diasArray.includes(checkbox.value);
                });
            });
        });
    </script>
JS;
    include_once "app/views/admin/templates/admin_footer.php";
?>