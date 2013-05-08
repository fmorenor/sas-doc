<?php
include "conexion.php";

// Eliminar tabla vieja
$sql1 = mysqli_query($link, "DROP TABLE catalogo_grupos");
echo "Drop - ".mysqli_error($link);

// Estructura de tabla para la tabla `catalogo_grupos1`
$sql2 = mysqli_query($link, "CREATE TABLE IF NOT EXISTS `catalogo_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_grupo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;");
echo "Create - ".mysqli_error($link);


// Volcado de datos para la tabla `catalogo_grupos`
$sql3 = mysqli_query($link, "INSERT INTO `catalogo_grupos` (`id`, `nombre_grupo`) VALUES
(1, '".utf8_decode('Coordinacion General de Planeación e Información')."'),
(2, '".utf8_decode('Coordinacion General de Administración')."'),
(3, '".utf8_decode('Gerencia de Información Forestal')."'),
(4, '".utf8_decode('Asuntos Jurídicos')."'),
(5, '".utf8_decode('Coordinacion General de Producción y Productividad')."'),
(6, '".utf8_decode('Coordinacion General de Educación y Desarrollo Tecnológico')."'),
(7, 'PRUEBA'),
(8, '".utf8_decode('Coordinacion General de Conservación y Restauración')."'),
(9, '".utf8_decode('Coordinacion General de Gerencias Estatales')."'),
(10, '".utf8_decode('Asuntos Internacionales y Fomento Financiero')."'),
(11, '".utf8_decode('Dirección General')."')
");

echo "Insert - ".mysqli_error($link);

// Si lo que se quiere es modificar la tabla existente
//ALTER TABLE `catalogo_grupos`
//  DROP `siglas`,
//  DROP `id_tipo_documento`,
//  DROP `activo`;
//  
//  
//  ALTER TABLE `catalogo_grupos` CHANGE `id_grupo` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
//CHANGE `nombre` `nombre_grupo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''

?>