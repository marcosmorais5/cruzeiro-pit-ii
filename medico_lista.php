<?php
require_once("inc/session.php");

if(!class_exists("Grupo")) require_once("class/Grupo.php");

?>
<html>
<head>
	
	<?php require_once("inc/header.php"); ?>
	

		<script>
			

			
			var json_grupo = <?php echo(json_encode(Grupo::$ALL_GROUPS_HASH)); ?>;
			var json_hash_ativo = {"Y": "Sim", "N": "Não"};
			
			jQuery(document).ready(function(){
					
					
					let numero_de_colunas = 2;
					let DEL = "\n";
					let arr_linhas = new Array();
					let tabela_fila = "<div class='table-responsive'>"+ DEL +
						"<table class='table table-md table-striped table-bordered table-hover tabela-fila'>"+ DEL +
						"<thead>"+ DEL +
						"<tr>"+ DEL +
						"<th style='width:15%'>N&ordm;</th>"+ DEL +
						"<th>Nome do Médico</th>"+ DEL +
						"<tr>"+ DEL +
						"</thead>"+ DEL +
						"<tbody>"+ DEL +
						"<!--conetudo-->"+ DEL +
						"</tbody>"+ DEL +
						"</table>"+ DEL +
						"</div>";
					
					let no_record = "<tr>"+
						"<td colspan='"+ numero_de_colunas +"'>Não há registros a serem exibidos.</td>"+
						"</tr>";
						
						
						

					
					
					
					/* CAPTURANDO A PARTE DA SUBSTRING */
					let parte_url = "";
					parte_url = window.location.search.substring(1);
					
					$.ajax({
						
						url: "medico_crud.php?fila=sim&"+ parte_url,
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 200){
								
								$.each(data.fila, function(i, obj){
									
									let linha = "<tr class='linhas'>"+
										"<td class='linha_cod link_id'><a href='medico_cadastro.php?idmedico="+ obj.idmedico +"'>Abrir "+ obj.idmedico +"</a></td>"+
										"<td class='linha_data'>"+ obj.nomemedico +"</td>"+
										"</tr>\n";
									
									arr_linhas.push(linha);
									
									
								});
								
								
								if(arr_linhas.length == 0){
									arr_linhas.push(no_record)
								}
								
								/* POPULANDO A TABELA */
								$("#filaDados").html(tabela_fila.replace("<!--conetudo-->", arr_linhas.join("\n") ) );
								
								/* carrega orcamentos */
								loadOrcamentos()
								
								
							}else{
								
								alert("Erro ao tentar capturar a lista de méidicos.");
								
							}
							
							
						}
						
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
				
				
				
				<h4 class="mb-3 alert barra_fis">Lista de médicos</h4>
				

				<div id="filaDados">
					Carregando lista de médicos...
				</div>
			</div>
			
			
		</div>
	</div>
	
	

</body>
</html>