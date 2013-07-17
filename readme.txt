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
    
        app/controller/main-container.js
        
    - Se debe cambiar la ruta de ImageMagik de "ImageMagick-6.7.6-Q16" a "ImageMagick-6.8.1-Q16" para que funcione en el server
    
        app/model/components/newDocument-save.php
        app/model/components/generateThumb.php


    - Temas pendientes
        Documentos pendientes
        Notificados
    