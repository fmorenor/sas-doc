<?php
    include "conexion.php";
    
    $sql = mysqli_query($link, "ALTER TABLE `documentos` CHANGE `destinatario_documento_enviado` `destinatario_documento_enviado_borrar` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
    
    $sql = mysqli_query($link, "ALTER TABLE `documentos` ADD `destinatario_documento_enviado` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `id_destinatario`");
    
    $sql = mysqli_query($link, "UPDATE documentos
    SET destinatario_documento_enviado = destinatario_documento_enviado_borrar
    WHERE destinatario_documento_enviado_borrar != ''");
    
    $sql = mysqli_query($link, "ALTER TABLE `documentos`
    CHANGE `id_documento_atendido` `id_documento_padre` INT( 11 ) NULL DEFAULT NULL,
    CHANGE `fecha_respuesta` `fecha_actualizacion` TIMESTAMP NULL DEFAULT NULL,
    CHANGE `asignado_fecha` `fecha_asignado` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

    $sql = mysqli_query($link, "ALTER TABLE `documentos` ADD `fecha_turnado` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `turnado_fecha`");
    
    $sql = mysqli_query($link, "UPDATE documentos SET fecha_turnado = turnado_fecha
    WHERE turnado_fecha is not null");
    
    $sql = mysqli_query($link, "DELETE FROM documento_turnado_a
    WHERE id_turnado_a = 0;");
    
    $sql = mysqli_query($link, "ALTER TABLE `documentos` ADD `id_usuario_insertar` INT NOT NULL AFTER `id_estatus` ");
    $sql = mysqli_query($link, "UPDATE `documentos` SET `id_usuario_insertar` = `id_asignado_por`);
    
    
    // Eliminar campos    
    $sql = mysqli_query($link, "ALTER TABLE `documentos`
    DROP `destinatario_documento_enviado_borrar`,
    DROP `nota`,
    DROP `historico_nota`,
    DROP `id_documento_respuesta`,
    DROP `numero_documento_respuesta`,
    DROP `numero_documento_atendido`,
    DROP `turnado_fecha`,
    DROP `ruta_escaneo`;");

?>