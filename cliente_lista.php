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
					
					
					let numero_de_colunas = 6;
					let total_geral = 0.0;
					let DEL = "\n";
					let arr_linhas = new Array();
					let tabela_fila = "<div class='table-responsive'>"+ DEL +
						"<table class='table table-md table-striped table-bordered table-hover tabela-fila'>"+ DEL +
						"<thead>"+ DEL +
						"<tr>"+ DEL +
						"<th>N&ordm;</th>"+ DEL +
						"<th>Nome</th>"+ DEL +
						"<th>E-mail</th>"+ DEL +
						"<th>CPF</th>"+ DEL +
						"<th>Telefone</th>"+ DEL +
						"<th>Orçamentos</th>"+ DEL +
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
						
						
						

					
					function loadOrcamentos(){
						
						$.ajax({
						
							url: "orcamento_crud.php?orcamentos=sim",
							cache: false,
							type: 'GET',
							contentType: 'application/json; charset=utf-8',
							success: function(data){
								
								orcamento_cliente = {};
								
								
								$.each(data.orcamentos, function(i, obj){
									
									
									if(obj.idcliente > 0){
										
										if(typeof(orcamento_cliente['orcamento_cliente_'+ obj.idcliente]) == 'undefined')
											orcamento_cliente['orcamento_cliente_'+ obj.idcliente] = new Array();
									
											orcamento_cliente['orcamento_cliente_'+ obj.idcliente].push(obj);
										
									}
									
									
								});
								
								
								$.each(orcamento_cliente, function(key, orcamentos){
									
									id = "#"+ key;
									
									
									$.each(orcamentos, function(iOrcamento, orcamento){
										
										$(id).append("<a title='Abrir este orçamento' href='orcamento_cadastro.php?cod="+ orcamento.cod +"&proveniente=<?=urlencode($_SERVER['SCRIPT_NAME'])?>'>Orçamento "+ orcamento.cod +"</a><br>");
										
										
									});
									
									
								});
								
							}
						});
					
					}
					
					
					/* CAPTURANDO A PARTE DA SUBSTRING */
					let parte_url = "";
					parte_url = window.location.search.substring(1);
					
					$.ajax({
						
						url: "cliente_crud.php?fila=sim&"+ parte_url,
						cache: false,
						type: 'GET',
						contentType: 'application/json; charset=utf-8',
						success: function(data){
							
							if(data.response_code == 200){
								
								$.each(data.fila, function(i, obj){
									
									let linha = "<tr class='linhas'>"+
										"<td class='linha_cod link_id'><a href='cliente_cadastro.php?idcliente="+ obj.idcliente +"'>Abrir "+ obj.idcliente +"</a></td>"+
										"<td class='linha_data'><a href='cliente_cadastro.php?idcliente="+ obj.idcliente +"'>"+ obj.nome +"</a></td>"+
										"<td class='linha_data'>"+ (obj.email == null ? "" : obj.email) +"</td>"+
										"<td class='linha_grupo'>"+ (obj.cpf == null ? "" : obj.cpf) +"</td>"+
										"<td class='linha_ativo'>"+ (obj.telefone == null ? "" : obj.telefone) +"</td>"+
										"<td class='linha_orcamento'><div id='orcamento_cliente_"+ obj.idcliente +"'></div></td>"+
										
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
				
				
				
				<h4 class="mb-3 alert barra_fis">Lista de clientes</h4>
				

				<div id="filaDados">
					Carregando lista de clientes...
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