<?php

?>
    
    </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <?php
    // Imprime JS personalizado si se definió en la vista
    if (isset($page_custom_js)) {
        echo $page_custom_js;
    }
    ?>
    
    <script src="<?php echo BASE_URL; ?>public/js/admin_darkmode.js"></script>
    </body>
</html>