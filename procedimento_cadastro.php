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
if(!class_exists("Usuario")) require_once("class/Usuario.php");
if(!class_exists("Grupo")) require_once("class/Grupo.php");

?>
<html>
<head>
	<title>.:: SOCS - Médico de Olhos ::.</title>
	<?php require_once("inc/header.php"); ?>
	

		<script>
			
			var json_option_ativo = [{"idativo": "Y", "ativo": "Ativo"},{"idativo": "N", "ativo": "Desativado"}];
			
			jQuery(document).ready(function(){
				
				
				if(<?=(int)$_GET['idprocedimento']?> > 0 ){
					
					$.ajax({
						
						url: "procedimento_crud.php?idprocedimento=<?=(int)$_GET['idprocedimento']?>",
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 404 || data.response_code == 403){
								
								alert(data.response_msg);
								
							}else{
								
								UTILS.loadFieldsFromJSON(data.obj);
								
								
							}
							
							
						}
						
					});
					
					
				}
				
				
				let method_type = (<?=(int)$_GET['idprocedimento']?> > 0) ? "PUT" : "POST";
				/* POST ACTION: LISTEN THE FORM AND PARSE 'to-save' TO JSON */
				
				UTILS.postData({
					
					"obj": "#form_cad_procedimento",
					"action_page": "procedimento_crud.php",
					"json_class": ".to-save",
					"method_type": method_type,
					"return_page": "procedimento_lista.php"
					
				});
				
				
				
				/* VOLTAR PARA A FILA */
				$("#button_voltar_fila").click(function(){
					
					location.href = 'procedimento_lista.php';
					
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
				
				
					<form id="form_cad_procedimento">
					
						<?php require_once("templates/procedimento.html"); ?>
						
						<?php if((int)$_GET['idprocedimento'] > 0) { ?>
						<button type="submit" id="button_submit"  title="Atualizar Registro" class="btn btn-success">Atualizar Registro</button>
						<button type="button" id="button_voltar_fila"  title="Voltar para Fila" class="btn btn-info">Voltar para Fila</button>
						
						<?php }else{ ?>
						<button type="submit" id="button_submit"  title="Criar Usuário" class="btn btn-success">Criar Procedimento</button> 
						<button type="button" id="button_voltar_fila"  title="Ir para a fila" class="btn btn-info">Ir para a fila</button>
						<?php } ?>
						
					</form>
				
			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>


</body>
</html>