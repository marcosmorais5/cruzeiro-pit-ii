<?php
require_once("inc/session.php");

//if(!class_exists("Medico")) require_once("class/Medico.php");
//if(!class_exists("TipoPagamento")) require_once("class/TipoPagamento.php");
//if(!class_exists("Servico")) require_once("class/Servico.php");
//if(!class_exists("Procedimento")) require_once("class/Procedimento.php");
//if(!class_exists("Lateralidade")) require_once("class/Lateralidade.php");
//if(!class_exists("Status")) require_once("class/Status.php");
//if(!class_exists("Opme")) require_once("class/Opme.php");
//if(!class_exists("Cliente")) require_once("class/Cliente.php");
//if(!class_exists("Usuario")) require_once("class/Usuario.php");
if(!class_exists("Grupo")) require_once("class/Grupo.php");


?>
<html>
<head>
	<title>.:: SOCS - Médico de Olhos ::.</title>
	<?php require_once("inc/header.php"); ?>
	

		<script>
			

			
			var json_grupo = <?php echo(json_encode(Grupo::$ALL_GROUPS_HASH)); ?>;
			var json_hash_ativo = {"Y": "Sim", "N": "Não"};
			
			jQuery(document).ready(function(){
					
					
					let numero_de_colunas = 5;
					let total_geral = 0.0;
					let DEL = "\n";
					let arr_linhas = new Array();
					let tabela_fila = "<div class='table-responsive'>"+ DEL +
						"<table class='table table-md table-striped table-bordered table-hover tabela-fila'>"+ DEL +
						"<thead>"+ DEL +
						"<tr>"+ DEL +
						"<th>N&ordm;</th>"+ DEL +
						"<th>Login</th>"+ DEL +
						"<th>Nome</th>"+ DEL +
						"<th>Grupo</th>"+ DEL +
						"<th>Ativo</th>"+ DEL +
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
						
						
						
					let parte_url = "";
					parte_url = window.location.search.substring(1);
					
					
					
					$.ajax({
						
						url: "usuario_crud.php?fila=sim&"+ parte_url,
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 200){
								
								$.each(data.fila, function(i, obj){
									
									let linha = "<tr class='linhas'>"+
										"<td class='linha_cod link_id'><a href='usuario_cadastro.php?idusuario="+ obj.idusuario +"'>Abrir "+ obj.idusuario +"</a></td>"+
										"<td class='linha_data'>"+ obj.loginusuario +"</td>"+
										"<td class='linha_data'>"+ obj.nomeusuario +"</td>"+
										"<td class='linha_grupo'>"+ obj.grupo +"</td>"+
										"<td class='linha_ativo'>"+ obj.ativo +"</td>"+
										
										"</tr>\n";
									
									arr_linhas.push(linha);
									
									
								});
								
								
								if(arr_linhas.length == 0){
									arr_linhas.push(no_record)
								}
								
								/* POPULANDO A TABELA */
								$("#filaDados").html(tabela_fila.replace("<!--conetudo-->", arr_linhas.join("\n") ) );
								
								
								
								
								$(".linhas").each(function(i, lobj){
									
									$(this).find("td").each(function(){
										
										
										obj = $(this);
										conteudo_celula = $.trim(obj.text());
										
										
										if(!obj.hasClass("link_id")){
											
										 
											if(conteudo_celula == "-1" || conteudo_celula == "0") obj.addClass("font-italic");
											
											if(obj.hasClass("linha_grupo")) conteudo_celula = json_grupo[conteudo_celula];
											if(obj.hasClass("linha_ativo")) conteudo_celula = json_hash_ativo[conteudo_celula];



											
											
										}
										
										if(!obj.hasClass("link_id")) obj.html(conteudo_celula);
										
									});
									
	
								});

								
								
							}else{
								
								alert("Erro ao tentar capturar a fila de orçamentos.")
								
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
				
				
				
				<h4 class="mb-3 alert barra_fis">Lista de usuários</h4>
				

				<div id="filaDados">
					Carregando lista de usuários...
				</div>
			</div>
			
			
		</div>
	</div>
	
	<?php require_once("inc/footer.php"); ?>
	<script>
		
		$("#idtipopagamento").val(<?=((int)$_GET['idtipopagamento'] <= 0 ? -1 : (int)$_GET['idtipopagamento'] )?>);
		$("#idstatus").val(<?=((int)$_GET['idstatus'] <= 0 ? -1 : (int)$_GET['idstatus'] )?>);
		$("#fechado_de").val("<?=$_GET['fechado_de']?>");
		$("#fechado_ate").val("<?=urldecode($_GET['fechado_ate'])?>");

		
	</script>

</body>
</html>