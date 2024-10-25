<h3>Olá, <?=$_SESSION['nome_usuario']?>!</h3>


<hr>

<?php
/* SE FOR CAIXA, NÃO MOSTRA OS DADOS */
if(!$E_CAIXA){ ?>
<div style='padding: 10px; border-radius: .5rem; background-color: #FFFFFF'>
<h4><strong>Sumário de Orçamento</strong></h4>
<div id='sumario_orcamento'>Carregando...</div>
</div>


<script>

function carregarOcamentosSumario(target_obj){
	

	let orcamentos = {};
	let out_text = "<table class=' table-hover'><thead><th>Status</th><th>Total</th></thead><tbody>";
	$.ajax({

		url: "orcamento_crud.php?fila=sim",
		cache: false,
		type: 'GET',
		contentType: 'application/json; charset=utf-8',
		success: function(data){
			
			
			$.each(data.fila, function(i, obj){
				
				if(typeof(orcamentos['status_'+ obj.idstatus]) == 'undefined'){
					orcamentos['status_'+ obj.idstatus] = 1;
				}else{
					
					orcamentos['status_'+ obj.idstatus] += 1;
					
				}
				
			});
			
			
			$.each(json_status, function(i, iStatus){
				
				if(typeof(orcamentos['status_'+ iStatus.idstatus]) == 'undefined'){
					orcamentos['status_'+ iStatus.idstatus] = 0;
				}
				
				
				out_text += '<tr><td>'+ iStatus.destatus +'</td><td><span class="badge badge-pill badge-info">'+ orcamentos["status_"+ iStatus.idstatus] +'</span></td></tr>\n'; 
				 
				
			});
			
			mesagem_rodape = "<div style='font-style: italic; font-size: 0.9rem' class='mt-3'>* Os registros fechados quer dizer os fechados hoje, ou fechado em outra data e ainda não foram recebidos no caixa.</div>";
			$(target_obj).html(out_text +"</tbody></table>"+ mesagem_rodape);
			
		}
		
	});

}


carregarOcamentosSumario("#sumario_orcamento");

</script>
<?php } ?>