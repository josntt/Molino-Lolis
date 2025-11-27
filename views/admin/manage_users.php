<?php
    $page_title = 'Gestión de Usuarios';
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success">Gestión de Usuarios</h1>
    
    <button class="btn btn-success btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-plus-circle me-2"></i>
        Añadir
    </button>
</div>

<?php if (isset($error_db) && $error_db): ?>
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php echo htmlspecialchars($error_db); ?>
    </div>
<?php endif; ?>
<?php if(isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-shield-slash-fill me-2"></i>
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if(isset($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning"> <h2 class="h4 mb-0 text-success fw-bold"><i class="bi bi-person-badge me-2"></i>Trabajadores</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Puesto</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trabajadores) && !$error_db): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay trabajadores registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($trabajadores as $trab): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trab['nombre'] . ' ' . $trab['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($trab['correo']); ?></td>
                            <td><?php echo htmlspecialchars($trab['telefono'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($trab['puesto'] ?: 'N/A'); ?></td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editUserModal"
                                        title="Editar"
                                        data-id="<?php echo $trab['idTrabajador']; ?>"
                                        data-tipo="trabajador"
                                        data-nombre="<?php echo htmlspecialchars($trab['nombre']); ?>"
                                        data-apellidos="<?php echo htmlspecialchars($trab['apellidos']); ?>"
                                        data-correo="<?php echo htmlspecialchars($trab['correo']); ?>"
                                        data-telefono="<?php echo htmlspecialchars($trab['telefono'] ?? ''); ?>"
                                        data-genero="<?php echo htmlspecialchars($trab['genero']); ?>"
                                        data-puesto="<?php echo htmlspecialchars($trab['puesto'] ?? ''); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=deleteUser&tipo=trabajador&id=<?php echo $trab['idTrabajador']; ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar a este trabajador?');">
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

<div class="card shadow-sm">
    <div class="card-header bg-warning"> <h2 class="h4 mb-0 text-success fw-bold"><i class="bi bi-people me-2"></i>Clientes</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Fecha de Registro</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes) && !$error_db): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay clientes registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cli): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cli['nombre'] . ' ' . $cli['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($cli['correo']); ?></td>
                            <td><?php echo htmlspecialchars($cli['telefono'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y h:i A', strtotime($cli['fecha_registro']))); ?></td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editUserModal"
                                        title="Editar"
                                        data-id="<?php echo $cli['idCliente']; ?>"
                                        data-tipo="cliente"
                                        data-nombre="<?php echo htmlspecialchars($cli['nombre']); ?>"
                                        data-apellidos="<?php echo htmlspecialchars($cli['apellidos']); ?>"
                                        data-correo="<?php echo htmlspecialchars($cli['correo']); ?>"
                                        data-telefono="<?php echo htmlspecialchars($cli['telefono'] ?? ''); ?>"
                                        data-genero="<?php echo htmlspecialchars($cli['genero']); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=deleteUser&tipo=cliente&id=<?php echo $cli['idCliente']; ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar a este cliente?');">
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

<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Crear nuevo usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=adminCreateUser" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="create_user_type" class="form-label fw-bold">Tipo de Usuario</label>
                            <select class="form-select" id="create_user_type" name="user_type" required>
                                <option value="" selected disabled>-- Selecciona un rol --</option>
                                <option value="cliente">Cliente</option>
                                <option value="trabajador">Trabajador</option>
                            </select>
                        </div>
                        <hr class="my-3">
                        <div class="col-md-6">
                            <label for="create_nombre" class="form-label fw-bold">Nombre(s)</label>
                            <input type="text" class="form-control" id="create_nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="create_apellidos" class="form-label fw-bold">Apellidos</label>
                            <input type="text" class="form-control" id="create_apellidos" name="apellidos" required>
                        </div>
                        <div class="col-12">
                            <label for="create_correo" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="create_correo" name="correo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="create_telefono" class="form-label fw-bold">Teléfono (Opcional)</label>
                            <input type="tel" class="form-control" id="create_telefono" name="telefono">
                        </div>
                         <div class="col-md-6">
                            <label for="create_genero" class="form-label fw-bold">Género</label>
                            <select class="form-select" id="create_genero" name="genero" required>
                                <option value="">Selecciona un género</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-12" id="create_puesto_wrapper" style="display: none;">
                            <label for="create_puesto" class="form-label fw-bold">Puesto (Requerido para trabajador)</label>
                            <input type="text" class="form-control" id="create_puesto" name="puesto">
                        </div>
                        <div class="col-md-6">
                            <label for="create_contrasena" class="form-label fw-bold">Contraseña</label>
                            <input type="password" class="form-control" id="create_contrasena" name="contrasena" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="create_confirmar_contrasena" class="form-label fw-bold">Confirmar contraseña</label>
                            <input type="password" class="form-control" id="create_confirmar_contrasena" name="confirmar_contrasena" required>
                        </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-fill me-2"></i>Editar Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>index.php?accion=updateUser" method="POST">
                
                <input type="hidden" id="edit_user_id" name="user_id">
                <input type="hidden" id="edit_user_type" name="user_type">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_nombre" class="form-label fw-bold">Nombre(s)</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_apellidos" class="form-label fw-bold">Apellidos</label>
                            <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_correo" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit_correo" name="correo" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_telefono" class="form-label fw-bold">Teléfono (Opcional)</label>
                            <input type="tel" class="form-control" id="edit_telefono" name="telefono">
                        </div>

                        <div class="col-md-6" id="edit_genero_wrapper">
                            <label for="edit_genero" class="form-label fw-bold">Género</label>
                            <select class="form-select" id="edit_genero" name="genero">
                                <option value="" disabled>Selecciona un género</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="edit_puesto_wrapper">
                            <label for="edit_puesto" class="form-label fw-bold">Puesto</label>
                            <input type="text" class="form-control" id="edit_puesto" name="puesto">
                        </div>
                    </div>
                    <small class="form-text text-muted mt-3 d-block">La contraseña no se puede modificar desde este panel por seguridad.</small>
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
    // COMENTARIO NUEVO: Se añade el JS para manejar los modales
    $page_custom_js = <<<JS
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // ---- Lógica para el modal CREAR ----
            const userTypeSelect = document.getElementById('create_user_type');
            const puestoWrapper = document.getElementById('create_puesto_wrapper');
            const puestoInput = document.getElementById('create_puesto');

            if (userTypeSelect) {
                userTypeSelect.addEventListener('change', () => {
                    if (userTypeSelect.value === 'trabajador') {
                        puestoWrapper.style.display = 'block';
                        puestoInput.required = true;
                    } else {
                        puestoWrapper.style.display = 'none';
                        puestoInput.required = false;
                    }
                });
            }

            // ---- Lógica para el modal EDITAR ----
            const editGeneroWrapper = document.getElementById('edit_genero_wrapper');
            const editPuestoWrapper = document.getElementById('edit_puesto_wrapper');
            const editGeneroInput = document.getElementById('edit_genero');
            const editPuestoInput = document.getElementById('edit_puesto');

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    // 1. Obtener todos los datos del botón
                    const id = button.getAttribute('data-id');
                    const tipo = button.getAttribute('data-tipo');
                    const nombre = button.getAttribute('data-nombre');
                    const apellidos = button.getAttribute('data-apellidos');
                    const correo = button.getAttribute('data-correo');
                    const telefono = button.getAttribute('data-telefono');
                    const genero = button.getAttribute('data-genero');

                    // 2. Poblar los campos comunes
                    document.getElementById('edit_user_id').value = id;
                    document.getElementById('edit_user_type').value = tipo;
                    document.getElementById('edit_nombre').value = nombre;
                    document.getElementById('edit_apellidos').value = apellidos;
                    document.getElementById('edit_correo').value = correo;
                    document.getElementById('edit_telefono').value = telefono;
                    document.getElementById('edit_genero').value = genero;

                    // 3. Lógica para mostrar/ocultar campos
                    if (tipo === 'trabajador') {
                        const puesto = button.getAttribute('data-puesto');
                        editPuestoWrapper.style.display = 'block';
                        editGeneroWrapper.style.display = 'block';
                        editPuestoInput.value = puesto;
                        editPuestoInput.required = true;
                        editGeneroInput.required = true;
                    } else if (tipo === 'cliente') {
                        editPuestoWrapper.style.display = 'none';
                        editGeneroWrapper.style.display = 'block';
                        editPuestoInput.required = false;
                        editGeneroInput.required = true;
                    }
                });
            });
        });
    </script>
JS;

    include_once "app/views/admin/templates/admin_footer.php";
?>