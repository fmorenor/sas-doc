<?php
include "conexion.php";

$sql = mysqli_query($link, "ALTER TABLE `documentos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `documentos`
CHANGE `id_documento` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
CHANGE `numero_documento` `numero_documento` VARCHAR(64) CHARACTER SET binary NOT NULL DEFAULT '',
CHANGE `expediente` `expediente` VARCHAR(255) CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `remitente` `remitente` VARCHAR( 128 ) CHARACTER SET BINARY NULL DEFAULT NULL,
CHANGE `asunto` `asunto` MEDIUMTEXT CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `anexos` `anexos` TEXT CHARACTER SET binary NOT NULL,
CHANGE `numero_documento_respuesta` `numero_documento_respuesta` VARCHAR(64) CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `destinatario_documento_enviado` `destinatario_documento_enviado` VARCHAR(255) CHARACTER SET binary NOT NULL DEFAULT '',
CHANGE `numero_documento_atendido` `numero_documento_atendido` VARCHAR(64) CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `nota` `nota` TEXT CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `historico_nota` `historico_nota` TEXT CHARACTER SET binary NOT NULL,
CHANGE `ruta_escaneo` `ruta_escaneo` VARCHAR(255) CHARACTER SET binary NULL DEFAULT NULL,
CHANGE `notificados` `notificados` VARCHAR(255) CHARACTER SET binary NOT NULL DEFAULT '';");

$sql = mysqli_query($link, "ALTER TABLE `documentos`
CHANGE `numero_documento` `numero_documento` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `expediente` `expediente` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `remitente` `remitente` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `asunto` `asunto` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `anexos` `anexos` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `numero_documento_respuesta` `numero_documento_respuesta` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `destinatario_documento_enviado` `destinatario_documento_enviado` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `numero_documento_atendido` `numero_documento_atendido` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `nota` `nota` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `historico_nota` `historico_nota` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `ruta_escaneo` `ruta_escaneo` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `notificados` `notificados` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';");


$sql = mysqli_query($link, "ALTER TABLE `catalogo_estatus` ENGINE = InnoDB");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_estatus` CHANGE `id_estatus` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ");

$sql = mysqli_query($link, "ALTER TABLE `bitacora` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `bitacora`
CHANGE `id_bitacora` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,                    
CHANGE `usuario` `usuario` VARCHAR( 32 ) CHARACTER SET BINARY NOT NULL DEFAULT '',
CHANGE `numero_documento` `numero_documento` VARCHAR( 255 ) CHARACTER SET BINARY NOT NULL DEFAULT '',
CHANGE `usuario_modificado` `usuario_modificado` VARCHAR( 32 ) CHARACTER SET BINARY NOT NULL DEFAULT '',
CHANGE `evento` `evento` VARCHAR( 64 ) CHARACTER SET BINARY NOT NULL DEFAULT '';");

$sql = mysqli_query($link, "ALTER TABLE `bitacora`
CHANGE `usuario` `usuario` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `numero_documento` `numero_documento` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `usuario_modificado` `usuario_modificado` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `evento` `evento` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_grupos` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_grupos` CHANGE `nombre_grupo` `nombre_grupo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_privilegios` ENGINE = InnoDB");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_privilegios` CHANGE `id_privilegios` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_tipo_documento` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_tipo_documento`
                    CHANGE `id_tipo_documento` `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
                    CHANGE `nombre` `nombre` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_usuarios` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_usuarios`
CHANGE `user` `user` VARCHAR( 32 ) CHARACTER SET BINARY NOT NULL DEFAULT '',
CHANGE `nombre` `nombre` VARCHAR( 255 ) CHARACTER SET BINARY NOT NULL DEFAULT ''");

$sql = mysqli_query($link, "ALTER TABLE `catalogo_usuarios`
CHANGE `user` `user` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `nombre` `nombre` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");

$sql = mysqli_query($link, "ALTER TABLE `documento_adjuntos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `documento_adjuntos` CHANGE `path` `path` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

$sql = mysqli_query($link, "ALTER TABLE `documento_notas` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$sql = mysqli_query($link, "ALTER TABLE `documento_notas` CHANGE `nota` `nota` TEXT CHARACTER SET BINARY NOT NULL");

$sql = mysqli_query($link, "ALTER TABLE `documento_notas` CHANGE `nota` `nota` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

$sql = mysqli_query($link, "ALTER TABLE `documento_turnado_a` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

?>