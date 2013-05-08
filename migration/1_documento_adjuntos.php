<?php
include "conexion.php";

// Eliminar tabla vieja
$sql1 = mysqli_query($link, "DROP TABLE documento_adjuntos");

//Estructura de la tabla
$sql1 = mysqli_query($link, "CREATE TABLE IF NOT EXISTS `documento_adjuntos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_documento` (`id_documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

//Datos de la tabla
$sql2 = mysqli_query($link, "INSERT INTO documento_adjuntos (id_documento, path)
SELECT documentos.id_documento, documentos.ruta_escaneo FROM documentos;");

?>