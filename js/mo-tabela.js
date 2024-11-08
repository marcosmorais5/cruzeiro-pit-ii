
/* FAZENDO PARSER EM COLUNAS DE TIPOS DISTINTOS
*
* Baseados em classes CSS, os dados de uma célula são traduzidos para um formato. Por exemplo, uma data que vem do banco de dados
* no formato yyyy-mm-dd é traduzida para o formato dd/mm/yyyy
*
* */
function filaOrcamentoSubstituirFila(total_geral){
	
	if(typeof(total_geral) == 'undefined') total_geral = 0.0;
	
	$(".linhas").each(function(i, lobj){
									
		$(this).find("td").each(function(){
			
			
			obj = $(this);
			conteudo_celula = $.trim(obj.text());
			
			/* Formatando células do tipo Dinheiro */
			if(obj.hasClass("valor_BRL")){
				
				obj.css("text-align", "right");
				
				/* CALCULANDO O TOTAL GERAL */
				total_geral += parseFloat(conteudo_celula);
				
				if(parseFloat(conteudo_celula) < 0){
					obj.css("color", "#FF0000");
				}
				
				conteudo_celula = "R$ "+ UTILS.formatarMoedaBRL(conteudo_celula);
				
			/* Formatando células do tipo Data */
			}else if(obj.hasClass("typeData")){
				
				if(conteudo_celula == "null"){
					
					conteudo_celula = "";
					
				}else{
					
					conteudo_celula = UTILS.toStringFromMySQL(conteudo_celula);
					
					
					diffDays = UTILS.diffDays(UTILS.getFormatDateTime(new Date()), conteudo_celula +" 00:00:00");
						
					diffDays = parseInt(diffDays);
					
					if(diffDays > 0){
						//conteudo_celula += "<br><span style='font-size: 0.8rem'>Ocorre em aproximadamente "+ parseInt(diffDays) +" dia(s)</span>";
						
					}else if(diffDays == 0){
						
						obj
							.css("color", "#0000FF").
							attr("title", "Fechado nas últimas 24 horas");
							
						//conteudo_celula += "<br><span style='font-size: 0.8rem'>Hoje</span>";
						
					}
						
				}
				
			/* Formatando células do tipo 'link' */
			}else if(!obj.hasClass("link_id")){
				
			 
				if(conteudo_celula == "-1" || conteudo_celula == "0") obj.addClass("font-italic");
				
				/* Convertendo o valor da célula, que é um ID, para o respectivo nome de uma hash
				* Exemplo: a célula "<td> class='link_id'>1</td>" se torma "<td> class='link_id'>John Doe</td>"
				* */
				if(obj.hasClass("linha_idcliente")) conteudo_celula = json_clientes["idcliente_" + conteudo_celula];
				if(obj.hasClass("linha_idmedico"))  conteudo_celula = json_medicos["idmedico_" + conteudo_celula];
				if(obj.hasClass("linha_idstatus"))  conteudo_celula = json_status_fila["idstatus_" + conteudo_celula];
				if(obj.hasClass("linha_idprocedimento"))  conteudo_celula = json_procedimento["idprocedimento_" + conteudo_celula];
				if(obj.hasClass("linha_idservico"))  conteudo_celula = json_servico["idservico_" + conteudo_celula];
				if(obj.hasClass("linha_idlateralidade"))  conteudo_celula = json_lateralidade["idlateralidade_" + conteudo_celula];
				if(obj.hasClass("linha_idopme"))  conteudo_celula = json_opme["idopme_" + conteudo_celula];
				if(obj.hasClass("linha_idtipopagamento"))  conteudo_celula = json_tipo_pagamento_fila["idtipopagamento_" + conteudo_celula];
				if(obj.hasClass("linha_idcraetedby"))  conteudo_celula = json_usuario["idusuario_" + conteudo_celula];

				
			}
			
			if(!obj.hasClass("link_id")) obj.html(conteudo_celula);
			
		});
		
	
	});
	
	return total_geral;
	
}





/** CONFIRMATION OF CUSTOMER PROCEDURES
 * 
 * This is a helper function. This action is usually executed when the analyst/user confirms that everything is ready
 * for the customer to execute the procedure. This option appears in the home screen for almost all user types. It does
 * not appear for the cashier.
 * 
 * */
function cofnirmarTudoOkProcedimento(){
	
	$(".cofirma-tudo-ok-procedimento").click(function(){
		
		PUT = $(this).attr("PUT");
		
		if(confirm("Você confirma que tudo está OK para a realização deste procedimento / exame? Esta operação não é reversível e este registro sumirá da sua fila de próximos eventos.")){
			
			$.ajax({
				
				url: "orcamento_crud.php?orcamento_tudo_ok=confirma",
				cache: false,
				type: 'PUT',
				contentType: 'application/json; charset=utf-8',
				success: function(data){
					
					console.log(data);
					
					if(data.response_code == 200){
						
						alert(data.response_msg);
						
						/* RECARREGA A FILA DO CAIXA EM 3 SEGUNDOS */
						setTimeout(function(){
							
							geraTabelaProximosEventos();
							
						}, 1000 * 3);
						
					}else{
						
						alert(data.response_msg);
						
					}
					
					
				},
				data: PUT
				
			});
			
		}
		
	});
	
}
			
/* FILA DE EXAMES / CIRURGIAS QUE ESTÃO NA FILA E SERÁ NECESSÁRIO ACOMPANHAR
* Esta função retorna os eventos / procedimentos / cirurgias que acontecerão nos próximos 15 dias.
* Isso mostra ao usuário / analista do sistema o que está por vir e ele pode ver se todos os
* requerimentos para a execução do procedimento estão prontos. Evitando, assim, retrabalho e melhoria de qualidade.
* */
function geraTabelaProximosEventos(target_id){
	
	
	if(typeof(target_id) == 'undefined') target_id = "#orcamentoProximosAcontecimentos";
	
	let numero_de_colunas = 15;
	let DEL = "\n";
	let arr_linhas = new Array();
	let tabela_fila = "<div class='table-responsive'>"+ DEL +
		"<table class='table table-sm table-striped table-bordered table-hover'>"+ DEL +
		"<thead>"+ DEL +
		"<tr>"+ DEL +
		"<th>N&ordm;</th>"+ DEL +
		"<th>Faltam (dias)</th>"+ DEL +
		"<th>Realização</th>"+ DEL +
		"<th>Cliente</th>"+ DEL +
		"<th>Procedimento</th>"+ DEL +
		"<th>Médico</th>"+ DEL +
		"<th>OPME</th>"+ DEL +
		"<th>Ação</th>"+ DEL +
		"<tr>"+ DEL +
		"</thead>"+ DEL +
		"<tbody>"+ DEL +
		"<!--conetudo-->"+ DEL +
		"</tbody>"+ DEL +
		"</table>"+ DEL +
		"</div>";
		
	let no_record = "<tr>"+
		"<td colspan='"+ numero_de_colunas +"'><br>Não existe nenhuma cirurgia que você agendou para os próximos 15 dias.<br><br></td>"+
		"</tr>";
	
	$.ajax({
		
		url: "orcamento_crud.php?fila_proximos=sim",
		cache: false,
		type: 'GET',
		contentType: 'application/json; charset=utf-8',
		success: function(data){
			
			$.each(data.fila_proximos, function(i, obj){
				
				
				/* CALCULANDO O NÚMERO DE DIAS QUE FALTA PARA A REALIZAÇÃO */
				conteudo_celula = obj.datarealizacao;
				conteudo_celula = UTILS.toStringFromMySQL(conteudo_celula) +" 00:00";

				
				
				
				diffDays = UTILS.diffDays(data.data_hoje, conteudo_celula);
				/** Convetendo para dias inteiros */
				diffDays =  diffDays.toFixed(0);




				/* MENSAGENS DE DIAS E COR DE LINHA */
				days_msg = diffDays +" dia(s)";
				if(diffDays < 0)  days_msg = "<strong>Há "+ ((-1) * diffDays) +" dia(s)</strong>";
				if(diffDays == 0) days_msg = "<strong>Hoje</strong>";
				if(diffDays == 1) days_msg = "<strong>Amanhã</strong>";
				
				linha_cor = "";
				if(diffDays < 2 ){
					
					linha_cor = "table-danger";
					
				}else if(diffDays <= 7){
					
					linha_cor = "table-warning";
					
				}
				
				
				let linha = "<tr class='linhas "+ linha_cor +"'>"+ DEL +
						"<td class='linha_cod link_id'><a href='orcamento_cadastro.php?cod="+ obj.cod +"'>Ver "+ obj.cod +"</a></td>"+ DEL +
						"<td class='linha_data "+ linha_cor +" link_id'>"+ days_msg +"</td>"+ DEL +
						"<td class='linha_data typeData'>"+ obj.datarealizacao +"</td>"+ DEL +
						"<td class='linha_idcliente'>"+ obj.idcliente +"</td>"+ DEL +
						"<td class='linha_idservico'>"+ obj.idservico +"</td>"+ DEL +
						"<td class='linha_idprocedimento'>"+ obj.idprocedimento +"</td>"+ DEL +
						"<td class='linha_idmedico'>"+ obj.idmedico +"</td>"+ DEL +
						"<td class='linha_idopme'>"+ obj.idopme +"</td>"+ DEL +
						"<td class='link_id'><button type='button' title='Clique aqui para informar que está tudo certo para este procedimento / exame.' class='btn btn-success cofirma-tudo-ok-procedimento' PUT='{\"cod\":"+ obj.cod +"}'>Tudo OK</button></td>"+ DEL +
						"</tr>"+ DEL;
					
					arr_linhas.push(linha);
			
			});
			
			if(arr_linhas.length == 0){
				arr_linhas.push(no_record)
			}
			
			/* GERANDO A TABELA */
			arr_linhas = tabela_fila.replace("<!--conetudo-->", arr_linhas.join("\n"));

			
			/* ADICIONAD O CONTEÚDNO NA DIV */
			$(target_id).html(arr_linhas);
			
			/* ADICIONA A AÇÃO DO BOTÃO */
			cofnirmarTudoOkProcedimento();
			
			/* SUBSTITUI OS IDs POR CONTEÚTDO */
			filaOrcamentoSubstituirFila(0);
			
			/* COLOCANDO O TEXTO DE SERVIÇO EM CIMA DE PROCEDIMENTO E APAGANDO A COLUNA SERVIÇO*/
			
			$(target_id).find("tr").each(function(){
				
				linha_idservico = $(this).find(".linha_idservico").html();
				linha_idprocedimento = $(this).find(".linha_idprocedimento").html();
				
				$(this).find(".linha_idprocedimento").html("<strong>"+ linha_idservico +"</strong><br>"+ linha_idprocedimento);
				
				
				
			});
		
			$(".linha_idservico").remove();
		}
	
	});
	
	
}
