<?php
include "conexion.php";

// Quitar campos innecesarios
$sql1 = mysqli_query($link, "ALTER TABLE `catalogo_usuarios`
  DROP `password`,
  DROP `auto_refrescar`,
  DROP `configurar_tabla`;");

$sql2 = mysqli_query($link, "ALTER TABLE `catalogo_usuarios`
                    CHANGE `id_usuario` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
                    CHANGE `recibir_correo` `recibir_correo` INT( 11 ) NOT NULL DEFAULT '1',
                    CHANGE `alerta_pendientes` `alerta_pendientes` INT( 11 ) NOT NULL DEFAULT '1'");

// Cambios especificos
// Desactivar usuarios
$sql2 = mysqli_query($link, "UPDATE `catalogo_usuarios`
                     SET activo = 0
                     WHERE id IN (447)");
//Cambiar permisos
$sql2 = mysqli_query($link, "UPDATE `catalogo_usuarios`
                     SET id_privilegios = 1
                     WHERE id IN (1)");                     

?>