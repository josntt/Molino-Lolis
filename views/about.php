<?php 
    include_once "app/views/templates/header.php"; 
?>

<script src="<?php echo BASE_URL; ?>public/js/about-contact.js"></script>

 <div>
    
 </div>
<section class="band fade-up">
  <div class="band__part band__left">MOLINO</div>
  <img class="band__logo" src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png" alt="Molino de Nixtamal Lolis">
  <div class="band__part band__right">“LOLIS”</div>
</section>

<!-- TARJETAS DE INFORMACIÓN -->
<section class="about-cards fade-up" style="--delay:100ms;">
  <!-- Misión -->
  <article class="abox">
    <h3 class="abox__title">Misión</h3>
    <p class="abox__text">
      En <strong>Molino de Nixtamal “Lolis”</strong> nos dedicamos a ofrecer productos frescos y de calidad,
      elaborados con procesos tradicionales que preservan el auténtico sabor mexicano. Ponemos a disposición de nuestros
      clientes masa para tortillas, masa preparada para tamales, nixtamal precocido y descabezado para pozole, entre otros,
      garantizando atención cercana y un servicio confiable que responde a las necesidades de la comunidad.
    </p>
  </article>

  <!-- Quiénes somos -->
  <article class="abox abox--center">
    <h3 class="abox__title">¿Quiénes somos?</h3>
    <p class="abox__text">
      <strong>Molino Lolis</strong> nació como un proyecto familiar con el propósito de brindar a la comunidad de Tejalpa
      productos frescos y elaborados, ofreciendo a sus clientes masa para tortillas, masa preparada para tamales y nixtamal
      precocido y descabezado para pozole, entre otros. Más de tres décadas compartiendo el sabor de nuestras raíces.
    </p>
  </article>

  <!-- Visión -->
  <article class="abox">
    <h3 class="abox__title">Visión</h3>
    <p class="abox__text">
      Convertirnos en el molino de referencia en Jiutepec y sus alrededores, con una gran variedad de productos enfocados
      en el tamal mexicano: masa para tortillas, masa preparada para tamales, nixtamal precocido y descabezado para pozole,
      entre muchos más. Mantener la tradición con calidad y confianza, integrando herramientas digitales que acerquen
      nuestros productos y faciliten el acceso a nuestros servicios.
    </p>
  </article>
</section>



<!-- Valores -->
<section class="values2 fade-up" style="--delay:360ms;">
  <h2 class="h2 h2--center h2--accent">Nuestros valores</h2>

  <div class="values-grid">
    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Calidad</h3>
      <p class="v-txt">Cuidamos cada detalle, del maíz a tu mesa.</p>
    </article>

    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Tradición</h3>
      <p class="v-txt">Procesos artesanales que honran nuestras raíces.</p>
    </article>

    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Servicio</h3>
      <p class="v-txt">Trato cercano: cada cliente es familia.</p>
    </article>

    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Compromiso</h3>
      <p class="v-txt">Frescura y limpieza, todos los días.</p>
    </article>

    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Respeto e igualdad</h3>
      <p class="v-txt">Equipo unido, oportunidades para todos.</p>
    </article>

    <article class="value-card">
      <div class="v-ic"></div>
      <h3 class="v-ttl">Pasión</h3>
      <p class="v-txt">Amar el oficio se nota en el sabor.</p>
    </article>
  </div>
</section>

<!--  Políticas -->
<section class="accordion fade-up" style="--delay:480ms;">
  <details class="acc">
    <summary class="acc__sum">✅ Política de calidad</summary>
    <div class="acc__content">
      En <strong>Molino de Nixtamal Lolis</strong> creemos que la calidad no se improvisa:
      se construye con constancia, experiencia y amor por el trabajo bien hecho.<br><br>
      Durante más de 30 años hemos mantenido nuestro compromiso de ofrecerte productos elaborados con maíz seleccionado
      y procesos cuidados, para garantizar ese sabor que distingue nuestras masas, tamales y nixtamal para pozole.<br><br>
      Cada entrega, cada cliente satisfecho y cada tortilla suave que sale de nuestras manos son la mejor prueba
      de que nuestra calidad se respalda sola… con sabor, con historia y con confianza.
    </div>
  </details>

  <details class="acc">
    <summary class="acc__sum">🌎 Política ambiental</summary>
    <div class="acc__content">
      Sabemos que cuidar lo que nos rodea también es parte de cuidar nuestras raíces.
      En Molino Lolis usamos el agua y los recursos con conciencia, buscando siempre reducir el desperdicio
      y aprovechar al máximo lo que la tierra nos da.<br><br>
      Respetamos el entorno, fomentamos prácticas sostenibles y trabajamos pensando en el futuro
      de quienes vendrán después de nosotros.<br><br>
      Porque el sabor de lo natural empieza desde el cuidado de la naturaleza. 🌱
    </div>
  </details>

  <details class="acc">
    <summary class="acc__sum">🤝 Política de igualdad y no discriminación</summary>
    <div class="acc__content">
      Nuestro molino ha crecido gracias al esfuerzo y la unión de muchas manos.
      Aquí todos somos parte de una misma familia, donde cada persona importa y tiene un lugar.<br><br>
      Rechazamos cualquier tipo de discriminación y promovemos un ambiente de respeto,
      colaboración y aprendizaje mutuo.<br><br>
      Creemos que cuando se trabaja con armonía, el resultado se siente… y también se saborea.
    </div>
  </details>
</section>

<!-- botónnn  -->
<div class="go-contact fade-up" style="--delay:520ms;">
  <!-- CAMBIO: Se ha renombrado el id por claridad -->
  <button type="button" class="btn-contact" id="btnGoToContact">Ir al módulo de contacto</button>
</div>

<!--  El script usa la variable BASE_URL para la URL -->
<script>
  document.getElementById('btnGoToContact')?.addEventListener('click', () => {
    // La URL ahora apunta a la nueva acción "contact"
    location.href = '<?php echo BASE_URL; ?>index.php?accion=contact';
  });
</script>

<?php 
    include_once "app/views/templates/footer.php"; 
?>
