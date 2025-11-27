<?php
    // app/views/admin/manage_backups.php
    $page_title = 'Respaldos y copias de seguridad';
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 text-success"></h1>
    
    <a href="<?php echo BASE_URL; ?>index.php?accion=create_backup" class="btn btn-success btn-lg fw-bold shadow-sm" 
       onclick="return confirm('¿Estás seguro de que quieres crear un nuevo respaldo ahora? Esto puede tardar unos segundos.');">
        <i class="bi bi-plus-circle me-2"></i>
        Crear respaldo
    </a>
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

<div class="card shadow-sm border-danger mb-4">
    <div class="card-header bg-danger text-white">
        <h2 class="h4 mb-0 fw-bold">
            <i class="bi bi-upload me-2"></i>
            Restauración de respaldos
        </h2>
    </div>
    <div class="card-body">
        <p class="text-danger fw-bold">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            ¡ADVERTENCIA! Esta acción es extremadamente PELIGROSA
        </p>
        <p>Antes de subir un archivo tenga en cuenta que se <b>SOBRESCRIBIRÁ TODA LA BASE DE DATOS ACTUAL</b> con el contenido del archivo.
         Todos los datos (clientes, productos, horarios) registrados después de que se creó ese respaldo 
         <b>se perderán permanentemente</b>.</p>
        
        <form action="<?php echo BASE_URL; ?>index.php?accion=upload_backup" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <input type="file" class="form-control" name="backup_file" id="backup_file" accept=".sql" required>
                <button class="btn btn-danger fw-bold" type="submit" 
                        onclick="return confirm('PELIGRO: ¿Estás ABSOLUTAMENTE SEGURO de que quieres sobrescribir la base de datos con este archivo? Esta acción es IRREVERSIBLE.');">
                    Subir y restaurar
                </button>
            </div>
            <small class="form-text text-muted">Solo se aceptan archivos .sql generados por este sistema.</small>
        </form>
    </div>
</div>
<div class="card shadow-sm border-success">
    <div class="card-header bg-success-subtle">
        <h2 class="h4 mb-0 text-success fw-bold">
            <i class="bi bi-archive-fill me-2"></i>
            Respaldos en existencia
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre del archivo</th>
                        <th scope="col">Fecha de creación</th>
                        <th scope="col">Tamaño</th>
                        <th scope="col">Creado por</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                    </thead>
                <tbody>
                    <?php if (empty($backups) && !$error_db): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay respaldos creados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($backup['name']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y h:i A', $backup['date'])); ?></td>
                            <td><?php echo htmlspecialchars(number_format($backup['size'] / 1024, 2)); ?> KB</td>
                            
                            <td><?php echo htmlspecialchars($backup['creator_name']); ?></td>
                            <td class="text-end">
                                
                                <a href="<?php echo BASE_URL; ?>index.php?accion=download_backup&file=<?php echo urlencode($backup['name']); ?>" 
                                   class="btn btn-primary btn-sm" 
                                   title="Descargar">
                                    <i class="bi bi-download"></i>
                                </a>
                                
                                <a href="<?php echo BASE_URL; ?>index.php?accion=restore_backup&file=<?php echo urlencode($backup['name']); ?>" 
                                   class="btn btn-warning btn-sm" 
                                   title="Restaurar" 
                                   onclick="return confirm('⚠¡ADVERTENCIA!⚠\n\n¿Estás seguro de que quieres restaurar la base de datos a esta versión? (<?php echo htmlspecialchars($backup['name']); ?>)\n\nTODOS los datos creados después de esta fecha SE PERDERÁN PARA SIEMPRE 💀 Esta acción es irreversible!!! ');">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>index.php?accion=delete_backup&file=<?php echo urlencode($backup['name']); ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Eliminar" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar este respaldo? Esta acción no se puede deshacer.');">
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

<?php 
    include_once "app/views/admin/templates/admin_footer.php";
?>