<?php
session_start();
//require_once("inc/log-config.php");

/** Configuracao necessaria para o servidor remoto, pois nao eh possivel alterar o php.ini */
error_reporting(0);
ini_set('display_errors', '0');

/* SET THE DEFAULT TIME ZONE */
ini_set("date.timezone", "America/Sao_Paulo");


if(strlen($_SESSION['cod_usuario']) <= 0 || !isset($_SESSION['cod_usuario']) ){
	
	header("location: logon.php");
	
}

ob_start('ob_gzhandler');

