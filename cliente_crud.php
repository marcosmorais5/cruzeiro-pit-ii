<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");


if(!class_exists("Cliente")) require_once("class/Cliente.php");
if(!class_exists("Usuario")) require_once("class/Usuario.php");


$json = json_decode(file_get_contents("php://input"));




$obj = new Cliente();



$arr_output = array();
$arr_output['response_code'] = 200;
$arr_output['response_msg'] = "Registro encontrado";
$arr_output['method'] = $_SERVER['REQUEST_METHOD'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	
	if($_GET['fila'] == "sim"){
		
		$params = array();
		
		if(isset($_GET['nome'])) $params['nome'] = $_GET['nome'];
		
		$fila = $obj->getAll($params);
		
		foreach($fila as &$registro){
			
			/* ADICIONA O ORÇAMENTO NA LISTA DE CLIENTE */
			//$registro->getOrcamentos();
			
		}
		$arr_output['fila'] = $fila;
		
	}else{
	


		$obj->setIdcliente((int)$_GET['idcliente']);
		$um_objeto = $obj->getOne();
		
		$arr_output['method'] = $_SERVER['REQUEST_METHOD'];
		
		if($um_objeto == null){
			
			$arr_output['response_code'] = 404;
			$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
			
		}else{
			
			$arr_output['obj'] = $um_objeto;

		}
	
	}
	
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	
	$obj->setIdcliente($json->idcliente);
	$obj->setNome($json->nome);
	$obj->setDatanascimento(Util::genFromDateToSQLWhole($json->datanascimento));
	$obj->setCpf($json->cpf);
	$obj->setEmail($json->email);
	$obj->setTelefone($json->telefone);
	$obj->setCreatedby($_SESSION['cod_usuario']);
	
	
	
	$PODE_CRIAR_USUARIO = Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) != "CAIXA";
	
	if(!$PODE_CRIAR_USUARIO){
		
		$arr_output['response_code'] = 403;
		$arr_output['response_msg'] = "Você não tem autorização para atribuir O novo um usuário ao grupo 'ADMINISTRADOR_MESTRE'!";
		
	}else if(strlen($json->nome) <= 0){
		
		$arr_output['response_code'] = 400;
		if(strlen($json->nome) <= 0){
			$arr_output['response_msg'] = "Para cadastrar um cliente, é necessário no mínimo fornecer o nome!";
		}
		
	}else{
		
		$ret = $obj->inserir();
	
		$arr_output['json'] = $obj;
		
		if($ret){
			
			$arr_output['obj'] = $obj;
			$arr_output['response_code'] = 201;
			$arr_output['response_msg'] = "O novo registro foi criado com sucesso!<br>Você pode ver este registro na sua fila de procedimentos.";
			
		}else{
			
			$arr_output['response_code'] = 400;
			$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
			
		}
	
	}
	

	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	
	
	$obj->setIdcliente($json->idcliente);
	$search_obj = $obj->getOne();
	
	
	if($search_obj != null){
			
		if(
			Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) != "CAIXA"
		){
	
			$search_obj->setIdcliente($json->idcliente);
			$search_obj->setNome($json->nome);
			$search_obj->setDatanascimento(Util::genFromDateToSQLWhole($json->datanascimento));
			$search_obj->setCpf($json->cpf);
			$search_obj->setEmail($json->email);
			$search_obj->setTelefone($json->telefone);
			
			/* NECESSÁRIO PARA REGISTROS ANTIGOS */
			$search_obj->setCreatedby((int)$search_obj->getCreatedby());
	
			$ret = $search_obj->atualizar();
							
			if($ret){
				
				$arr_output['obj'] = $search_obj;
				$arr_output['response_code'] = 200;
				$arr_output['response_msg'] = "O novo registro foi atualizado com sucesso!";
				
			}else{
				
				$arr_output['response_code'] = 400;
				$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
				
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
