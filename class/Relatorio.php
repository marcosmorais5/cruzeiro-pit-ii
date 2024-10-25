<?php 
if(!class_exists('Banco')) require_once('Banco.php');
if(!class_exists('Orcamento')) require_once('Orcamento.php');

class Relatorio{

	/* RETORNA EM VALORES O NÚMERO DE 'EXAMES' E 'CIRURGIAS' FECHADOS ONTEM E HOJE
	* */
	public function getValoresServicoOntemHoje(){
		
		$sql = "SELECT TEMP.SERVICO as 'servico', SUM(TEMP.TOTAL_ONTEM) as 'ontem', SUM(TEMP.TOTAL_HOJE) as 'hoje' FROM (

			-- SELECIONA TODOS OS DADOS, PARA EXIBIR MESMO SE NÃO EXISTIR REGISTROS PARA HOJE
			SELECT servico AS SERVICO, 0 as TOTAL_HOJE, 0 AS TOTAL_ONTEM FROM tb_servico

			UNION

			SELECT
				S.SERVICO AS SERVICO,
				SUM(CASE DATE(B.DATE_CLOSED) WHEN DATE(NOW()) THEN VALOR_OPERACAO ELSE  0 END) AS TOTAL_HOJE,
				SUM(CASE DATE(B.DATE_CLOSED) WHEN DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN VALOR_OPERACAO ELSE  0 END) AS TOTAL_ONTEM
			FROM tb_servico S
			LEFT JOIN tb_cirurgia B ON S.id_servico = B.id_servico
			WHERE  DATE(B.DATE_CLOSED) IN (DATE(NOW()), DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)))
			AND B.DATE_DELETED IS NULL AND B.caixa_ok = 'Y'
			GROUP BY S.SERVICO
			
		) AS TEMP
		GROUP BY TEMP.SERVICO";
		
		return $this->returnGeneric($sql);
		
	}
	
	
	public function getSqlSemana($senana, $overall = false){
		
		$semana = " WEEK( DATE_SUB(NOW(), INTERVAL ". ((int)$senana) ." WEEK),  1) ";
		$ano = " YEAR( DATE_SUB(NOW(), INTERVAL ". ((int)$senana) ." WEEK) ) ";
		
		$campos_group = "temp2.servico, temp2.week_day";
		if($overall === true){
			$campos_group = "temp2.servico";
		}
		$sql = "SELECT servico, week_day AS week_day, CAST(SUM(total) as decimal(10, 2)) AS total from
			(
				SELECT servico, days.week_day, days.total FROM tb_servico,
				(
					SELECT 0 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 1 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 2 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 3 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 4 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 5 AS 'week_day', 0 AS 'total'
					UNION
					SELECT 6 AS 'week_day', 0 AS 'total'
				) AS days

				union

				SELECT
					S.SERVICO as 'servico',
					weekday(DATE_CLOSED) AS 'week_day',
					SUM(valor_operacao) as 'total'
				FROM tb_cirurgia C
				RIGHT JOIN tb_servico S ON S.ID_SERVICO = C.ID_SERVICO
				WHERE DATE_CLOSED IS NOT NULL
				AND DATE_DELETED IS NULL
				AND caixa_ok = 'Y'
				AND WEEK(DATE_CLOSED,  1) = ". $semana ." AND YEAR(DATE_CLOSED) = ". $ano ."
				GROUP BY S.SERVICO, weekday(DATE_CLOSED)
			) as temp2
			GROUP BY ". $campos_group ."
			";
			
		return $sql;
		
	}
	
	public function getServicoAnoPorMes($ano = null){
		
		$ano = (int)$ano;
		$ano_atual = "";
		if($ano == 0){
			
			$ano_atual = " WHERE TEMP.mes <= MONTH(NOW()) ";
			
		}
		$sql = "SELECT TEMP.servico, TEMP.mes, SUM(TEMP.total) AS total FROM
		(
			SELECT servico, ALL_MONTHS.mes, ALL_MONTHS.total FROM tb_servico,
			(
				SELECT 1 AS 'mes', 0 as total
				UNION
				SELECT 2 AS 'mes', 0 as total
				UNION
				SELECT 3 AS 'mes', 0 as total
				UNION
				SELECT 4 AS 'mes', 0 as total
				UNION
				SELECT 5 AS 'mes', 0 as total
				UNION
				SELECT 6 AS 'mes', 0 as total
				UNION
				SELECT 7 AS 'mes', 0 as total
				UNION
				SELECT 8 AS 'mes', 0 as total
				UNION
				SELECT 9 AS 'mes', 0 as total
				UNION
				SELECT 10 AS 'mes', 0 as total
				UNION
				SELECT 11 AS 'mes', 0 as total
				UNION
				SELECT 12 AS 'mes', 0 as total
			) AS ALL_MONTHS

			UNION

			SELECT S.SERVICO, MONTH(DATE_CLOSED) AS 'mes', SUM(valor_operacao) as 'total' FROM tb_cirurgia C
			RIGHT JOIN tb_servico S ON S.ID_SERVICO = C.ID_SERVICO
			WHERE DATE_CLOSED IS NOT NULL AND caixa_ok = 'Y'
			AND YEAR(DATE_CLOSED) = YEAR( DATE_SUB(NOW(), INTERVAL ". $ano ." YEAR ))
			GROUP BY S.SERVICO, MONTH(DATE_CLOSED)

		) AS TEMP
		". $ano_atual ."
		GROUP BY TEMP.servico, TEMP.mes";
		
		
		
		return $this->returnGeneric($sql);
		
	}
	
	public function returnGeneric($sql){
		
		$ban = new Banco();
		$ban->setSql($sql);

		$arr_all_objects = $ban->getResultSelect();

		
		$ban->fecharConexao();
		
		return $arr_all_objects;
		
	}
	
	public function getValoresOverallServicoSemana(){
		
		$sql = Relatorio::getSqlSemana(0, true);
		
		return $this->returnGeneric($sql);
		
	}
	
	public function getValoresServicoSemana($semana = null){
		
		$sql = Relatorio::getSqlSemana((int)$semana, false);
		
		return $this->returnGeneric($sql);
		
	}
	
	
	
	
	public function getOrcamentosRelatorio(){
		
		$orc_obj = new Orcamento();
		return $orc_obj->getRelatorioFiltro();
		
	}
	
	public function getValoresServicoSemanaMatriz($semana = null){
		
		$arr = $this->getValoresServicoSemana($semana);
		
		$pre_render = array();

		
		foreach($arr as $obj){
			
			$pre_render[$obj['servico']][$obj['week_day']] = $obj['total'];
			
		}
		
		
		return $this->transcreverLinhasParaColunas($pre_render);
		
	}
	
	public function transcreverLinhasParaColunas($pre_render, $label = "Dia", $use_val = false){
		
		$arr_keys = array_keys($pre_render);
		
		/* CRIANDO O HEADER DA TABELA */
		$pre_matriz = array(array($label));
		foreach($arr_keys as $key_obj){
			array_push($pre_matriz[0], $key_obj);
		}

		
		if($use_val){
			
			foreach(array_keys($pre_render[$arr_keys[0]]) as $i => $val){
				$pre_matriz[$i + 1] = array($val);
				
				foreach($arr_keys as $key_obj){
					
					array_push($pre_matriz[$i + 1], $pre_render[$key_obj][$val] );
					
				}
			}
			
		}else{
			
			for($i = 0; $i < sizeof( array_keys($pre_render[$arr_keys[0]]) ); $i++){
				$pre_matriz[$i + 1] = array($i);
				
				foreach($arr_keys as $key_obj){
					array_push($pre_matriz[$i + 1], $pre_render[$key_obj][$i] );
				}
			}
			
		}
		

		
		return $pre_matriz;
		
	}
	
	
	public function getValoresMesesMatriz($ano = null){
		
		$arr = $this->getServicoAnoPorMes($ano);
		
		$pre_render = array();

		
		foreach($arr as $obj){
			
			$pre_render[$obj['servico']][$obj['mes']] = $obj['total'];
			
		}

		
		return $this->transcreverLinhasParaColunas($pre_render, "Mês", true);
		
	}
	
	
}

?>