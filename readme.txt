    - Se deben activar los módulos siguientes en Apache para activar la compresión y el cache:
    
        mod_expires.c    
        mod_deflate.c    
        mod_headers.c    
    
    - En caso de no activar desde .htaccess se puede poner en los PHP los headers
    
    <?php
        $seconds_to_cache = 604800; // 7 días
        $ts = gmdate("D, d M Y H:i:s ", time() + $seconds_to_cache) . " GMT";
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");
    ?>
    
    - El usuario por defecto se debe quitar para dejar que sea el del usuario logueado
    
        app/controller/level1.js
    