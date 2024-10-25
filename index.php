<?php
require_once("inc/session.php");

$start_time = microtime(true);



if(!class_exists("Usuario")) require_once("class/Usuario.php");
if(!class_exists("Grupo")) require_once("class/Grupo.php");
?>
<html>
<head>
	<?php require_once("inc/header.php"); ?>

	<?php
	
	$E_ADMIN = $_SESSION['grupo'] == "ADMINISTRADOR_MESTRE" || $_SESSION['grupo'] == "ADMINISTRADOR";
	$E_CAIXA = Usuario::getGrupoUsuario((int)$_SESSION['cod_usuario']) == "CAIXA";
	if($E_CAIXA){ ?>
	<script>
		

		
		jQuery(document).ready(function(){
			
			
			function actionCaixaRecebeu(){
				
				$(".caixa-recebeu").click(function(){
					
					PUT = $(this).attr("PUT");
					
					if(confirm("Confirmar o recebimento do valor por para do cliente para este orçamento?")){
						
						$.ajax({
							
							url: "orcamento_crud.php?caixa_pagamento=confirma",
							cache: false,
							type: 'PUT',
							contentType: 'application/json; charset=utf-8',
							success: function(data){
								
								console.log(data);
								
								if(data.response_code == 200){
									
									alert(data.response_msg);
									
									/* RECARREGA A FILA DO CAIXA EM 3 SEGUNDOS */
									setTimeout(function(){
										
										carregarPainelCaixa();
										
									}, 1000 * 3);
									
								}else{
									
									alert(data.response_msg);
									
								}
								
								
							},
							data: PUT
							
						});
						
					}
					
				});
				
			}
			
			function carregarPainelCaixa(){
				
				/* MOSTRA A BARRA DE FILA DO CAIXA */
				$(".fila-caixa").show();
				
				/* MOSTRA A FILA DO CAIXA */
				$("#filaCaixa").show();
				
				
				
				let numero_de_colunas = 14;
				let total_geral = 0.0;
				let DEL = "\n";
				let arr_linhas = new Array();
				let tabela_fila = "<div class='table-responsive'>"+ DEL +
					"<table class='table table-md table-striped table-bordered table-hover tabela-fila'>"+ DEL +
					"<thead>"+ DEL +
					"<tr>"+ DEL +
					"<th>Cliente</th>"+ DEL +
					"<th>Médico</th>"+ DEL +
					"<th>Valor</th>"+ DEL +
					"<th>Pagamento</th>"+ DEL +
					"<th>Status</th>"+ DEL +
					"<th>Orientador</th>"+ DEL +
					"<th>Observação</th>"+ DEL +
					"<th>Caixa</th>"+ DEL +
					"<tr>"+ DEL +
					"</thead>"+ DEL +
					"<tbody>"+ DEL +
					"<!--conetudo-->"+ DEL +
					"</tbody>"+ DEL +
					"</table>"+ DEL +
					"</div>";
					
				let footer = "<thead>"+ DEL +
					"<tr>"+ DEL +
					"<th colspan='9' style='text-align: right'>Total Geral da Lista</th>"+ DEL +
					"<th colspan='5'><!--total-gera--></th>"+ DEL +
					"<tr>"+ DEL +
					"</thead>"+ DEL;
				
				let no_record = "<tr>"+
					"<td colspan='"+ numero_de_colunas +"'>Não há registros a serem exibidos.</td>"+
					"</tr>";
				
				$.ajax({
						
					url: "orcamento_crud.php?filacaixa=sim",
					cache: false,
					type: 'GET',
					contentType: 'application/json; charset=utf-8',
					success: function(data){
						
						
						if(data.response_code == 200){
						
							$.each(data.filacaixa, function(i, obj){
								
								let linha = "<tr class='linhas'>"+

									"<td class='linha_idcliente'>"+ obj.idcliente +"</td>"+
									"<td class='linha_idmedico'>"+ obj.idmedico +"</td>"+
									"<td class='linha_valoroperacao valor_BRL'>"+ obj.valoroperacao +"</td>"+
									"<td class='linha_idtipopagamento'>"+ obj.idtipopagamento +"</td>"+
									"<td class='linha_idstatus'>"+ obj.idstatus +"</td>"+
									"<td class='linha_idcraetedby'>"+ obj.craetedby +"</td>"+
									"<td class='linha_obs'>"+ obj.obs +"</td>"+
									"<td class='linha_validar link_id align-center'><button type='button' title='Clique aqui para informar de que o valor foi recebido pelo caixa' class='btn btn-success caixa-recebeu' PUT='{\"cod\":"+ obj.cod +"}'>Valor Recebido</button></td>"+
									"</tr>\n";
								
								arr_linhas.push(linha);
								
								
							});
							
							
							
							if(arr_linhas.length == 0){
								arr_linhas.push(no_record)
							}
							
							/* POPULANDO A TABELA */
							$("#filaCaixa").html(tabela_fila.replace("<!--conetudo-->", arr_linhas.join("\n") ) );
						
							/* ADICIONA AÇÕES AO BOTÃO DE RECEBIMENTO DO CAIXA */
							actionCaixaRecebeu();
							filaOrcamentoSubstituirFila(0);
							
						}
						
					}
				});
				
			}
			
			
			
			/* INICIA O CARRETAMENTO DA TABELA */
			carregarPainelCaixa();
			
			
		
		});

		
	</script>
	
	<?php }else{ ?>
	<!-- SE NÃO FOR CAIXA, MOSTRA OS DADOS DE OUTRA MANEIRA -->
	
	<script>
		jQuery(document).each(function(){
			
			geraTabelaProximosEventos("#orcamentoProximosAcontecimentos");
			
		});
	</script>
	<?php } ?>
	
	<script>
		
		
		<?php require_once("inc/inc-json-graficos.php") ?>
		
		
		<?php if($_SESSION['grupo'] == "ADMINISTRADOR" || $_SESSION['grupo'] == "ADMINISTRADOR_MESTRE"){ ?>
		
		geraGraficoSemana({"target_id": "id_grafico_semana", title: "Produtividade esta semana"});
		geraOntemHoje({"target_id": "id_grafico_ontem", title: "Ontem", "dia_padrao": "ontem"});
		geraOntemHoje({"target_id": "id_grafico_hoje", title: "Hoje"});
		
		<?php } ?>
	</script>

	
	
	
	<script>

		jQuery(document).ready(function(){
			
			$(".buscar-cliente").click(function(){
				
				location.href = 'cliente_lista.php?nome='+ $("#nomecliente").val();
				
			});
			
			$("#form-busca-cliente").submit(function(e){
				
				e.preventDefault();
				location.href = 'cliente_lista.php?nome='+ $("#nomecliente").val();
				
			});
			
			
		});

	</script>

</head>

<body>

	<?php require_once("inc/top-menu.php"); ?>
	
	<div class="container-fluid">
			
		<div class="row">
			
			<div class="col-md-2  pt-md-2 pb-md-3 bg-light text-dark">
				
				<?php require_once("inc/left-box.php"); ?>
				
			</div>
			<div class="col-md-10 pt-md-2 bg-white text-dark">
				
				<?php if($_SESSION['grupo'] == Grupo::$ADMINISTRADOR || $_SESSION['grupo'] == Grupo::$ADMINISTRADOR_MESTRE) { ?>
				<!-- OS GRÁFICOS VÃO AQUI -->
				<div class="row">
					<div class="col">
						<h4 class='mb-3 alert barra_fis'>Resumo da semana e de hoje e ontem</h4>
					</div>
				</div>
				<div class="row">
					
					<div class="col-md-6">
						
						<div id="id_grafico_semana"></div>
						<div id="id_grafico_ano"></div>
						
					</div>
					<div class="col-md-3">
						
						<div id="id_grafico_ontem"></div>
						
					</div>
					<div class="col-md-3">
						
						<div id="id_grafico_hoje"></div>
						
					</div>
					
					
				</div>
				<!-- OS GRÁFICOS VÃO AQUI -->
				<?php } ?>
		
		
		
		
								

				<?php if(!$E_CAIXA){ ?>
				
				<div class="row">
					<div class="col">
						<h4 class='mb-3 alert barra_fis'>Opções iniciais do sistema</h4>
					</div>
				</div>
				<div class="row">
					<div class="col col-md-6">
						
						<h1>O que você deseja fazer?</h1>
				
						<ul>
							<li><a href="orcamento_fila.php">Ver sua fila de orçamentos</a></li>
							
							<li><a href="orcamento_cadastro.php">Cadastrar orçamento</a></li>
							
							<li><a href="cliente_cadastro.php">Cadastrar um cliente</a></li>
							
							<?php if($E_ADMIN){ ?>
							<li><a href="relatorio_geral.php">Realizar um relatório</a></li>
							<li><a href="usuario_cadastro.php">Cadastrar um usuário</a></li>
							<?php } ?>
						</ul>
						
						<hr>
						
						<h4>Pesquisar cliente</h4>
						<form id="form-busca-cliente">					
						
							<div class="input-group">
								
								<div class="input-group-prepend">
											<span class="input-group-text">Nome do cliente</span>
										</div>
										
								<input type="text" name="nomecliente" id="nomecliente" class="form-control"/>
								
								<div class="input-group-append" id="id_button_box_copy_local_from">
									<button type="submit" class="btn btn-success buscar-cliente">Buscar Cliente</button>
								</div>

							</div>

						</form>
						
					</div>
					<div class="col col-md-6">
						
						<h4 class="mb-3 alert barra_fis">Próximas cirurgias dentro de 15 dias</h4>
						<div id="orcamentoProximosAcontecimentos"></div>
						
					</div>



				</div>
				<?php } ?>
				
				<!-- ESTES DADOS SÓ SÃO EXIBIDOS SE FOR O CAIXA, ELE INICIA OCULTO -->
				<h4 class="mb-3 alert barra_fis fila-caixa" style="display: none">Fila de orçamento pendente de validação do caixa</h4>
				<div id="filaCaixa" style="display: none">Carregando fila do caixa...</div>
				
				

				
				
			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>



	<?php echo "Tempo até o Ponto 1: " . (microtime(true) - $start_time) . " segundos.<br>"; ?>

</body>
</html>