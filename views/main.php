<?php 
    include_once "app/views/templates/header.php";
?>

<section class="hero-section">
    <div class="hero-left">
        
        <span class="hero-tag">Molino de nixtamal en Jiutepec</span>

        <h1 class="hero-title">
            MOLINO <br>
            <span class="hero-title-green">"LOLIS"</span>
        </h1>
         

        <p class="hero-subtitle">
            Maíz, tradición y sabor casero en cada molida.
        </p>

        <ul class="hero-bullets">
            <li>Mólidas programadas para tu comodidad</li>
            <li>Productos frescos y seleccionados</li>
            <li>Atención cercana y de confianza</li>
        </ul>

        <a href="index.php?accion=products" class="btn-hero ">
            VER PRODUCTOS
        </a>

        <p class="hero-bottom-text">
            Consulta también horarios y servicios en línea.
        </p>

        
    </div>
    <div class="hero-content-container hero-right">
        <div class="hero-images">
            <div class="image-wrapper">
                <img src="<?php echo BASE_URL; ?>public/media/imagen1.png" alt="" class="zoom-effect">
            </div>
            <div class="image-wrapper">
                <img src="<?php echo BASE_URL; ?>public/media/imagen2.png" alt="" class="zoom-effect">
            </div>
            <div class="image-wrapper">
                <img src="<?php echo BASE_URL; ?>public/media/imagen3.png" alt="" class="zoom-effect">
            </div>
        </div>
        
    </div>
    
</section>

        
<section class="process-section">

    <div class="process-header">
        <h2 class="process-title">Lo que hay detrás de tu masa</h2>
        
        <p class="process-subtitle">
            Nuestro secreto es simple: respeto por el proceso artesanal.
        </p>
    </div>
    
    <div class="process-timeline">
        
        <div class="process-step">
            <div class="process-step-image">
                <img 
                    src="<?php echo BASE_URL; ?>/public/media/seleccion.jpg" 
                    alt="Selección de Maíz"
                >
            </div>
            <span class="process-step-number">01</span>
            <h3 class="process-step-title">Selección del Grano</h3>
            <p class="process-step-desc">
                Todo empieza con el mejor maíz criollo, seleccionado de agricultores locales que conocemos y en quienes confiamos.
            </p>
            <div class="process-arrow"></div>
        </div>
        
        <div class="process-step">
            <div class="process-step-image">
                <img 
                    src="<?php echo BASE_URL; ?>/public/media/nixtamalizacion.jpg" 
                    alt="Maíz Nixtamalizado"
                >
            </div>
            <span class="process-step-number">02</span>
            <h3 class="process-step-title">Nixtamalización</h3>
            <p class="process-step-desc">
                Cocemos el maíz lentamente con cal de piedra. Este proceso ancestral despierta sus nutrientes y crea el sabor único.
            </p>
            <div class="process-arrow"></div>
        </div>
        
        <div class="process-step">
            <div class="process-step-image">
                <img 
                    src="<?php echo BASE_URL; ?>/public/media/molino.jpg" 
                    alt="Molino de Piedra"
                >
            </div>
            <span class="process-step-number">03</span>
            <h3 class="process-step-title">Molienda en Piedra</h3>
            <p class="process-step-desc">
                El maíz nixtamalizado pasa por nuestro molino de piedra volcánica, garantizando la textura suave y perfecta que nos distingue.
            </p>
            <div class="process-arrow"></div>
        </div>
        
        <div class="process-step">
            <div class="process-step-image">
                <img 
                    src="<?php echo BASE_URL; ?>/public/media/masa.jpg" 
                    alt="Masa Fresca"
                >
            </div>
            <span class="process-step-number">04</span>
            <h3 class="process-step-title">Masa Fresca, Sin Más</h3>
            <p class="process-step-desc">
                El resultado: una masa pura, fresca, sin conservadores ni harinas añadidas. Lista para tus tortillas, tamales y antojitos.
            </p>
        </div>

    </div>
</section>


<?php 
    include_once "app/views/templates/footer.php";
?>
