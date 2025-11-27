<?php
    $page_title = 'Gestión de Contacto';
    $body_class = 'bg-warning-subtle'; 
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

        <h1 class="h2 text-success mb-4">Información de contacto</h1>

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
        <?php if(isset($error_db) && $error_db) echo "<div class='alert alert-warning'>$error_db</div>"; ?>

        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning">
                <h2 class="h4 mb-0 text-success fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>
                    Editar información
                </h2>
            </div>
            <div class="card-body p-4">
                
                <form action="<?php echo BASE_URL; ?>index.php?accion=update_contact" method="POST">
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               value="<?php echo htmlspecialchars($contact['telefono'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo_contacto" class="form-label fw-bold">Correo Electrónico de Contacto</label>
                        <input type="email" class="form-control" id="correo_contacto" name="correo_contacto"
                               value="<?php echo htmlspecialchars($contact['correo_contacto'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="4" required><?php echo htmlspecialchars($contact['direccion'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="url_facebook" class="form-label fw-bold">URL de Facebook (Opcional)</label>
                        <input type="url" class="form-control" id="url_facebook" name="url_facebook"
                               value="<?php echo htmlspecialchars($contact['url_facebook'] ?? ''); ?>" placeholder="https://facebook.com/molino-lolis">
                    </div>

                    <hr class="my-4">

                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
                            <i class="bi bi-save-fill me-2"></i>
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php 
    include_once "app/views/admin/templates/admin_footer.php";
?>