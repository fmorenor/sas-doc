<?php session_start(); ?>
<?php
    header("Content-type: application/json");
    include_once "../conexion.php";
    include_once "../mcrypt.php";
       
    $dataArray = array();
    $rowArray = array();
    $total = 0;
    
    ///// BOF Ldap	
	include('../../../login/model/Ldap.php');
    
	$login = $_SESSION['usuario'];
	$password = decrypt($_SESSION['cryptkey'],$key,$iv,$bit_check);        
	
	$AUT = new ldap();	
	$ldap_config = array();
	$ldap_config['base_dn'] = 'dc=conafor,dc=gob,dc=mx';
	$ldap_config['domain_controllers'] =array('10.0.0.7');
	$ldap_config['ad_username'] = $login;
	$ldap_config['ad_password'] = $password;
	$ldap_config['account_preffix'] = 'CONAFOR';
	
	$AUT->initialize( $ldap_config );
	if( $AUT->authenticate( $login ,utf8_encode( $password ) ) )
	{
		// Datos del usuario
        $r = $AUT->user_search($_REQUEST['term']);
        $count_r = count($r) - 1;
       
        for($i = 0; $i < $count_r; $i++){
            $rowArray['id'] = $r[$i]['samaccountname'][0];
            $rowArray['text'] = $r[$i]['displayname'][0]; //utf8_decode($r[$i]['displayname'][0]);
            $rowArray['user'] = $r[$i]['samaccountname'][0];
            $rowArray['id_grupo'] = null;
            array_push($dataArray, $rowArray);            
            $total++;
        }
        
    }
    ///// EOF Ldap
    
    // BOF Datos de usuarios registrados
    $sql = mysqli_query($link, "SELECT * FROM catalogo_usuarios
                                WHERE activo = 1
									AND (user = '".$_REQUEST['term']."'
                                    OR nombre like '%".$_REQUEST['term']."%')
                                ORDER BY nombre; ");
    
    while($row = mysqli_fetch_array($sql)){
        
        $key = (search_array($dataArray, $row['user']) === false) ? false : search_array($dataArray, $row['user']);
        //echo $key;
        
        if($key >= 0){
            // Requeridos
            $dataArray[$key]['id'] = $row['id'];
            //$dataArray[$key]['text'] = $row['nombre'];
            
            // Opcionales
            //$dataArray[$key]['user'] = $row['user'];
            $dataArray[$key]['id_grupo'] = $row['id_grupo'];
        }
    }
    
    function search_array ( array $array, $term ){
		foreach ( $array as $key => $value ){
			if ( stripos( $value['user'], $term ) !== false )
				return $key;
        }
	
		return false;
	}
    // EOF Datos de usuarios registrados
    
   
    // Preparar los datos   
    $jsonData['total'] = $total;
    $jsonData['more'] = true;
    $jsonData['results'] = $dataArray;
    
    echo json_encode($jsonData);

?>