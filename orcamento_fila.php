<?php
require_once("inc/session.php");

if(!class_exists("Status")) require_once("class/Status.php");

?>
<html>
<head>
	<title>.:: SOCS - Médico de Olhos ::.</title>
	<?php require_once("inc/header.php"); ?>
	

		<script>
			

			
			<?php require_once("inc/inc-json-replacement.php"); ?>

			//var json_option_medicos = <?=json_encode(Medico::getAllStatic())?>;
			var json_option_tipo_pagamento = <?=json_encode(TipoPagamento::getAllStatic())?>;
			//var json_option_servico = <?=json_encode(Servico::getAllStatic())?>;
			//var json_option_procedimento = <?=json_encode(Procedimento::getAllStatic())?>;
			//var json_option_lateralidade = <?=json_encode(Lateralidade::getAllStatic())?>;
			var json_option_status = <?=json_encode(Status::getAllStatic())?>;
			//var json_option_opme = <?=json_encode(Opme::getAllStatic())?>;
			//var json_option_clientes = <?=json_encode(Cliente::getAllStatic())?>;
			
			jQuery(document).ready(function(){
					
					
					let numero_de_colunas = 15;
					let total_geral = 0.0;
					let DEL = "\n";
					let arr_linhas = new Array();
					let tabela_fila = "<div class='table-responsive'>"+ DEL +
						"<table class='table table-sm table-striped table-bordered table-hover tabela-fila'>"+ DEL +
						"<thead>"+ DEL +
						"<tr>"+ DEL +
						"<th>N&ordm;</th>"+ DEL +
						"<th>Data</th>"+ DEL +
						"<th>Data Realização</th>"+ DEL +
						"<th>Cliente</th>"+ DEL +
						"<th>Serviço</th>"+ DEL +
						"<th>Procedimento</th>"+ DEL +
						"<th>Lateralidade</th>"+ DEL +
						"<th>Médico</th>"+ DEL +
						"<th>OPME</th>"+ DEL +
						"<th>Valor</th>"+ DEL +
						"<th>Pagamento</th>"+ DEL +
						"<th>Status</th>"+ DEL +
						//"<th>Criado Por</th>"+ DEL +
						"<th>Data Fechado</th>"+ DEL +
						"<th>Observação</th>"+ DEL +
						"</tr>"+ DEL +
						"</thead>"+ DEL +
						"<tbody>"+ DEL +
						"<!--conetudo-->"+ DEL +
						"</tbody>"+ DEL +
						"</table>"+ DEL +
						"</div>";
						
					let footer = "<thead>"+ DEL +
						"<tr>"+ DEL +
						//"<th>N&ordm;</th>"+ DEL +
						//"<th>Data</th>"+ DEL +
						//"<th>Data Realização</th>"+ DEL +
						//"<th>Cliente</th>"+ DEL +
						//"<th>Serviço</th>"+ DEL +
						//"<th>Procedimento</th>"+ DEL +
						//"<th>Lateralidade</th>"+ DEL +
						//"<th>Médico</th>"+ DEL +
						"<th colspan='9' style='text-align: right'>Total Geral da Lista</th>"+ DEL +
						"<th colspan='6' style='text-align: left'><!--total-gera--></th>"+ DEL +
						"</tr>"+ DEL +
						"</thead>"+ DEL;
					
					let no_record = "<tr>"+
						"<td colspan='"+ numero_de_colunas +"'>Não há registros a serem exibidos.</td>"+
						"</tr>";
						
						
						
					let parte_url = "";
					parte_url = window.location.search.substring(1);
					
					
					
				
					
					$.ajax({
						
						url: "orcamento_crud.php?fila=sim&"+ parte_url,
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 200){
								
								$.each(data.fila, function(i, obj){
									
									caixa_ok = "";
									if(obj.idstatus == <?=Status::$FECHADO?> || obj.idstatus == <?=Status::$C_D?>){
										
										caixa_ok = obj.caixaok;
									
										if(caixa_ok == "Y") caixa_ok = "<strong>Caixa Recebeu</strong>";
										if(caixa_ok == "N") caixa_ok = "<i style='color: #FF0000'>Caixa Aguardando</i>";
										
										caixa_ok = "<div>"+ caixa_ok +"</div>";
									}
									
									
									let linha = "<tr class='linhas'>"+
										"<td class='linha_cod link_id'><a href='orcamento_cadastro.php?cod="+ obj.cod +"'>Abrir "+ obj.cod +"</a> "+ caixa_ok +"</td>"+
										"<td class='linha_data typeData'>"+ obj.data +"</td>"+
										"<td class='linha_data typeData'>"+ obj.datarealizacao +"</td>"+
										"<td class='linha_idcliente'>"+ obj.idcliente +"</td>"+
										
										
										"<td class='linha_idservico'>"+ obj.idservico +"</td>"+
										"<td class='linha_idprocedimento'>"+ obj.idprocedimento +"</td>"+
										"<td class='linha_idlateralidade'>"+ obj.idlateralidade +"</td>"+
										"<td class='linha_idmedico'>"+ obj.idmedico +"</td>"+
										"<td class='linha_idopme'>"+ obj.idopme +"</td>"+
										"<td class='linha_valoroperacao valor_BRL'>"+ obj.valoroperacao +"</td>"+
										"<td class='linha_idtipopagamento'>"+ obj.idtipopagamento +"</td>"+
										"<td class='linha_idstatus'>"+ obj.idstatus +"</td>"+
										//"<td class='linha_idcraetedby'>"+ obj.craetedby +"</td>"+
										"<td class='linha_datafechado typeData'>"+ obj.dateclosed +"</td>"+
										"<td class='linha_obs'>"+ obj.obs +"</td>"+
										"</tr>\n";
									
									
										
										console.log("Fila de orcamentos (obj): "+ JSON.stringify(obj));
										
										
										
									arr_linhas.push(linha);
									
									
								});
								
								
								if(arr_linhas.length == 0){
									arr_linhas.push(no_record)
								}
								
								/* POPULANDO A TABELA */
								$("#filaDados").html(tabela_fila.replace("<!--conetudo-->", arr_linhas.join("\n") ) );
								
								
								total_geral = filaOrcamentoSubstituirFila(total_geral);
								
								/* PREENCHENDO O RODAPÉ */
								if(arr_linhas.length <= 0){
									
									$(".tabela-fila").append();
								
								}
								
							
								
								$(".tabela-fila").append(
									
									footer.replace("<!--total-gera-->", "R$ "+ UTILS.formatarMoedaBRL(total_geral) )
								);
								
								
							}else{
								
								alert("Erro ao tentar capturar a fila de orçamentos.")
								
							}
							
							
						}
						
					});
					
				
				
				
				$(".btn-export-table").click(function(){
					
					$.ajax({
						url: "geral_export_excel.php?excel=dump&nome_arquivo=orcamento_fila.xls",
						cache: false,
						type: 'POST',
						contentType: 'text/html; charset=utf-8',
						success: function(data){
							
							
							if(typeof($("#export_excel").attr("src")) == 'undefined'){
								
								$("body").append("<iframe src='' id='export_excel' style='display: none'></iframe>");
								
							}
							
							$("#export_excel").attr("src", "geral_export_excel.php?excel=export");
							
						},
						data: encodeURI($(".table-responsive").html())
						
						
					});
					
				});	
				
			});
		</script>
		
</head>

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
				
				
				
				<h4 class="mb-3 alert barra_fis">Fila de orçamentos</h4>
				
				<form method="get">
				
					<div class="row">
				
						<div class="col-md-1 mb-3">
							<select name="idtipopagamento" id="idtipopagamento" class="custom-select w-100 to-save">
							<option value="-1">Tipo Pgto...</option>
							</select>
						</div>
						
						<div class="col-md-1 mb-3">
							<select name="idstatus" id="idstatus" class="custom-select w-100 to-save">
							<option value="-1">Status...</option>
							</select>
						</div>
					
						
						<div class="col-md-3 mb-3">
							
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Fechado</span>
								</div>
								<input type="text" name="fechado_de" id="fechado_de" class="form-control typeDate to-save" placeholder="De"/>
								<input type="text" name="fechado_ate" id="fechado_ate" class="form-control typeDate to-save" placeholder="Até"/>
							</div>
								
						</div>
						
						<div class="col-md-3 mb-3">
							
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Criado</span>
								</div>
								<input type="text" name="criado_de" id="criado_de" class="form-control typeDate to-save" placeholder="De"/>
								<input type="text" name="criado_ate" id="criado_ate" class="form-control typeDate to-save" placeholder="Até"/>
							</div>
								
						</div>
						
						
						<div class="col-md-2 mb-3">
							<input type="submit" value="Filtrar"  class="change-buttons btn btn-success">
							<input type="button" value="Salvar em Excel"  class="change-buttons btn btn-info btn-export-table">
						</div>
						
					</div>
					
				</form>
				<hr>
				<div id="filaDados">
					
					<div class="progress" style="height:80px">
					  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 2rem; ">Carregando lista de procedimentos...</div>
					</div>
					<br>
					
				</div>

			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>
	<script>
		
		$("#idtipopagamento").val(<?=((int)$_GET['idtipopagamento'] <= 0 ? -1 : (int)$_GET['idtipopagamento'] )?>);
		$("#idstatus").val(<?=((int)$_GET['idstatus'] <= 0 ? -1 : (int)$_GET['idstatus'] )?>);
		$("#criado_de").val("<?=$_GET['criado_de']?>");
		$("#fechado_de").val("<?=$_GET['fechado_de']?>");
		$("#criado_ate").val("<?=$_GET['criado_ate']?>");
		$("#fechado_ate").val("<?=urldecode($_GET['fechado_ate'])?>");

		
	</script>

</body>
</html>