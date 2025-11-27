<?php 
    include_once "app/views/templates/header.php";
?>

<style>
    #client-questions .acc__sum::after {
        content: none;
    }
</style>
<div class="values2" style="max-width: 900px; margin-top: 40px;">

    <?php if(isset($success_message)): ?>
        <div class="alert alert-success" role="alert" style="background-color: #d4edda; color: #155724; padding: 10px 20px; border-radius: 5px; margin-bottom: 20px; position: relative;">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" onclick="this.parentElement.style.display='none'" 
                    style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); background: none; border: none; font-size: 1.5rem; color: #155724; cursor: pointer; line-height: 1; padding: 0;">
                &times;
            </button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger" role="alert" style="background-color: #f8d7da; color: #721c24; padding: 10px 20px; border-radius: 5px; margin-bottom: 20px; position: relative;">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" onclick="this.parentElement.style.display='none'" 
                    style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); background: none; border: none; font-size: 1.5rem; color: #721c24; cursor: pointer; line-height: 1; padding: 0;">
                &times;
            </button>
        </div>
    <?php endif; ?>


    <h2 class="h2">Módulo de preguntas frecuentes(FAQ)</h2>
    
    <div class="accordion">
        <?php if (isset($error_db) && strpos($error_db, 'FAQ') !== false): // Solo muestra error de FAQ aquí ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error_db); ?></p>
        
        <?php elseif (empty($faqs)): ?>
            <p style="text-align: center; font-size: 1.1rem;">
                No hay preguntas frecuentes disponibles en este momento.
            </p>
            
        <?php else: ?>
            <?php foreach ($faqs as $faq): ?>
                <details class="acc">
                    <summary class="acc__sum">
                        <?php echo htmlspecialchars($faq['pregunta']); ?>
                    </summary>
                    <div class="acc__content">
                        <?php echo nl2br(htmlspecialchars($faq['respuesta'])); ?>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    
    <h2 class="h2" style="margin-top: 40px;">Preguntas de clientes</h2>
    <h3 class="h3" style="text-align: center;">Porque tus preguntas nos importan!!!</h3>

    <div class="accordion" id="client-questions">
        <?php if (isset($error_db) && strpos($error_db, 'contenido') !== false): // Solo muestra error de Cliente aquí ?>
             <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error_db); ?></p>
             
        <?php elseif (empty($client_questions)): ?>
            <p style="text-align: center; font-size: 1.1rem;">
                Parece que no hay preguntas públicas de otros clientes. Se el primero en preguntar!!!
            </p>
            
        <?php elseif (!isset($error_db)): ?>
            <?php foreach ($client_questions as $q): ?>
                <div class="acc" style="border-color: rgba(0,106,78,.15);">
                    <div class="acc__sum" style="font-weight: normal; cursor: default; display: flex; align-items: center;">
                        <span><?php echo htmlspecialchars($q['pregunta_texto']); ?></span>
                        
                        <?php if ($q['estado'] == 'pendiente'): ?>
                            <span style="background-color: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 15px; font-size: 0.8rem; margin-left: auto;">Pendiente</span>
                        <?php else: ?>
                            <span style="background-color: #d4edda; color: #155724; padding: 3px 8px; border-radius: 15px; font-size: 0.8rem; margin-left: auto;">Respondida</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="acc__content" style="border-top: 1px dashed #ccc;">
                        <strong>Pregunta de:</strong> <?php echo htmlspecialchars($q['cliente_nombre']); ?>
                        <br>
                        <small style="color: #777;">Enviada el: <?php echo htmlspecialchars(date('d/m/Y', strtotime($q['fecha_pregunta']))); ?></small>
                        
                        <?php if ($q['estado'] == 'respondida' && !empty($q['respuesta_texto'])): ?>
                            <div style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px;">
                                <strong style="color: var(--brand);">Respuesta del molino:</strong>
                                <p style="margin-top: 5px;"><?php echo nl2br(htmlspecialchars($q['respuesta_texto'])); ?></p>
                                <small style="color: #777;">Respondida el: <?php echo htmlspecialchars(date('d/m/Y', strtotime($q['fecha_respuesta']))); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


    <h2 class="h2" style="margin-top: 40px;">¿Tienes otra duda?</h2>
    
    <div style="max-width: 700px; margin: 0 auto;">
        <?php 
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'Cliente'): 
        ?>
            <form class="login-form" style="max-width: none;" action="<?php echo BASE_URL; ?>index.php?accion=submit_question" method="POST">
                <input type="hidden" name="source" value="faq">
                <div class="form-group">
                    <label for="pregunta_texto" style="color: var(--brand);">Escribe tu pregunta aquí <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>:</label>
                    <textarea id="pregunta_texto" name="pregunta_texto" rows="4" required style="width: 100%; padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 5px; background: #fff; color: #333;"></textarea>
                </div>
                <button type="submit" class="btn-submit">Enviar</button>
            </form>

        <?php else: ?>
            <div class="go-contact">
                <p style="font-size: 1.1rem; color: #555; margin-bottom: 20px;">
                    Debes iniciar sesión como cliente o crear una cuenta para poder enviar una pregunta.
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?accion=login" class="btn-contact" style="font-size: 1rem !important; padding: 12px 30px !important;">
                    Iniciar sesión o registrarse
                </a>
            </div>
        <?php endif; ?>
    </div>

</div> 
    
<?php 
    include_once "app/views/templates/footer.php";
?>