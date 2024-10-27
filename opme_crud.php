<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");


if(!class_exists("Opme")) require_once("class/Opme.php");
if(!class_exists("Usuario")) require_once("class/Usuario.php");


$json = json_decode(file_get_contents("php://input"));


$obj = new Opme();


$arr_output = array();
$arr_output['response_code'] = 200;
$arr_output['response_msg'] = "Registro encontrado";
$arr_output['method'] = $_SERVER['REQUEST_METHOD'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	
	if($_GET['fila'] == "sim"){
		
		$params = array();
		
		
		$fila = $obj->getAll($params);
		
		$arr_output['fila'] = $fila;
		
	}else{
	


		$obj->setIdopme((int)$_GET['idopme']);
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
	
	
	$obj->setOpme($json->opme);
	
	
	if(strlen($json->opme) <= 0){
		
		$arr_output['response_code'] = 400;
		if(strlen($json->opme) <= 0){
			$arr_output['response_msg'] = "Para cadastrar um OPME, é necessário fornecer no mínimo 2 caracteres!";
		}
		
	}else{
		
		$ret = $obj->inserir();
	
		$arr_output['json'] = $obj;
		
		if($ret){
			
			$arr_output['obj'] = $obj;
			$arr_output['response_code'] = 201;
			$arr_output['response_msg'] = "O novo registro foi criado com sucesso!<br>Você pode ver este registro na sua fila de OPME.";
			
		}else{
			
			$arr_output['response_code'] = 400;
			$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
			
		}
	
	}
	

	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	
	
	
	$obj->setIdopme($json->idopme);
	$search_obj = $obj->getOne();
	
	
	if($search_obj != null){
			
			
		$search_obj->setOpme($json->opme);

	
		$ret = $search_obj->atualizar();
						
		if($ret){
			
			$arr_output['obj'] = $search_obj;
			$arr_output['response_code'] = 200;
			$arr_output['response_msg'] = "O registro foi atualizado com sucesso!";
			
		}else{
			
			$arr_output['response_code'] = 400;
			$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
			
		}
			
		
	}else{
		
		$arr_output['response_code'] = 404;
		$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
		
	}
	
	
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
	
	
	
}


echo(json_encode($arr_output));
