<?php
require_once("inc/session.php");
header("Content-type: application/json; charset=utf-8");

if(!class_exists("Relatorio")) require_once("class/Relatorio.php");


$json = json_decode(file_get_contents("php://input"));

/* HELPER */
$obj = new Relatorio();

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
	
	
	if($IS_ADMIN_OU_MESTRE){
		
		if($_GET['relatorio_dados'] == "gerais"){
			
			$orcamento = new Orcamento();
			$arr_output['relatorio'] = $orcamento->getRelatorioFiltro();
			
		/* PRODUTIVIDAD DE ONTEM E HOJE */
		}else if($_GET['relatorio_dados'] == "produtividade_ontem_hoje"){
			
			
			$arr = $obj->getValoresServicoOntemHoje();
			
			$arr_output['grafico'] = $arr;
			
			
		/* PRODUTIVIDAD DE ONTEM E HOJE */
		}else if($_GET['relatorio_dados'] == "produtividade_semana"){
			
			
			$arr = $obj->getValoresServicoSemanaMatriz((int)$_GET['semana']);
			
			$arr_output['grafico'] = $arr;
			
			
		/* PRODUTIVIDAD DE ONTEM E HOJE */
		}else if($_GET['relatorio_dados'] == "ontem_hoje"){
			
			
			$arr = $obj->getValoresServicoOntemHoje();
			
			$arr_output['grafico'] = $arr;
			
			
		
		/* PRODUTIVIDAD DE ONTEM E HOJE */
		}else if($_GET['relatorio_dados'] == "mes_a_mes"){
			
			
			$arr = $obj->getValoresMesesMatriz((int)$_GET['ano'], 1);
			
			$arr_output['grafico'] = $arr;
			
			
		}
		
	}else{
		
		$arr_output['response_code'] = 403;
		$arr_output['response_msg'] = "Você não tem permissão para acessar este recurso.";
		
	}
	
	
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	
	
	
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
	
	
		
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
	

	
}


echo(json_encode($arr_output));
