<?php
	header("Content-type: application/json");
	include("../../app/model/conexion.php");
	
	///// BOF Ldap	
	include('Ldap.php');
	
	$login = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
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
		$r = $AUT->user_info($login);
		
		print_r($r);
		
		
		$nombre_completo = utf8_decode($r[0]['displayname'][0]);
		
		// Grupo del Usuario con prioridad 1-Gerencia, 2-Coordinación o 3-La primera que aparezca
		$g = $AUT->user_groups($login);
		$g= array_reverse($g);
		$keys = search_array($g, 'Gerencia');
		if(!$keys){
			$keys = (!search_array($g, 'Coordinaci')) ? 0 : search_array($g, 'Coordinaci');
		}		
		$grupo = $g[$keys];
		
		@session_start();
		$_SESSION['nombre_completo'] = $nombre_completo;
		$_SESSION['grupo'] = $grupo;
		$_SESSION['appSessionSASDOC'] = "true";
	} else {
		return false;
	}
	
	function search_array ( array $array, $term ){
		foreach ( $array as $key => $value )
			if ( stripos( $value, $term ) !== false )
				return $key;
	
		return false;
	}
	
	///// EOF Ldap	
	if($nombre_completo != null){
		$jsonData = @array('usuario'=>$_REQUEST['username'],
		'nombre_completo'=>$nombre_completo,
		'grupo'=>$grupo);
	} 
	
	echo json_encode($jsonData);	
?>