/** Este é um arquivo helper para usar a API de gráficos do google 
 * As funções são auxiliares para a geração dos gráficos necessários nas sessões de dashboard
* */
(function ($) {
	
	/* CORES PADRÕES PARA OS GRÁFICOS */
    var DEFAULT_COLORS = ["#7cb5ec", "#434348", "#90ed7d", "#f7a35c", "#8085e9", "#f15c80", "#e4d354", "#2b908f", "#f45b5b", "#91e8e1"];

	/* CRITÉRIOS PARA VER SE A TABELA É VÁLIDA */
	var CHARTCRITERIA = {
		body: {
			required: true
		},
		head: {
			required: true
		},
		table: {
			regular: true
		}
	};
	
	function castTable(table, formatArray) {
	
		table = $.map(table, function (row) {
		
			return [castRow(row, formatArray)];
		
		});

		return table;
	}
	
	function castRow(row, formatArray) {

		row = row.slice(0, formatArray.length);

		row = $.map(row, function (cell, col) {
			return castString(cell, formatArray[col]);
		});

		return row;
		
	}
	
	function castString (original, format) {
	
		var casted;

		switch (format) {
			case 'number':
			casted = stringToNumber(original);
			break;
			
			case 'float':
			casted = parseFloat(original);
			break;

			case 'integer':
			casted = parseInt(stringToNumber(original));
			break;

			case '':
			casted = null;
			break;

			default:
			casted = original;
			break;
		}
		return casted;
	
	}

	function stringToNumber(string) {

		var number,
		converting = string;

		converting = converting.trim();
		converting = converting.replace(/\./g, '');
		converting = converting.replace(',', '.');
		
		try {
			number = parseFloat(converting);
		}
		catch(e) {
			console.error('stringToNumber(): Impossível converter ' + string + ' para número.');
			number = 0;
		}

		return number;
	
	}
	
	
	/* CARREGANDO O GOOGLE CHARTS */
	google.charts.load('current', {packages: ['corechart', 'treemap', 'gauge'], language: 'pt-BR'});
		

	$.fn.table2Gauge = function(options){

		var defaultOptions = {
			chartType: "Gauge",
			isStacked: true,
			width: 500,
			height: 300,
			redFrom: 90,
			redTo: 100,
			yellowFrom:75,
			yellowTo: 90,
			minorTicks: 5,
		};

		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);

		$(this).table2Chart(options);

	} 

	$.fn.table2StackedAreaChart = function(options){

		var defaultOptions = {
			chartType: "AreaChart",
			isStacked: true
		};

		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);

		$(this).table2Chart(options);

	} 

	$.fn.table2DonutChart = function(options){

		var defaultOptions = {
			chartType: "PieChart",
			pieHole: 0.4
		};

		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);

		$(this).table2Chart(options);

	}

	$.fn.table2AreaChart = function(options){
		
		var defaultOptions = {
			chartType: "AreaChart",
			};
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}

	$.fn.table2StackedColumnChart = function(options){
		
		var defaultOptions = {
			chartType: "ColumnChart",
			isStacked: true
			};
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}
	
	$.fn.table2LineChart = function(options){
		
		var defaultOptions = {
			chartType: "LineChart",
			pointSize: 5,
			//curveType: 'function',
			legend: { position: 'bottom' }
		};
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}
	
	$.fn.table2ColumnChart = function(options){
		
		var defaultOptions = {
			chartType: "ColumnChart",
			legend: { position: 'bottom' }
		};
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
        options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}
	
	$.fn.table2ColumnChartPercent = function(options){
		
		var defaultOptions = {
			chartType: "ColumnChart",
			isStacked: 'percent',
			legend: { position: 'bottom' }
		};
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
        options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}
	
	$.fn.table2PieChart = function(options){
		
		var defaultOptions = { chartType: "PieChart" };
		
		/* MESCLANDO AS OPÇÕES FORNECIDAS COM AS OPÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);
		
		$(this).table2Chart(options);
		
	}
	
	$.fn.table2Chart = function (options) {


		var defaultOptions = {

			chartType: "PieChart",
			colors: DEFAULT_COLORS,
			width: "100%",
			height: "350px"

		};


		/* MESCLA OPÇÕES DO PARÂMETRO COM AS OÇÕES PADRÕES */
		options = $.extend({}, defaultOptions, options);

		return this.each(function () {

			var table = $(this);

			if (!checkTable(table, CHARTCRITERIA)) {
				
				console.error('A tabela não está no formato esperado para gerar um gráfico. Tabela: ', table , ' Critérios', CHARTCRITERIA);
				return false;
				
			}

			/* CAPTURA TODAS COLUNAS, LEVA EM CONSIDERAÇÃO A 1 COMO 'STRING' AS OUTRAS COMO 'NUMBER'*/
			allColumnsDataTypes = theadDataTypes(table);

			/* CAPTURA O CONTEÚDO DO BODY DA TABELA */
			bodyCOntent = castTable(tbodyToArray(table), allColumnsDataTypes);

			/* CAPTURA O CONTEÚDO DO HEADER DA TABELA*/
			headToArray = theadToArray(table);

			/* ADICIONA O HEADER AO CONTEÚDO */
			bodyCOntent.unshift(headToArray);

			/* GERANDO O ELEMENTO ONDE O GRÁFICO SERÁ POSIDIONADO*/
			let generate_chart_id = "ID_"+ (new Date()).getTime();
			
			/* CRIA UM ELEMENTO APÓS A TABELA ATUAL */
			table.after("<div id='"+ generate_chart_id +"' style='height: "+ options.height +"; width: "+ options.width +"'>TESTE</div>");
			
			/* ENVELOPA A TABELA COM UM DIV PARA NÃO MOSTRAR MAIS NA TELA */
			table.wrap('<div class="sr-only"></div>');

			
			var OPTIONS_TARGET = {"data": bodyCOntent, "target_obj": generate_chart_id, "chartType": options.chartType, "options": options};

			google.charts.setOnLoadCallback(function(){

				drawChart(OPTIONS_TARGET);

			});



		});
		
	};
	
		
	function drawChart(OPTIONS) {

			target_obj = OPTIONS.target_obj;
			data = OPTIONS.data;
			chartType = OPTIONS.chartType;
			options = OPTIONS.options;
			
			console.log("data = "+ JSON.stringify(data));
			
			var chart_data = google.visualization.arrayToDataTable(data);

			//var options = {
			//  title: 'My Daily Activities',
			//  colors: DEFAULT_COLORS
			//  
			//};
			
			console.log("chartType = '"+ chartType +"', target_obj = '"+ target_obj +"'")
			console.log("options = "+ JSON.stringify(options) );
			
			var chart = new google.visualization[chartType]( document.getElementById(target_obj) );
			
			chart.draw(chart_data, options);
	}
	
	function tbodyToArray(tableElement, cols = 0) {

		var rows,
		tableArray = [];

		// Pega as linhas do tbody da tabela
		rows = tableElement.find('tbody tr');

		// Para cada linha da tabela
		rows.map(function (rowCount, row) {

			var cells = $(row).find('th, td');

			// Se foi especificado um número limite de colunas, filtra as colunas por esse número
			if (cols > 0) {
			cells = cells.filter(':lt('+ cols +')');
			}

			tableArray[rowCount] = [];

			cells.map(function (colCount, cell) {

				tableArray[rowCount][colCount] = $(cell).text();
			
			});
		});

		return tableArray;
		
	}
	
	function theadDataTypes(tableElement, cols = 0) {
	
		var rows,tableArray = [];
		
		// Pega as linhas do tbody da tabela
		rows = tableElement.find('thead tr');
		
		/* para incrementar o label */
		iSum = 0;
		
		// Para cada linha da tabela
		rows.map(function (rowCount, row) {
		
			var cells = $(row).find('th, td');
			
			// Se foi especificado um número limite de colunas, filtra as colunas por esse número
			if (cols > 0) {
			cells = cells.filter(':lt('+ cols +')');
			}
			
			
			cells.map(function (colCount, cell) {
				
				
				
				
				
				if($(cell).attr("type") != null){
					
					localType = $(cell).attr("type");
					
				}else{
					
					if(colCount == 0){
						localType = "string";
					}else{
						localType = "number";
					}
					
				}
				
				
				tableArray[colCount + iSum] = localType;
				
				//if($(cell).attr("params") != null){
				//	
				//	if(typeof($(cell).attr("params") ) != 'undefined'){
				//		
				//		iSum++;
				//		tableArray[colCount + iSum] = JSON.parse($(cell).attr("params"));
				//		iSum++;
				//	
				//	}
				//	
				//	
				//	
				//}
					
					
			});
			
		});
		
		
		
		return tableArray;
		
	}
	
	function theadToArray(tableElement, cols = 0) {

        var rows,
            tableArray = [];

        // Pega as linhas do tbody da tabela
        rows = tableElement.find('thead tr');

        // Para cada linha da tabela
        rows.map(function (rowCount, row) {

            var cells = $(row).find('th, td');

            // Se foi especificado um número limite de colunas, filtra as colunas por esse número
            if (cols > 0) {
                cells = cells.filter(':lt('+ cols +')');
            }

            

            cells.map(function (colCount, cell) {

                tableArray[colCount] = $(cell).text();
            });
        });

		

        return tableArray;
    }
	
	function checkTable (table, criteria) {

        // Verifica se a tabela é "regular", ou seja, se todas as linhas possuem o mesmo número de células
        // Aqui, tanto TH quanto TD são considerados células.
        // Retorna:
        // - true caso a tabela seja regular
        // - false caso não seja regular
        function isRegular (table) {
            var cells,
                regular = true;

            table.find('tr').each(function (index) {

                // Na primeira iteração, apenas armazena a quantidade de células da linha
                // Esse número será utilizado depois na comparação com todas as demais linhas
                if (index === 0) {
                    cells = $(this).find('th, td').length;
                }
                else {
                    // Se o número de células de qualquer linha for diferente do número de células da primeira linha
                    if ($(this).find('th, td').length !== cells) {
                        // Informa que a tabela não é regular
                        regular = false;
                        // Interrompe o loop 'each'
                        return false;
                    }
                }
            });

            return regular;
        }

        var actual = {
                table: {},
                head: {},
                body: {}
            };

        // -- Obtenção das características da tabela -- //
        // Verifica se há tbody
        actual.body.isPresent = (table.find('tbody').length !== 0);
        // Verifica quantas linhas há no tbody
        actual.body.rows = table.find('tbody tr').length;
        // Verifica se há thead
        actual.head.isPresent = (table.find('thead').length !== 0);
        // Verifica quantas linhas há no thead
        actual.head.rows = table.find('thead tr').length;
        // Verifica se a tabela é regular
        actual.table.regular = isRegular(table);

        // -- Verificações -- //
        // Se body é obrigatório
        if (criteria.body.required) {
            // Retorna false se body não existir
            if (!actual.body.isPresent) return false;
        }
        // Se foi definido um número de linhas no body
        if (criteria.body.rows) {
            // Retorna false se o número de linhas não corresponder ao esperado.
            if (criteria.body.rows !== actual.body.rows) return false;
        }
        // Se head é obrigatório
        if (criteria.head.required) {
            // Retorna false se head não existir.
            if (!actual.head.isPresent) return false;
        }
        // Se foi definido um númeor de linhas no head
        if (criteria.head.rows) {
            // Retorna false se o número de linhas não corresponder ao esperado.
            if (criteria.head.rows !== actual.head.rows) return false;
        }
        // Se a tabela deve ser regular
        if (criteria.table.regular) {
            // Retorna false se não for regular.
            if (!actual.table.regular) return false;
        }
        // Retorna true se chegou até aqui, passando por todos os testes.
        return true;
    }
	
})(jQuery);