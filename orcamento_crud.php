<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");


if(!class_exists("Orcamento")) require_once("class/Orcamento.php");
if(!class_exists("Status")) require_once("class/Status.php");
if(!class_exists("Usuario")) require_once("class/Usuario.php");
if(!class_exists("Grupo")) require_once("class/Grupo.php");



$json = json_decode(file_get_contents("php://input"));

/* HELPER */
$obj = new Orcamento();

// 403 = forbiden
// 201 = created
// 500 = erro
// 406 = not acceptable 

$arr_output = array();
$arr_output['response_code'] = 200;
$arr_output['response_msg'] = "Sem errors.";
$arr_output['method'] = $_SERVER['REQUEST_METHOD'];


/* VERIFICA SE É ADMIN OU MESTRE, USADA EM VÁRIAS PARTES DO CÓDIGO 
* */
$IS_ADMIN_OU_MESTRE = $_SESSION['grupo'] == Grupo::$ADMINISTRADOR || $_SESSION['grupo'] == Grupo::$ADMINISTRADOR_MESTRE;
$COD_USUARIO = (int)$_SESSION['cod_usuario'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if($_GET['fila_proximos'] == "sim"){
		
		$todos = $obj->getProcesimentosAcontecerProximosDias(15);
		
		$arr_output['data_hoje'] = date("d/m/Y") ." 00:00";
		$arr_output['fila_proximos'] = $todos;
		
	
	}else if($_GET['orcamentos'] == "sim"){
		
		/* OUTPUT COM FILTRO */
		$filtro = array();
		
		/* TODOS OS REGISTROS */
		$todos = $obj->getAll($json);
		
		/* SÓ RETORNA A LISTA DE ORÇAMENTOS SE FOR O DONO OU O ADMINISTRADOR */
		foreach($todos as $orcamento){
			if($orcamento->getCraetedby() == $COD_USUARIO || $IS_ADMIN_OU_MESTRE){
				
				array_push($filtro, $orcamento);
				
			}
		}
		
		
		$arr_output['orcamentos'] = $filtro;
	
	/* RETORNA A FILA DO CAIXA, SÓ O CAIXA CONSEGUE VER ESTA FILA */
	}else if($_GET['filacaixa'] == "sim"){
		
		$filacaixa = $obj->getFilaCaixa();
		
		$arr_output['filacaixa'] = $filacaixa;
		
	/* RETRONA A FILA DA PESSOA */
	}else if($_GET['fila'] == "sim"){
		
		
		$json = array(
			"idstatus" => $_GET['idstatus']
		);
		
		
		$fila = $obj->getFila($json);
		
		$arr_output['fila'] = $fila;
	
	/* BUSCANDO SOMENTE UM REGISTRO */
	}else{
		
		$obj->setCod((int)$_GET['cod']);
		$um_objeto = $obj->getOne();
		
		
		if($um_objeto == null){
			
			$arr_output['response_code'] = 404;
			$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
			
		}else{
			
			if(
				$COD_USUARIO == $um_objeto->getCraetedby() ||
				$IS_ADMIN_OU_MESTRE
			){
				
				//$arr_output['readonly'] = $um_objeto->getCraetedby() != $_SESSION['cod_usuario'] && $_SESSION['grupo'] != "ADMINISTRADOR" && $_SESSION['grupo'] != "ADMINISTRADOR_MESTRE";
				
				$arr_output['obj'] = $um_objeto;
				
			}else{
				
				$arr_output['response_code'] = 403;
				$arr_output['response_msg'] = "Você não tem permissão para ver os dados deste registro. Somente o administrador ou o dono do registro podem realizar esta ação!";
				
			}
			
		}
	
	}
	
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	/* SETA TODOS OS VALORES */
	$obj->setData(Util::genFromDateToSQLWhole($json->data));
	$obj->setIdcliente($json->idcliente);
	$obj->setValoroperacao(Util::convertRealFloat($json->valoroperacao));
	$obj->setIdtipopagamento((int)$json->idtipopagamento);
	$obj->setIdservico((int)$json->idservico);
	$obj->setIdprocedimento((int)$json->idprocedimento);
	$obj->setDatarealizacao(Util::genFromDateToSQLWhole($json->datarealizacao));
	$obj->setIdmedico((int)$json->idmedico);
	$obj->setIdlateralidade((int)$json->idlateralidade);
	$obj->setIdopme((int)$json->idopme);
	$obj->setIdstatus((int)$json->idstatus);
	$obj->setDatecreated(Util::genFromDateToSQLWhole($json->datecreated));
	$obj->setDateupdate(Util::genFromDateToSQLWhole($json->dateupdate));
	$obj->setDatedeleted(Util::genFromDateToSQLWhole($json->datedeleted));
	$obj->setCraetedby($COD_USUARIO);
	$obj->setObs($json->obs);
	
	$arr_existe = $obj->jaExisteCadastrado();
	
	if(sizeof($arr_existe) > 0){
		
		$arr_output['response_code'] = 406;
		$arr_output['response_msg'] = "Um outro orientador já cadastrou um mesmo orçamento com informações idênticas de:<br><ul><li>Cliente</li><li>Serviço</li><li>Procedimento</li><li>Lateralidade</li></ul><strong style='color: #FF0000'>Este registro não será salvo</strong>.";
		
	}else if(
	
		(int)$json->idcliente <= 0 ||
		(int)$json->idprocedimento <= 0 ||
		(int)$json->idmedico <= 0 ||
		(int)$json->idlateralidade <= 0
		
	){
		
		$arr_output['response_code'] = 406;
		$arr_output['response_msg'] = "Para criar um orçamento é necessário no mínimo fornecer os campos de:<br><ul><li>Cliente</li><li>Procedimento</li><li>Médico</li><li>Lateralidade</li></ul>";
		
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
	
	
	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	
	
	
	
	$obj->setCod($json->cod);
	$search_obj = $obj->getOne();
	
	
	if($search_obj == null){
		
		$arr_output['response_code'] = 404;
		$arr_output['response_msg'] = "Este orçamento existe, mas ele não está disponível para o caixa. Isso significa que o caixa já confirmou o recebimento do valor anteriormente.";
		
	}else if($_GET['orcamento_tudo_ok'] == "confirma"){
		
		
		
		if( $search_obj->requerPagamento() && !$search_obj->isCaixaOK() ){
			
			$arr_output['response_code'] = 406;
			$arr_output['response_msg'] = "Ainda há pendência do caixa para este procedimento.<br>Verifique se o caixa recebeu os valores e tente novamente!";
			
		}else{
			
			$search_obj->setTudook("Y");
			$search_obj->setTudookpor($COD_USUARIO);
			
			$ret = $search_obj->atualizar();
			
			if($ret){
				
				$arr_output['obj'] = $search_obj;
				$arr_output['response_code'] = 200;
				$arr_output['response_msg'] = "Você confirmou que está tudo OK para este procedimento.";
				
			}else{
				
				$arr_output['response_code'] = 406;
				$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
				
			}
			
			
		}		

		

		
	
	/* O CAIXA MANDOU CONFIRMAR O PAGAMENTO */
	}else if(isset($_GET['caixa_pagamento'])){
		

		
		/* O OBJETO EXISTE PARA O CAIXA */
		if($search_obj != null){
			
			$arr_output['obj'] = $json;
			
			if(!$search_obj->isCaixaOK()){
				
				$search_obj->setCaixaok("Y");
				$search_obj->setCaixaokby($COD_USUARIO);
				$search_obj->setCaixaokdatetime(date("Y-m-d H:i:s"));
				
				$ret = $search_obj->atualizar();
			
				if($ret){
					
					$arr_output['obj'] = $search_obj;
					$arr_output['response_code'] = 200;
					$arr_output['response_msg'] = "Confirmado o recebimento do valor pelo caixa. Este registro sumirá da sua fila em segundos.";
					
				}else{
					
					$arr_output['response_code'] = 406;
					$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
					
				}
				
			}else{
				
				$arr_output['response_code'] = 404;
				$arr_output['response_msg'] = "Este orçamento existe, mas ele não está disponível para o caixa. Isso significa que o caixa já confirmou o recebimento do valor anteriormente.";
				
			}
			
			
			
			
			
			
		}else{
			
			$arr_output['response_code'] = 404;
			$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
			
		}
		
		
	}else{
		
		//if($search_obj != null){
			
			if($search_obj->isCaixaOK()){
				
				$arr_output['response_code'] = 403;
				$arr_output['response_msg'] = "O caixa já confirmou ter recebido o valor deste procedimento, portanto não é possível realizar alterações.";
				
			}else if(
				$COD_USUARIO == $search_obj->getCraetedby() || 	$IS_ADMIN_OU_MESTRE
			){
				
				/* SETANDO A DATA DE FECHAMENTO BASEADA NO STATUS FORNECIDO */
				$search_obj->setClosedByStatus((int)$json->idstatus);
				
				$search_obj->setData(Util::genFromDateToSQLWhole($json->data));
				$search_obj->setIdcliente($json->idcliente);
				$search_obj->setValoroperacao(Util::convertRealFloat($json->valoroperacao));
				$search_obj->setIdtipopagamento((int)$json->idtipopagamento);
				$search_obj->setIdservico((int)$json->idservico);
				$search_obj->setIdprocedimento((int)$json->idprocedimento);
				$search_obj->setDatarealizacao(Util::genFromDateToSQLWhole($json->datarealizacao));
				$search_obj->setIdmedico((int)$json->idmedico);
				$search_obj->setIdlateralidade((int)$json->idlateralidade);
				$search_obj->setIdopme((int)$json->idopme);
				$search_obj->setIdstatus((int)$json->idstatus);
				$search_obj->setUpdatedby($COD_USUARIO);
				$search_obj->setObs($json->obs);
				
				$ret = $search_obj->atualizar();
			
				if($ret){
					
					$arr_output['obj'] = $search_obj;
					$arr_output['response_code'] = 200;
					$arr_output['response_msg'] = "Registro atualizado com sucesso!";
					
				}else{
					
					$arr_output['response_code'] = 406;
					$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
					
				}
				
			}else{
				
				$arr_output['response_code'] = 403;
				$arr_output['response_msg'] = "Você não tem permissão para atualizar este registro!";
				
			}
				
				
	}
	
	
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
	

	$obj->setCod($json->cod);
	$search_obj = $obj->getOne();

		
	if($search_obj != null){
			
		if($COD_USUARIO == $search_obj->getCraetedby() || $IS_ADMIN_OU_MESTRE){
			
			$search_obj->setDeletedby($COD_USUARIO);
			$ret = $search_obj->excluir();
			
			if($ret){
				
				$arr_output['obj'] = $search_obj;
				$arr_output['response_code'] = 200;
				$arr_output['response_msg'] = "Registro atualizado com sucesso!";
				
			}else{
				
				$arr_output['response_code'] = 406;
				$arr_output['response_msg'] = "Os dados informados não foram aceitos pelo servidor. Houve alguma inconsistência com a informação. Por favor, tente novamente!";
				
			}
			
		}else{
			
			$arr_output["response_msg"] = "Você não tem permissão para excluir este registro!";
			$arr_output["response_code"] = 403;
			
		}
		
	}else{
		
		$arr_output['response_code'] = 404;
		$arr_output['response_msg'] = "Nenhum registro encontrado para o código informado.";
		
	}
	
}


echo(json_encode($arr_output));
