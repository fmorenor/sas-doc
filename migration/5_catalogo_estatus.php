<?php
include "conexion.php";

$sql = mysqli_query($link, "UPDATE catalogo_estatus SET estatus = 'Seguimiento' WHERE id_estatus = 5; ");   

?>