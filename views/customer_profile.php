<?php
    include_once "app/views/templates/header.php";
?>

<div style="width: 90%; max-width: 900px; margin: 40px auto; padding: 20px;">

    <?php if(isset($success_message)): ?>
        <div class="alert alert-success" role="alert" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger" role="alert" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-bottom: 30px;">
        <img src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png" alt="Logo del Molino Lolis" style="width: 120px; border-radius: 50%;" >
        <h1 style="color: #006A4E; margin-top: 10px;">
            ¡Bienvenido  <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>!
        </h1>
        <p style="font-size: 1.1rem; color: #555;">Este es tu panel de cliente</p>
    </div>
    
    <div style="margin-bottom: 30px; text-align: center;">
        <a href="<?php echo BASE_URL; ?>index.php?accion=showEditForm" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; padding: 10px 15px; text-decoration: none; color: white; border-radius: 5px; margin-right: 10px;">
            Editar mis datos
        </a>
        <a href="<?php echo BASE_URL; ?>index.php?accion=deleteUser" class="btn btn-danger" style="background-color: #dc3545; border-color: #dc3545; padding: 10px 15px; text-decoration: none; color: white; border-radius: 5px;" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible.');">
            Eliminar cuenta
        </a>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

    <div style="margin-bottom: 40px;">
        <h2 style="color: #006A4E; border-bottom: 2px solid #FDCB0A; padding-bottom: 10px;">
            ¿Tienes alguna duda? Escribela aqui abajo :D
        </h2>
        <form action="<?php echo BASE_URL; ?>index.php?accion=submit_question" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="pregunta_texto" style="display: block; margin-bottom: 5px; font-weight: bold;">Escribe tu pregunta:</label>
                <textarea id="pregunta_texto" name="pregunta_texto" rows="4" required style="width: 100%; padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 5px;"></textarea>
            </div>
            <button type="submit" style="background-color: #006A4E; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem;">
                Enviar
            </button>
        </form>
    </div>

    <div>
        <h2 style="color: #006A4E; border-bottom: 2px solid #FDCB0A; padding-bottom: 10px;">
            Mis preguntas realizadas
        </h2>
        
        <?php if (isset($error_db)): ?>
             <p style="color: red;"><?php echo htmlspecialchars($error_db); ?></p>
        <?php elseif (empty($my_questions)): ?>
            <p style="color: #555;">No has enviado ninguna pregunta todavía.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($my_questions as $q): ?>
                    <div style="border: 1px solid #ddd; border-radius: 8px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <strong style="font-size: 1.1rem; color: #333;">Mi pregunta</strong>
                                
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if ($q['estado'] == 'pendiente'): ?>
                                        <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">Pendiente</span>
                                    <?php else: ?>
                                        <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">Respondida</span>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo BASE_URL; ?>index.php?accion=client_delete_question&id=<?php echo $q['id_pregunta']; ?>" 
                                       title="Eliminar mi pregunta"
                                       style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 5px; text-decoration: none; font-size: 0.8rem;"
                                       onclick="return confirm('¿Estás seguro de que quieres eliminar esta pregunta?');">
                                        Eliminar
                                    </a>
                                </div>
                                </div>
                            <p style="margin: 0; color: #555;"><?php echo htmlspecialchars($q['pregunta_texto']); ?></p>
                            <small style="color: #777;">Enviada el: <?php echo htmlspecialchars(date('d/m/Y h:i A', strtotime($q['fecha_pregunta']))); ?></small>
                        </div>
                        
                        <?php if ($q['estado'] == 'respondida' && !empty($q['respuesta_texto'])): ?>
                            <div style="padding: 15px 20px; background-color: #f9f9f9; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                                <strong style="font-size: 1.1rem; color: #006A4E;">Respuesta del molino</strong>
                                <p style="margin: 5px 0 0 0; color: #333; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($q['respuesta_texto'])); ?></p>
                                <small style="color: #777;">Respondida el: <?php echo htmlspecialchars(date('d/m/Y h:i A', strtotime($q['fecha_respuesta']))); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php 
    include_once "app/views/templates/footer.php";
?>