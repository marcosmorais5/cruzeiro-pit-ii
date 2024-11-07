<?php
require_once("inc/session.php");

if($_SESSION['grupo'] == "ADMINISTRADOR" || $_SESSION['grupo'] == "ADMINISTRADOR_MESTRE"){

?>
var DAYS_CHART = ["Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"];
var MONTHS_CHART = ["", "Janeiro", "Feveireo", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
		
		
		
		function geraGraficoSemana(options){
			
			let defaultOptions = {
				target_id: "",
				title: "",
				week: 0,
				
			}
			
			options = $.extend({}, defaultOptions, options);
			
			var tabela = "<table id='"+ options.target_id +"'>";
			
			$.ajax({
					
				url: "relatorio_crud.php?relatorio_dados=produtividade_semana&semana="+ options.week,
				cache: false,
				type: 'GET',
				contentType: 'application/json; charset=utf-8',
				success: function(data){
					
					
					
					$.each(data.grafico, function(i, obj){
						
						if(i == 0){
							
							tabela += "<thead>"
							tabela += "<tr>"
							tabela += "<th>"+ obj[0] +"</th>"
							tabela += "<th type='float' params='{ role: \"annotation\"}'>"+ obj[1] +"</th>"
							tabela += "<th type='float' params='{ role: \"annotation\"}'>"+ obj[2] +"</th>"
							tabela += "</tr>"
							tabela += "</thead>"
							tabela += "<tbody>"
							
						}else{
							
							tabela += "<tr>"
							tabela += "<td>"+ DAYS_CHART[obj[0]] +"</td>"
							tabela += "<td>"+ obj[1] +"</td>"
							tabela += "<td>"+ obj[2] +"</td>"
							tabela += "</tr>"
							
						}
						
						
					});
					
					tabela += "</tbody><table>";
					
					
					
					$("#"+ options.target_id)
						.html(tabela)
						.table2ColumnChart({"width": "100%", "title": options.title,
							vAxis: {
								title: "Valor em R$",
								format: "currency",
								minValue: 0,
								viewWindow: {
									min: 0
								}
							}
						
					});
				}		
			});
			
		}
		
		
		function geraGraficoAno(options){
			
			let defaultOptions = {
				target_id: "",
				title: "",
				ano: 0,
				stacked: true
				
			}
			
			options = $.extend({}, defaultOptions, options);
			
			var tabela = "<table id='"+ options.target_id +"'>";
			
			$.ajax({
					
				url: "relatorio_crud.php?relatorio_dados=mes_a_mes&semana="+ options.ano,
				cache: false,
				type: 'GET',
				contentType: 'application/json; charset=utf-8',
				success: function(data){
					
					
					
					$.each(data.grafico, function(i, obj){
						
						if(i == 0){
							
							tabela += "<thead>"
							tabela += "<tr>"
							tabela += "<th>"+ obj[0] +"</th>"
							tabela += "<th type='float' params='{ role: \"annotation\"}'>"+ obj[1] +"</th>"
							tabela += "<th type='float' params='{ role: \"annotation\"}'>"+ obj[2] +"</th>"
							tabela += "</tr>"
							tabela += "</thead>"
							tabela += "<tbody>"
							
						}else{
							
							tabela += "<tr>"
							tabela += "<td>"+ MONTHS_CHART[obj[0]] +"</td>"
							tabela += "<td>"+ obj[1] +"</td>"
							tabela += "<td>"+ obj[2] +"</td>"
							tabela += "</tr>"
							
						}
						
						
					});
					
					tabela += "</tbody><table>";
					
					
					var vAxis = {title: "Valor em R$", format: "currency", tooltip: { isHtml: true }};
					
					$("#"+ options.target_id)
						.html(tabela);
						
					if(options.stacked)   $("#"+ options.target_id).table2StackedColumnChart({"width": "100%", "title": options.title, "vAxis": vAxis});
					if(!options.stacked) $("#"+ options.target_id).table2ColumnChart({
							"vAxis": vAxis,
							"width": "100%", "title": options.title, "colors": ['#007EAC', '#05CCEF', '#BB0C43', '#E10E51', '#FF7295', '#F39125', '#FFC178', '#FAC915', '#007C7C', '#39C5BE', '#00E5D7', '#C43BB0', '#1C91D1', '#0FA87F', '#00B5CF', '#FFDE6F', '#B3009C', '#FF9C4A']});
				}		
			});
			
		}
		
		
		function geraOntemHoje(options){
			
			let defaultOptions = {
				target_id: "",
				title: "",
				day: 0,
				dia_padrao: "hoje"
				
			}
			
			options = $.extend({}, defaultOptions, options);
			
			var tabela = "<table id='"+ options.target_id +"'>";
			
			$.ajax({
					
				url: "relatorio_crud.php?relatorio_dados=ontem_hoje",
				cache: false,
				type: 'GET',
				contentType: 'application/json; charset=utf-8',
				success: function(data){
					
							tabela += "<thead>"
							tabela += "<tr>"
							tabela += "<th>Serviço</th>"
							if(options.dia_padrao != "hoje") tabela += "<th type='float' params='{ role: \"annotation\"}'>Ontem</th>"
							if(options.dia_padrao == "hoje") tabela += "<th type='float' params='{ role: \"annotation\"}'>Hoje</th>"
							tabela += "</tr>"
							tabela += "</thead>"
							tabela += "<tbody>"
					
					$total = 0;
					$.each(data.grafico, function(i, obj){
						
							$total += parseFloat( (options.dia_padrao == "hoje" ? obj.hoje : obj.ontem) );
							
							tabela += "<tr>"
							tabela += "<td>"+ obj.servico +"</td>"
							if(options.dia_padrao != "hoje") tabela += "<td>"+ obj.ontem +"</td>"
							if(options.dia_padrao == "hoje") tabela += "<td>"+ obj.hoje +"</td>"
							tabela += "</tr>"
						
						
					});
					
					tabela += "</tbody><table>";
					
					
					$("#"+ options.target_id)
						.html(tabela);
					
					/** Gerando o gráfico, se houver dados para gerar o gráfico de pizza */
					if($total > 0) $("#"+ options.target_id).table2PieChart({
						"width": "100%", "title": options.title, legend: "none",
						vAxis: {title: "Valor em R$"}
					});
					
					        
					
					//if($total == 0) $("#"+ options.target_id).table2ColumnChart({
					//	"width": "100%", "title": options.title, legend: "bottom",
					//	vAxis: {title: "Valor em R$ "}
					//});
				}		
			});
			
		}
		
		

<?php
}

?>