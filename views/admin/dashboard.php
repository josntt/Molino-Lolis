<?php
    $page_title = 'Dashboard Administrativo';
    include_once "app/views/admin/templates/admin_header.php";
?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        
         <div class="text-center mb-4">
            <p class="h3 mb-1">Bienvenido al panel de administración</p>
            <p class="text-muted">Seleccione una sección para comenzar a gestionar.</p>
        </div>
        
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

        <?php if (isset($stats)): ?>
        <div class="row g-4 mb-4">
            
            <div class="col-md-4">
                <div class="card shadow-sm h-100 <?php echo ($stats['pending_questions'] > 0) ? 'border-danger border-3' : 'border-success'; ?>">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">PREGUNTAS PENDIENTES</h6>
                        <p class="h1 fw-bold <?php echo ($stats['pending_questions'] > 0) ? 'text-danger' : ''; ?>">
                            <?php echo $stats['pending_questions']; ?>
                        </p>
                        <a href="<?php echo BASE_URL; ?>index.php?accion=manage_questions" class="btn btn-sm <?php echo ($stats['pending_questions'] > 0) ? 'btn-danger' : 'btn-outline-success'; ?>">
                            Ir a preguntas
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">NUEVOS CLIENTES (Últ. 30 días)</h6>
                        <p class="h1 fw-bold text-success">
                            <?php echo $stats['new_clients']; ?>
                        </p>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted">HORARIOS CREADOS (Este Mes)</h6>
                        <p class="h1 fw-bold text-success">
                            <?php echo $stats['monthly_schedules']; ?>
                        </p>
                       
                    </div>
                </div>
            </div>
            
        </div>
        <?php endif; ?>
       <br>

        <div class="row g-4">
            
            <?php 
            // Este bloque 'if' muestra los enlaces que son comunes para AMBOS roles: Administrador y Trabajador.
            if ($usuario_rol == 'Administrador' || $usuario_rol == 'Trabajador'): ?>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_products" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-cubes fa-3x text-success icon-primary"></i>
                                <i class="fas fa-tag fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de productos</h5>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_schedules" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-calendar-alt fa-3x text-success icon-primary"></i>
                                <i class="fas fa-clock fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de horarios</h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_services" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-concierge-bell fa-3x text-success icon-primary"></i>
                                <i class="fas fa-cogs fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de servicios</h5>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_questions" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-question-circle fa-3x text-success icon-primary"></i>
                                <i class="fas fa-reply fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de preguntas</h5>
                        </div>
                    </a>
                </div>
                        
            <?php endif;  ?>
            
           <?php 
           //  bloque 'if' separado muestra los enlaces que son sensibles y SOLO el Administrador debe ver
           if ($usuario_rol == 'Administrador'): ?>

                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_news" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-bullhorn fa-3x text-success icon-primary"></i>
                                <i class="fas fa-pen-to-square fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de avisos</h5>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_faq" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-info-circle fa-3x text-success icon-primary"></i>
                                <i class="fas fa-book-open fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de FAQ</h5>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_users" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-users-cog fa-3x text-success icon-primary"></i>
                                <i class="fas fa-user-pen fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de usuarios</h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_contact" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-envelope-open-text fa-3x text-success icon-primary"></i>
                                <i class="fas fa-inbox fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de contacto</h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_reports" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-chart-line fa-3x text-success icon-primary"></i>
                                <i class="fas fa-file-pdf fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Reportes de producción</h5>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <a href="<?php echo BASE_URL; ?>index.php?accion=manage_backups" class="admin-card card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-database fa-3x text-success icon-primary"></i>
                                <i class="fas fa-cloud-arrow-down fa-3x text-success icon-secondary"></i> </div>
                            <h5 class="card-title mb-0">Gestión de respaldos</h5>
                        </div>
                    </a>
                </div>

            <?php endif;  ?>
            
        </div>
    </div>
</div>

<?php 
    include_once "app/views/admin/templates/admin_footer.php";
?>