<?php
	include_once "../conexion.php";
	
	echo '<select id="tipo_documento">';                
	$sql = mysqli_query($link, "SELECT * FROM catalogo_tipo_documento; ");		
	while($row = mysqli_fetch_array($sql)){
		$selected = ($_REQUEST['id_tipo_documento'] == $row['id']) ? "selected" : "";
		echo "	<option value='".$row['id']."' data-vigencia='".$row['vigencia_predeterminada']."' ".$selected.">".$row['nombre']."</option>";
	}
	echo '</select>';

?>