<?php
require_once("inc/session.php");

if(!class_exists("Status")) require_once("class/Status.php");

?>
<html>
<head>
	<title>.:: SOCS - Médico de Olhos ::.</title>
	<?php require_once("inc/header.php"); ?>
	
		
</head>

	<script>
		
		jQuery(document).ready(function(){
		
			<?php require_once("inc/inc-json-graficos.php") ?>
			//ontem_hoje
			
			geraGraficoSemana({"target_id": "id_grafico_semana_passada", title: null, week: 1});
			geraGraficoSemana({"target_id": "id_grafico_esta_semana", title: null, week: 0});
			geraGraficoAno({"target_id": "id_grafico_anual_por_mes", title: "Produtividade mês a mês", "stacked": false});
			geraOntemHoje({"target_id": "id_grafico_ontem", title: "Ontem", "dia_padrao": "ontem"});
			geraOntemHoje({"target_id": "id_grafico_hoje", title: "Hoje"});
		
		});
	</script>
	
<body>

	<?php require_once("inc/top-menu.php"); ?>
	
	<div class="container-fluid">
			
		<div class="row">
			
			<!--
			<div class="col-md-2  pt-md-2 pb-md-3 bg-light text-dark">
				
				<?php require_once("inc/left-box.php"); ?>
				
			</div>
			-->
			<div class="col-md-12 pt-md-2 bg-white text-dark">
				
				
				<div class="row">
					<div class="col-md-6">
						
						<h4 class="mb-3 alert barra_fis">Produtividade Semana Passada</h4>
						<div id="id_grafico_semana_passada"></div>
						
					</div>
					<div class="col-md-6">
						
						<h4 class="mb-3 alert barra_fis">Produtividade Esta Semana</h4>
						<div id="id_grafico_esta_semana"></div>
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						
						<h4 class="mb-3 alert barra_fis">Produtividade Anual por mês</h4>
						<div id="id_grafico_anual_por_mes"></div>
						
					</div>
				</div>
				
			</div>
			
			
		</div>
	</div>


</body>
</html>