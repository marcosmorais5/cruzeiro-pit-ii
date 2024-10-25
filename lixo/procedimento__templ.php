<?php

if(!class_exists("Medico")) require_once("class/Medico.php");
if(!class_exists("TipoPagamento")) require_once("class/TipoPagamento.php");
if(!class_exists("Servico")) require_once("class/Servico.php");
if(!class_exists("Procedimento")) require_once("class/Procedimento.php");
if(!class_exists("Lateralidade")) require_once("class/Lateralidade.php");
if(!class_exists("Status")) require_once("class/Status.php");
if(!class_exists("Opme")) require_once("class/Opme.php");

?>

<html>
	<head>
		<?php require_once("inc/header.php"); ?>
		
		<script>
			
			var json_medicos = <?=json_encode(Medico::getAllStatic())?>;
			var json_tipo_pagamento = <?=json_encode(TipoPagamento::getAllStatic())?>;
			var json_servico = <?=json_encode(Servico::getAllStatic())?>;
			var json_procedimento = <?=json_encode(Procedimento::getAllStatic())?>;
			var json_lateralidade = <?=json_encode(Lateralidade::getAllStatic())?>;
			var json_status = <?=json_encode(Status::getAllStatic())?>;
			var json_opme = <?=json_encode(Opme::getAllStatic())?>;
			
			jQuery(document).ready(function(){
					
				if(<?=(int)$_GET['cod']?> > 0 ){
					
					$.ajax({
						
						url: "procedimento_crud.php?cod=<?=(int)$_GET['cod']?>",
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 404){
								
								alert(data.response_msg);
								
							}else{
								
								UTILS.loadFieldsFromJSON(data.obj);
								console.log(data);
								
							}
							
							
						}
						
					});
					
				}
				
				let method_type = (<?=(int)$_GET['cod']?> > 0) ? "PUT" : "POST";
				/* POST ACTION: LISTEN THE FORM AND PARSE 'to-save' TO JSON */
				
				UTILS.postData({
					
					"obj": "#form_cad_procdeimento",
					"action_page": "procedimento_crud.php",
					"json_class": ".to-save",
					"method_type": method_type
					
				});
				
						

		
				
			});
		</script>
	</head>
	
	<body>
	

			
		</form>

		
	</body>
	
	<?php require_once("inc/footer.php"); ?>
	
</html>