<?php 
    include_once "app/views/templates/header.php"; 
?>

<script src="<?php echo BASE_URL; ?>public/js/about-contact.js" defer></script>

<section class="contact-hero contact-hero--flat fade-up">
  <div class="contact-columns">
    <aside class="brand-side slide-in-left">
      <h2 class="brand-title">
        <span class="brand-line brand-line--1">MOLINO</span>
        <span class="brand-line brand-line--2">“LOLIS”</span>
      </h2>

      <img class="brand-logo" src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png" alt="Molino de Nixtamal Lolis">

      <div class="brand-slogan">La Tradición del maíz hecha masa fresca</div>
    </aside>

    <article class="info-side slide-in-right">
      <h1 class="contact-heading">
        <a href="#mapa" class="heading-link" title="Ver ubicación en el mapa">Módulo de contacto</a>
      </h1>

      <?php
        // PageController nos pasa las variables $contact y $error_db
        
        if (isset($error_db) && $error_db): 
        // error si la BD falló
      ?>
        <div style="color: red; background: #fdd; border: 1px solid red; padding: 15px; border-radius: 5px;">
            <strong>Error al cargar:</strong> <?php echo htmlspecialchars($error_db); ?>
        </div>
      <?php 
      //  Se usa $contact y se comprueba si está vacío
      elseif (empty($contact) || empty($contact['telefono'])): 
      ?>
        <div style="color: #31708f; background: #d9edf7; border: 1px solid #bce8f1; padding: 15px; border-radius: 5px;">
            La información de contacto no está disponible en este momento.
        </div>
      <?php 
      else: 
      // Si todo está bien, se muestran los datos
      ?>
        <ul class="contact-items">
          <li class="citem">
            <span class="cicon cicon--phone" aria-hidden="true"></span>
            <a href="tel:<?php echo htmlspecialchars($contact['telefono']); ?>">
              <?php echo htmlspecialchars($contact['telefono']); ?>
            </a>
          </li>

          <li class="citem">
            <span class="cicon cicon--mail" aria-hidden="true"></span>
            <a href="mailto:<?php echo htmlspecialchars($contact['correo_contacto']); ?>">
              <?php echo htmlspecialchars($contact['correo_contacto']); ?>
            </a>
          </li>

          <?php if (!empty($contact['url_facebook'])): ?>
            <li class="citem">
              <span class="cicon cicon--fb" aria-hidden="true"></span>
              <a target="_blank" rel="noopener" href="<?php echo htmlspecialchars($contact['url_facebook']); ?>">
                Molino de Nixtamal Tejalpa
              </a>
            </li>
          <?php endif; ?>

          <li class="citem citem--wrap">
            <span class="cicon cicon--pin" aria-hidden="true"></span>
            <span>
              <?php echo nl2br(htmlspecialchars($contact['direccion'])); ?>
            </span>
          </li>
        </ul>
      <?php 
      endif; 
      ?>
    </article>
  </div>
</section>

<section class="fade-up" style="--delay:140ms;">
  <div class="map-frame contact-map" id="mapa">
    <iframe title="Ubicación Molino Lolis"
      loading="lazy"
      src="https://www.google.com/maps?q=Mercado%20La%20Asunci%C3%B3n%20de%20Tejalpa%20Jiutepec&output=embed">
      </iframe>
  </div>
</section>

<?php 
    include_once "app/views/templates/footer.php"; 
?>