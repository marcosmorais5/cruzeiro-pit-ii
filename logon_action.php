<?php
session_start();

if(!class_exists('Usuario')) require_once('class/Usuario.php');

function isValidJSON($str) {
   json_decode($str);
   return json_last_error() == JSON_ERROR_NONE;
}


$json_params = file_get_contents("php://input");

$output = array();
$output['status_code'] = "NOK";

if (strlen($json_params) > 0 && isValidJSON($json_params)){
	
	/* SENDING JSON CONTENT */
	header("Content-type: application/json; charset=utf-8");
	
	$json = json_decode($json_params);
	
	$tmp_obj = new Usuario();
	$tmp_obj->setLoginusuario($json->user);
	$user = $tmp_obj->fazerLogin();
	
	if($user != null){
		
		if($user->getSenhausuario() == sha1($json->password)){
			
			if($user->getAtivo() == "N"){
				
				$output['status_msg'] = "O seu usuário foi encontrado no sistema, mas ele está desativado.";
				
			}else{
				
				$output['status_code'] = "OK";
				
				$_SESSION['cod_usuario'] = $user->getIdusuario();
				$_SESSION['email_usuario'] = $user->getLoginusuario();
				$_SESSION['nome_usuario'] = $user->getNomeusuario();
				$_SESSION['grupo'] = $user->getGrupo();
				
			}
			
		}
		
	}
	
}

echo(json_encode($output));
?>