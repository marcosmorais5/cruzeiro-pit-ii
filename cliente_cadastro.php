<?php
require_once("inc/session.php");

if(!class_exists("Cliente")) require_once("class/Cliente.php");

?>
<html>
<head>
	
	<?php require_once("inc/header.php"); ?>
	

		<script>
			

			jQuery(document).ready(function(){
					
				if(<?=(int)$_GET['idcliente']?> > 0 ){
					
					$.ajax({
						
						url: "cliente_crud.php?idcliente=<?=(int)$_GET['idcliente']?>",
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
				
				let method_type = (<?=(int)$_GET['idcliente']?> > 0) ? "PUT" : "POST";
				/* POST ACTION: LISTEN THE FORM AND PARSE 'to-save' TO JSON */
				
				UTILS.postData({
					
					"obj": "#form_cad_cliente",
					"action_page": "cliente_crud.php",
					"json_class": ".to-save",
					"method_type": method_type
					
				});
				
				
				
				/* VOLTAR PARA A FILA */
				$("#button_voltar_fila").click(function(){
					
					url_voltar_para = $(this).attr("url_voltar_para");
					
					if(url_voltar_para == ""){
						url_voltar_para = "cliente_lista.php"
					}
					
					location.href = url_voltar_para;
					
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
				
				<div>
				
					<form id="form_cad_cliente">
					
						<?php require_once("templates/cliente.html"); ?>

						<?php
						if(isset($_GET['proveniente'])){
							
							$VOLTAR_TEXT = "Voltar";
							
						}else{
							
							$VOLTAR_TEXT = "Ir para a fila";
							
						}?>
						
						<?php if($_GET['idcliente'] > 0) { ?>
						<button type="submit" id="button_submit"  title="Atualizar Registro" class="change-buttons btn btn-success">Atualizar Registro</button>
						

						
						<button type="button" id="button_voltar_fila" url_voltar_para="<?=$_GET['proveniente']?>" title="<?=$VOLTAR_TEXT?>" class="change-buttons btn btn-info"><?=$VOLTAR_TEXT?></button>
						
						<?php }else{ ?>
						<button type="submit" id="button_submit"  title="Criar Usuário" class="change-buttons btn btn-success">Criar Cliente</button>
						<button type="button" id="button_voltar_fila" url_voltar_para="<?=$_GET['proveniente']?>" title="<?=$VOLTAR_TEXT?>" class="change-buttons btn btn-info"><?=$VOLTAR_TEXT?></button>
						<?php } ?>
						
					</form>
				
				</div>
				
			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>


</body>
</html>