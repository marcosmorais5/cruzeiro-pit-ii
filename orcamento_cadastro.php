<?php
require_once("inc/session.php");

if(!class_exists("Medico")) require_once("class/Medico.php");
if(!class_exists("TipoPagamento")) require_once("class/TipoPagamento.php");
if(!class_exists("Servico")) require_once("class/Servico.php");
if(!class_exists("Procedimento")) require_once("class/Procedimento.php");
if(!class_exists("Lateralidade")) require_once("class/Lateralidade.php");
if(!class_exists("Status")) require_once("class/Status.php");
if(!class_exists("Opme")) require_once("class/Opme.php");
if(!class_exists("Cliente")) require_once("class/Cliente.php");

?>
<html>
<head>
	
	<?php require_once("inc/header.php"); ?>
	

		<script>
			
			var json_option_medicos = <?=json_encode(Medico::getAllStatic())?>;
			var json_option_tipo_pagamento = <?=json_encode(TipoPagamento::getAllStatic())?>;
			var json_option_servico = <?=json_encode(Servico::getAllStatic())?>;
			var json_option_procedimento = <?=json_encode(Procedimento::getAllStatic())?>;
			var json_option_lateralidade = <?=json_encode(Lateralidade::getAllStatic())?>;
			var json_option_status = <?=json_encode(Status::getAllStatic())?>;
			var json_option_opme = <?=json_encode(Opme::getAllStatic())?>;
			var json_option_clientes = <?=json_encode(Cliente::getAllStatic())?>;
			
			jQuery(document).ready(function(){
					
				if(<?=(int)$_GET['cod']?> > 0 ){
					
					$.ajax({
						
						url: "orcamento_crud.php?cod=<?=(int)$_GET['cod']?>",
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 404 || data.response_code == 403){
								
								alert(data.response_msg);
								//location.href = "orcamento_fila.php";
								
							}else{
								
								UTILS.loadFieldsFromJSON(data.obj);


								/** Se o caixa já tiver confirmado o recebimento dos valores,
								 * então esconde os botões de exclusão e atualização
								 * */
								if(data.obj.caixaok == "Y"){

									$("#button_submit, #button_delete").hide();

									$("#place-holder-message-top").after("<div class='alert alert-warning' role='warning'>"+
										"Este orçamento já foi fechado. Não é possível atualizar ou excluir o mesmo!");

									$(".to-save").attr("disabled", true);

									
								}

								
							}
							
							
						}
						
					});
					
				}
				
				let method_type = (<?=(int)$_GET['cod']?> > 0) ? "PUT" : "POST";
				/* POST ACTION: LISTEN THE FORM AND PARSE 'to-save' TO JSON */
				
				UTILS.postData({
					
					"obj": "#form_cad_procdeimento",
					"action_page": "orcamento_crud.php",
					"json_class": ".to-save",
					"method_type": method_type,
					"return_page": "orcamento_fila.php"
					
				});
				
				
				$("#button_voltar_tela").click(function(){

					$(".cadastro_procedimento").slideDown();
					$(".cadastro_cliente").slideUp();

				});

				$(".orcamento-cadastro-cliente").click(function(){
					
					$(".cadastro_procedimento").slideUp();
					$(".cadastro_cliente").slideDown();
					
				});

				/* VOLTAR PARA A FILA */
				$("#button_voltar_fila").click(function(){
					
					url_voltar_para = $(this).attr("url_voltar_para");
					
					if(url_voltar_para == ""){
						url_voltar_para = "orcamento_fila.php"
					}
					
					location.href = url_voltar_para;
					
				});
				
				
				
				$("#button_delete").click(function(){
					
					if(confirm("Você tem certeza absoluta de que quer apagar este registro de orçamento?")){
						
						$.ajax({
							
							url: "orcamento_crud.php?cod=<?=(int)$_GET['cod']?>",
							cache: false,
							type: 'DELETE',
							contentType: 'application/json; charset=utf-8',
							success: function(data){
								
								if(data.response_code == 404 || data.response_code == 403 || data.response_code == 500){
									
									alert(data.response_msg);
									
								}else{
									
									location.href = 'orcamento_fila.php';
									
								}
								
								
							},
							data: JSON.stringify( UTILS.getJSONtoSave(".to-save") )
							
						});
						
					}
					
				});
				
				
				/* ATIVANDO CADASTRO DE CLIENTE */
				UTILS.postData({
					
					"obj": "#form_cad_cliente",
					"action_page": "cliente_crud.php",
					"json_class": ".to-save",
					"return_page": "orcamento_cadastro.php"
					
				});
		
		
				<?php if((int)$_GET['cod'] > 0){
					echo("\$('.orcamento-cadastro-cliente').hide();\n");					
				}?>
				<?php if((int)$_GET['cod'] <= 0){
					echo("\$('#data').val('". date("d/m/Y") ."');\n");
					
				}?>

				
				$("#idstatus").change(function(){
					
					if(<?=Status::$S_D?> == parseInt($(this).val())){
						
						$(".esconde-se-sem-despesa").slideUp();
					}else{
						$(".esconde-se-sem-despesa").slideDown();
					}
					
					
				});
				
				setTimeout(function(){
					
					$("#idstatus").change();
					
				}, 1000 * 1);
				
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
				
				<div class="cadastro_cliente" style="display: none">
					
					
					<form id="form_cad_cliente">
					
						<h4 class="mb-3 alert barra_fis">Dados do Cliente</h4>
						
	
						<div class="row">
							<div class="col-md-12 mb-3">
								
								<label for="nome" class="font-weight-bold">Nome</label>
								<div class="input-group">
									<input type="text" name="nome" id="nome" class="form-control to-save"/>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 mb-3">
								
								<label for="datanascimento" class="font-weight-bold">Data de Nascimento</label>
								<div class="input-group">
									<input type="text" name="datanascimento" id="datanascimento" class="form-control to-save typeDate"/>
								</div>
								
							</div>
							<div class="col-md-2 mb-3">
								
								<label for="cpf" class="font-weight-bold">CPF:</label>
								<div class="input-group">
									<input type="text" name="cpf" id="cpf" class="form-control to-save typeCPF"/>
								</div>
								
							</div>
							<div class="col-md-4 mb-3">
								
								<label for="email" class="font-weight-bold">E-mail</label>
								<div class="input-group">
									<input type="text" name="email" id="email" class="form-control to-save"/>
								</div>
								
							</div>
							<div class="col-md-4 mb-3">
								
								<label for="telefone" class="font-weight-bold">Telefone</label>
								<div class="input-group">
									<input type="text" name="telefone" id="telefone" class="form-control to-save"/>
								</div>
								
							</div>
							
						</div>

						<?php
						if(isset($_GET['proveniente'])){
							
							$VOLTAR_TEXT = "Voltar";
							
						}else{
							
							$VOLTAR_TEXT = "Ir para a fila";
							
						}?>
						

						<button type="submit" id="button_submit"  title="Criar Cliente" class="change-buttons btn btn-success">Criar Cliente</button>
						<button type="button" id="button_voltar_tela" title="Voltar" class="change-buttons btn btn-info">Voltar</button>

						
					</form>


					
				</div>
				
				<div class="cadastro_procedimento">
				
					<form id="form_cad_procdeimento">
					
						<?php require_once("templates/orcamento.html"); ?>
						
						<?php if($_GET['cod'] > 0) { ?>
						<button type="submit" id="button_submit"  title="Atualizar Registro" class="change-buttons btn btn-success">Atualizar Registro</button>
						
						<button type="button" id="button_delete"  title="Apagar Orçamento" class="change-buttons btn btn-danger">Apagar Orçamento</button>
						
						<?php }else{ ?>
						<button type="submit" id="button_submit"  title="Criar Registro" class="change-buttons btn btn-success">Criar Registro</button>
						<?php } ?>
						
						<?php
						if(isset($_GET['proveniente'])){
							
							$VOLTAR_TEXT = "Voltar";
							
						}else{
							
							$VOLTAR_TEXT = "Ir para a fila";
							
						}?>
						
						<button type="button" id="button_voltar_fila" url_voltar_para="<?=$_GET['proveniente']?>" title="<?=$VOLTAR_TEXT?>" class="change-buttons btn btn-info"><?=$VOLTAR_TEXT?></button>
						
					</form>
				
				</div>
				
			</div>
			
			
		</div>
	</div>
	<?php require_once("inc/footer.php"); ?>


</body>
</html>