<?php
    $servidor = 'localhost';
    $basedatos= "correcaminos";
    $usuariobase = "correcaminosu";
    $passwordbase = "beepbeep";
    
    $link = @mysql_connect($servidor,$usuariobase, $passwordbase)
        or die ("No se pudo establecer conexión");
        
    $db = @mysql_select_db($basedatos,$link)
        or die ("No se encuentra la base de datos");
?>