<?php
    $servidor = 'localhost';
    $basedatos= "correcaminos";
    $usuariobase = "correcaminosu";
    $passwordbase = "beepbeep";
    
    $link = mysqli_connect($servidor,$usuariobase, $passwordbase)
        or die ("No se pudo establecer conexión");
        
    $db = mysqli_select_db($link, $basedatos)
        or die ("No se encuentra la base de datos");
?>