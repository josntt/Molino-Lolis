<?php
    //Lógica para manejar el caso idAdmin
    // Esta lógica de vista se queda para preparar los datos
    $id_field = '';
    if ($type == 'administrador') {
        $id_field = 'idAdmin'; // El caso especial sensible a mayúsculas
    } else {
        $id_field = 'id' . ucfirst($type); // El caso normal idCliente o idTrabajador
    }
    $user_id = $user[$id_field];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body class="bg-warning-subtle">
    
    <nav class="navbar navbar-expand-lg bg-success shadow-sm" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>index.php?accion=dashboard">
                <img src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png"  alt="Logo" width="70" height="70" class="me-2 rounded-circle">
                <h1 class="h4 mb-0 text-white">Molino de Nixtamal "Lolis"</h1>
            </a>
            <div class="d-flex align-items-center text-white">
                <a href="<?php echo BASE_URL; ?>index.php?accion=logout" class="btn btn-warning fw-bold">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success-subtle">
                        <h3 class="mb-0">Editar usuario: <?php echo htmlspecialchars($user['nombre']); ?> (<?php echo htmlspecialchars(ucfirst($type)); ?>)</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        
                        <form action="<?php echo BASE_URL; ?>index.php?accion=updateUser" method="POST" onsubmit="return confirmUpdate();">
                            
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <input type="hidden" name="user_type" value="<?php echo $type; ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-bold">Nombre(s)</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos" class="form-label fw-bold">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="correo" class="form-label fw-bold">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                                </div>

                                <?php if ($type == 'trabajador'): ?>
                                    <div class="col-md-6">
                                        <label for="genero" class="form-label fw-bold">Género</label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="" disabled>Selecciona un género</option>
                                            <option value="Femenino" <?php if ($user['genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                                            <option value="Masculino" <?php if ($user['genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                                            <option value="Otro" <?php if ($user['genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="puesto" class="form-label fw-bold">Puesto</label>
                                        <input type="text" class="form-control" id="puesto" name="puesto" value="<?php echo htmlspecialchars($user['puesto'] ?? ''); ?>">
                                    </div>
                                <?php elseif ($type == 'cliente'): ?>
                                    <div class="col-md-6">
                                        <label for="genero" class="form-label fw-bold">Género</label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="" disabled>Selecciona un género</option>
                                            <option value="Femenino" <?php if ($user['genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                                            <option value="Masculino" <?php if ($user['genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                                            <option value="Otro" <?php if ($user['genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-lg" onclick="history.back();">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Actualizar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmUpdate() {
            return confirm('¿Estás seguro de que quieres actualizar esta información?');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
