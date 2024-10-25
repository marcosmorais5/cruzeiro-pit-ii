<?php
require_once("inc/session.php");


if($_GET['excel'] == "dump"){

	header("Content-type: text/html; charset=utf-8");
	
	$input = file_get_contents("php://input");
	
	$_SESSION['content'] = urldecode($input);
	
	if(isset($_GET['nome_arquivo'])){
		
		$_SESSION['nome_arquivo'] = $_GET['nome_arquivo'];
		
	}

}else if($_GET['excel'] == "export"){
	
	header("Content-type: application/vnd.ms-excel; charset=UTF-8");
	
	if(isset($_SESSION['nome_arquivo'])) header('Content-Disposition: attachment; filename="'. $_SESSION['nome_arquivo'] .'"');
	
	echo('<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />');
	
	
	$tabela = $_SESSION['content'];
	$tabela = str_replace("<table", "<table border=1", $tabela);
	$tabela = str_replace("<th ", "<th style='background-color: #C0C0C0; font-family: \"Segoe UI\"; font-size: 13pt' ", $tabela);
	$tabela = str_replace("<th>", "<th style='background-color: #C0C0C0; font-family: \"Segoe UI\"; font-size: 13pt'>", $tabela);
	$tabela = str_replace("<td ", "<td style='font-family: \"Segoe UI\"; font-size: 13pt' ", $tabela);
	$tabela = str_replace("<div>", " ", $tabela);
	$tabela = str_replace("</div>", " ", $tabela);
	$tabela = str_replace("<a", "<!--", $tabela);
	$tabela = str_replace("</a>", "-->", $tabela);
	
	echo($tabela);

}



