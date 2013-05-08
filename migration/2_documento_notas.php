<?php
include "conexion.php";

// Eliminar tabla vieja
$sql1 = mysqli_query($link, "DROP TABLE documento_notas");

// Estructura de la tabla
$sql1 = mysqli_query($link, "CREATE TABLE IF NOT EXISTS `documento_notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nota` text NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_documento` (`id_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

// Datos de la tabla (Notas sin histórico)

$sql2 = mysqli_query($link, "INSERT INTO documento_notas
(id_documento, id_usuario, nota, fecha)
SELECT
  d.id_documento,
  d.id_asignado_por,
  d.nota,
  d.fecha_recepcion
FROM documentos d
WHERE nota != ''
  AND historico_nota = '';");


$sql = mysqli_query($link, "SELECT
                            d.id_documento,
                            d.id_asignado_por,
                          --  d.nota,
                            d.historico_nota,
                            d.fecha_recepcion
                          
                            FROM documentos d
                            
                            WHERE nota != ''
                              AND historico_nota != '';");

    echo "<table border='1s'>";                              
                              
  while($row = mysqli_fetch_array($sql)){
    
    
    
    //echo $row['id_documento']."<br />";
    //echo $row['id_asignado_por']."<br />";
    //echo utf8_decode($row['historico_nota'])."<br />";
    //echo $row['fecha_recepcion']."<br />";
    
    
    // BOF Hacer de la nota histórico un arreglo
    $historico_nota = $row['historico_nota'];//utf8_decode($row['historico_nota']);
    $historico_nota = str_replace("\n","",$historico_nota);
    
    $arr = explode("\r",$historico_nota);    
    //foreach ($arr as &$valor) {
    //    if($valor == "\n") $valor = null;          
    //}
    //unset($valor);    
    $new_array = array_values(array_filter($arr));
    $new_array_count = count($new_array);    
    //print_r($new_array);    
    // EOF
    
    $vueltas = ($new_array_count / 3)+1;
    
    for($v = 0; $v < $vueltas; $v++){    
        // Fix Array
        for($i = 0; $i < $new_array_count; $i++){
            $res = ($i % 3);
            if($res == 0 && $i == 3){            
                $date = $new_array[$i+1];
                $date_format = 'Y-m-d';
                $input = substr($date,0,10);
                
                $input = trim($input);
                $time = strtotime($input);
                
                $is_valid = date($date_format, $time) == $input;
                
                if(!$is_valid){
                    $new_array[$i-1] = $new_array[$i-1]."*".$new_array[$i];
                    $new_array[$i] = null;
                    $new_array = array_values(array_filter($new_array)); 
                }
            }        
        }
        $new_array_count = count($new_array);
        
        // Fix Array
        for($i = 0; $i < $new_array_count; $i++){
            $res = ($i % 3);
            if($res == 0 && $i >= 3){            
                $date = $new_array[$i+1];
                $date_format = 'Y-m-d';
                $input = substr($date,0,10);
                
                $input = trim($input);
                $time = strtotime($input);
                
                $is_valid = date($date_format, $time) == $input;
                
                if(!$is_valid){
                    $new_array[$i-1] = $new_array[$i-1]."*".$new_array[$i];
                    $new_array[$i] = null;
                    $new_array = array_values(array_filter($new_array)); 
                }
            }        
        }
        $new_array_count = count($new_array);    
    }
    
    
    
    for($i = 0; $i < $new_array_count; $i++){
        $res = ($i % 3);
        if($res == 0){
            
            if($new_array[$i+2] != ""){
                echo "<tr>";                
                
                $usuario = "";
                $sql_user = mysqli_query($link, "SELECT id_usuario FROM catalogo_usuarios WHERE nombre LIKE '%".utf8_encode($new_array[$i])."%'; ");
                while($row2 = mysqli_fetch_array($sql_user)){
                    $usuario = $row2['id_usuario'];
                }
                $usuario = ($usuario == "") ? $row['id_asignado_por'] : $usuario;
                
                
                
                //echo "<td>".$new_array[$i+2]."</td>"; // Nota
                $pos = stripos($new_array[$i+2], "*");
                if($pos !== FALSE){
                    $lon = strlen($new_array[$i+2]) - 1;
                    
                    if($pos != $lon){
                        $nota = substr($new_array[$i+2],$pos + 1);
                    } else {
                        $nota = substr($new_array[$i+2],0,$pos);
                    }
                    
                } else {
                    $nota = $new_array[$i+2];
                }                
                $nota = ($nota == " ") ? $new_array[$i+2] : $nota;   
                //echo "<td>".$pos."-".$lon."</td>";
                
                //echo "<td>".$row['id_asignado_por']."</td>";
                //echo "<td>".$new_array[$i]."</td>"; // Usuario
                echo "<td>".$row['id_documento']."</td>";
                echo "<td>".$usuario."</td>";
                echo "<td>".$nota."</td>"; // Nota perfeccionada                
                echo "<td>".$new_array[$i+1]."</td>"; // Fecha                
                //echo "<td>".$historico_nota."</td>";
                //echo "<td>".$row['fecha_recepcion']."</td>";
                
                $insert = mysqli_query($link, "INSERT INTO documento_notas
                                                (id_documento, id_usuario, nota, fecha)
                                                VALUES (".$row['id_documento'].",
                                                '".$usuario."',
                                                '".$nota."',
                                                '".$new_array[$i+1]."');"
                                                );
                
                echo "</tr>";
            }
        }
    }
    
   
  }
  echo "</table>";

?>