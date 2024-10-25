<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");


if(!class_exists("Usuario")) require_once("class/Usuario.php");


$json = json_decode(file_get_contents("php://input"));




$obj = new Usuario();



$arr_output = array();
$arr_output['response_code'] = 200;
$arr_output['response_msg'] = "Registro encontrado";

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	
	if($_GET['fila'] == "sim"){
		
		$fila = $obj->getAll();
		
		$arr_output['fila'] = $fila;
		
	}else{
	
		$obj->setIdusuario((int)$_GET['idusuario']);
		$um_objeto = $obj->getOne();
		
		$arr_output['method'] = $_SERVER['REQUEST_METHOD'];
		
		if($um_objeto == null){
			
			$arr_output['response_code'] = 404;
			$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
			
		}else{
			
			if(
				Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR" ||
				Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR_MESTRE"
			){
				
				$arr_output['obj'] = $um_objeto;
				
			}else{
				
				$arr_output['response_code'] = 403;
				$arr_output['response_msg'] = "Você não tem permissão para ver os dados deste registro. Somente o administrador ou o dono do registro podem realizar esta ação!";
				
			}
			
			
		}
	
	}
	
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	$obj->setLoginusuario($json->loginusuario);
	$obj->setNomeusuario($json->nomeusuario);
	$obj->setSenhausuario(sha1($json->senhausuario));
	$obj->setAtivo($json->ativo);
	$obj->setGrupo($json->grupo);

	
	/** Verificando a permissão do usuário atual */
	$PODE_CRIAR_USUARIO = Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR_MESTRE" || Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR";
	
	if(!$PODE_CRIAR_USUARIO){
		
		$arr_output['response_code'] = 403;
		$arr_output['response_msg'] = "Você não tem autorização para atribuir O novo um usuário ao grupo 'ADMINISTRADOR_MESTRE'!";
		
	}else{
		
		if(!$PODE_CRIAR_USUARIO){
			
			$arr_output['response_code'] = 403;
			$arr_output['response_msg'] = "Permissão negada! Você não tem permissão de criar um usuário!";
			
		}else{
			
			$ret = $obj->inserir();
	
			$arr_output['json'] = $json;
			
			if($ret){
				
				$arr_output['obj'] = $obj;
				$arr_output['response_code'] = 201;
				$arr_output['response_msg'] = "O novo registro foi criado com sucesso!<br>Você pode ver este registro na sua fila de procedimentos.";
				
			}else{
				
				$arr_output['response_code'] = 406;
				$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
				
			}
			
		}

		
	}
	

	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	

	$obj->setIdusuario($json->idusuario);
	$search_obj = $obj->getOne();
	
	
	if($search_obj != null){
			
			if(
				Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR" ||
				Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "ADMINISTRADOR_MESTRE"
			){

				$arr_output['json'] = $json;
				
				
				$search_obj->setIdusuario($json->idusuario);
				$search_obj->setLoginusuario($json->loginusuario);
				$search_obj->setNomeusuario($json->nomeusuario);
				$search_obj->setAtivo($json->ativo);
				$search_obj->setGrupo($json->grupo);
				
				if(isset($json->senhausuarioreinicializar)){
					
					if(trim($json->senhausuarioreinicializar) != ""){
						$search_obj->setSenhausuario(sha1($json->senhausuarioreinicializar));
					}
					
				}
				
				if(Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) != "ADMINISTRADOR_MESTRE" && $json->grupo == "ADMINISTRADOR_MESTRE"){
		
					$arr_output['response_code'] = 403;
					$arr_output['response_msg'] = "Você não tem autorização para atribuir O novo um usuário ao grupo 'ADMINISTRADOR_MESTRE'!";
					
				}else{
					
					$ret = $search_obj->atualizar();
		
					if($ret){
						
						$arr_output['obj'] = $search_obj;
						$arr_output['response_code'] = 200;
						$arr_output['response_msg'] = "Registro atualizado com sucesso!";
						
					}else{
						
						$arr_output['response_code'] = 406;
						$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
						
					}
					
				}

				
			}else{
				
				$arr_output['response_code'] = 403;
				$arr_output['response_msg'] = "Você não tem permissão para atualizar este registro!";
				
			}
			
			
		
		
	}else{
		
		$arr_output['response_code'] = 404;
		$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
		
	}
	
	
	
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
	
	
	
}


echo(json_encode($arr_output));

