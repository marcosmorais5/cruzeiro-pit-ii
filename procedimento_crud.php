<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");


if(!class_exists("Procedimento")) require_once("class/Procedimento.php");
if(!class_exists("Usuario")) require_once("class/Usuario.php");


$json = json_decode(file_get_contents("php://input"));




/* CLASS METHODS DEFINITION 
* $tmp_obj = new Procedimento();
* $tmp_obj->setIdprocedimento($idprocedimento);
* $tmp_obj->setProcedimento($procedimento);
* $tmp_obj = null;
* $tmp_obj->apply("i"); // Insert
* $tmp_obj->apply("u"); // Update
* $tmp_obj->apply("d"); // Delete
* * * * * * * * * * * * * */

$obj = new Procedimento();


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
	


		$obj->setIdprocedimento((int)$_GET['idprocedimento']);
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
	
	
	$obj->setProcedimento($json->procedimento);
	
	
	if(strlen($json->procedimento) <= 0){
		
		$arr_output['response_code'] = 406;
		if(strlen($json->procedimento) <= 0){
			$arr_output['response_msg'] = "Para cadastrar um procedimento, é necessário fornecer no mínimo 2 caracteres!";
		}
		
	}else{
		
		$ret = $obj->inserir();
	
		$arr_output['json'] = $obj;
		
		if($ret){
			
			$arr_output['obj'] = $obj;
			$arr_output['response_code'] = 201;
			$arr_output['response_msg'] = "O novo registro foi criado com sucesso!<br>Você pode ver este registro na sua fila de procedimentos.";
			
		}else{
			
			$arr_output['response_code'] = 406;
			$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
			
		}
	
	}
	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	
	$obj->setIdprocedimento($json->idprocedimento);
	$search_obj = $obj->getOne();
	
	
	if($search_obj != null){
			
			
		$search_obj->setProcedimento($json->procedimento);

	
		$ret = $search_obj->atualizar();
						
		if($ret){
			
			$arr_output['obj'] = $search_obj;
			$arr_output['response_code'] = 200;
			$arr_output['response_msg'] = "O registro foi atualizado com sucesso!";
			
		}else{
			
			$arr_output['response_code'] = 406;
			$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
			
		}
			
		
	}else{
		
		$arr_output['response_code'] = 404;
		$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
		
	}
	
	
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
	
	
	
}


echo(json_encode($arr_output));

/*

idusuario: <input type='text' id='idusuario' class='to-save'><br>
loginusuario: <input type='text' id='loginusuario' class='to-save'><br>
nomeusuario: <input type='text' id='nomeusuario' class='to-save'><br>
senhausuario: <input type='text' id='senhausuario' class='to-save'><br>
ativo: <input type='text' id='ativo' class='to-save'><br>
datecreated: <input type='text' id='datecreated' class='to-save'><br>
dateupdated: <input type='text' id='dateupdated' class='to-save'><br>
grupo: <input type='text' id='grupo' class='to-save'><br>


*/