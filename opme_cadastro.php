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

	<?php require_once("inc/header.php"); ?>
	

		<script>
			
			
			jQuery(document).ready(function(){
				
				
				if(<?=(int)$_GET['idopme']?> > 0 ){
					
					$.ajax({
						
						url: "opme_crud.php?idopme=<?=(int)$_GET['idopme']?>",
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
				
				
				let method_type = (<?=(int)$_GET['idopme']?> > 0) ? "PUT" : "POST";
				/* POST ACTION: LISTEN THE FORM AND PARSE 'to-save' TO JSON */
				
				UTILS.postData({
					
					"obj": "#form_cad_opme",
					"action_page": "opme_crud.php",
					"json_class": ".to-save",
					"method_type": method_type,
					"return_page": "opme_lista.php"
					
				});
				
				
				
				/* VOLTAR PARA A FILA */
				$("#button_voltar_fila").click(function(){
					
					location.href = 'opme_lista.php';
					
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
				
				
					<form id="form_cad_opme">
					
						<?php require_once("templates/opme.html"); ?>
						
						<?php if((int)$_GET['idopme'] > 0) { ?>
						<button type="submit" id="button_submit"  title="Atualizar Registro" class="btn btn-success">Atualizar Registro</button>
						<button type="button" id="button_voltar_fila"  title="Voltar para Fila" class="btn btn-info">Voltar para Fila</button>
						
						<?php }else{ ?>
						<button type="submit" id="button_submit"  title="Criar Usuário" class="btn btn-success">Criar OPME</button> 
						<button type="button" id="button_voltar_fila"  title="Ir para a fila" class="btn btn-info">Ir para a fila</button>
						<?php } ?>
						
					</form>
				
			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>


</body>
</html>