<?php

if(!class_exists("Medico")) require_once("class/Medico.php");
if(!class_exists("TipoPagamento")) require_once("class/TipoPagamento.php");
if(!class_exists("Servico")) require_once("class/Servico.php");
if(!class_exists("Procedimento")) require_once("class/Procedimento.php");
if(!class_exists("Lateralidade")) require_once("class/Lateralidade.php");
if(!class_exists("Status")) require_once("class/Status.php");
if(!class_exists("Opme")) require_once("class/Opme.php");
if(!class_exists("Cliente")) require_once("class/Cliente.php");
if(!class_exists("Usuario")) require_once("class/Usuario.php");

?>

			var json_status = <?=json_encode(Status::getAllStatic())?>;
			var json_tipo_pagamento = <?=json_encode(TipoPagamento::getAllStatic())?>;

			var json_medicos = <?=json_encode(Medico::getAllStatic(true))?>;
			var json_tipo_pagamento_fila = <?=json_encode(TipoPagamento::getAllStatic(true))?>;
			var json_servico = <?=json_encode(Servico::getAllStatic(true))?>;
			var json_procedimento = <?=json_encode(Procedimento::getAllStatic(true))?>;
			var json_lateralidade = <?=json_encode(Lateralidade::getAllStatic(true))?>;
			var json_status_fila = <?=json_encode(Status::getAllStatic(true))?>;
			var json_opme = <?=json_encode(Opme::getAllStatic(true))?>;
			var json_clientes = <?=json_encode(Cliente::getAllStatic(true))?>;
			var json_usuario = <?=json_encode(Usuario::getAllStatic(true))?>;