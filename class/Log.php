<?php 
if(!class_exists('Util')) require_once('Util.php');

Class Log{

	public static $LOG_KEY_JAVASCRIPT = "JAVASCRIPT";
	
	public function imprimirArray($arr){
		
		if(is_array($arr)){
			
			$arr = array_values($arr);
			return implode(", ", $arr);
			
		}else{
			return $arr;
		}
		
	}
	
	public function salvarJavascriptLog($msg){
		//log_track_javascript.php
		
		$ban = new Banco();
		$sql = "INSERT INTO `". $ban->getDB() ."`.`ged_032t_log_programa`
			(`pkni_p032t_cod_usuario`,
			`atsv_p032t_tipo`,
			`atdt_p032t_criado`,
			`atsv_p032t_descricao`)
			VALUES
			(". $_SESSION['cod_usuario'] .",
			'". Log::$LOG_KEY_JAVASCRIPT ."',
			NOW(),
			'". str_replace("\'", "\'", $msg)."')";
			
		$ban->setSql($sql);
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		
		return $ret;

	}
	
	public function log2file($msg, $log_name = null){
		
		return Log::log2fileGeneric("[LOG_LEVEL_ALL] ". $msg, $log_name, LOG_LEVEL_ALL);
		
	}
	
	
	public function trace($msg, $log_name = null){
		return Log::log2fileTRACE($msg, $log_name = null);
	}
	public function info($msg, $log_name = null){
		return Log::log2fileINFO($msg, $log_name = null);
	}
	public function warning($msg, $log_name = null){
		return Log::log2fileWARNING($msg, $log_name = null);
	}
	public function error($msg, $log_name = null){
		return Log::log2fileERROR($msg, $log_name = null);
	}
	public function fatal($msg, $log_name = null){
		return Log::log2fileFATAL($msg, $log_name = null);
	}
	
	/*** ARQUIVOS DE LOG COM PÁGINA QUE A CHAMOU */
	public function tracePage($msg, $log_name = null){
		return Log::trace(Util::getPaginaAtual() .":: ". $msg, $log_name);
	}
	public function infoPage($msg, $log_name = null){
		return Log::info(Util::getPaginaAtual() .":: ". $msg, $log_name);
	}
	public function warningPage($msg, $log_name = null){
		return Log::warning(Util::getPaginaAtual() .":: ". $msg, $log_name);
	}
	public function errorPage($msg, $log_name = null){
		return Log::error(Util::getPaginaAtual() .":: ". $msg, $log_name);
	}
	public function fatalPage($msg, $log_name = null){
		return Log::fatal(Util::getPaginaAtual() .":: ". $msg, $log_name);
	}
	
	public function log2fileTRACE($msg, $log_name = null){
		
		if(!LOG_LEVEL_TRACE && !LOG_LEVEL_ALL) return false;
		$tipo_erro = Log::getErroTipo(LOG_LEVEL_TRACE, "LOG_LEVEL_TRACE");
		return Log::log2fileGeneric($tipo_erro . $msg, $log_name, LOG_LEVEL_TRACE || LOG_LEVEL_ALL);
		
	}
	
	public function log2fileINFO($msg, $log_name = null){
		
		if(!LOG_LEVEL_INFO && !LOG_LEVEL_TRACE && !LOG_LEVEL_ALL) return false;
		$tipo_erro = Log::getErroTipo(LOG_LEVEL_INFO, "LOG_LEVEL_INFO");
		return Log::log2fileGeneric($tipo_erro . $msg, $log_name, LOG_LEVEL_INFO || LOG_LEVEL_ALL);
		
	}
	
	public function log2fileWARNING($msg, $log_name = null){
		
		if(!LOG_LEVEL_WARNING && !LOG_LEVEL_INFO && !LOG_LEVEL_TRACE && !LOG_LEVEL_ALL) return false;
		$tipo_erro = Log::getErroTipo(LOG_LEVEL_WARNING, "LOG_LEVEL_WARNING");
		return Log::log2fileGeneric($tipo_erro . $msg, $log_name, LOG_LEVEL_WARNING || LOG_LEVEL_ALL);
		
	}
	
	public function log2fileERROR($msg, $log_name = null){
		
		if(!LOG_LEVEL_ERROR && !LOG_LEVEL_WARNING && !LOG_LEVEL_INFO && !LOG_LEVEL_TRACE && !LOG_LEVEL_ALL) return false;
		$tipo_erro = Log::getErroTipo(LOG_LEVEL_ERROR, "LOG_LEVEL_ERROR");
		return Log::log2fileGeneric($tipo_erro . $msg, $log_name, LOG_LEVEL_ERROR || LOG_LEVEL_ALL);
		
	}
	
	public function log2fileFATAL($msg, $log_name = null){
		
		if(!LOG_LEVEL_FATAL && !LOG_LEVEL_ERROR && !LOG_LEVEL_WARNING && !LOG_LEVEL_INFO && !LOG_LEVEL_TRACE && !LOG_LEVEL_ALL) return false;
		$tipo_erro = Log::getErroTipo(LOG_LEVEL_FATAL, "LOG_LEVEL_FATAL");
		return Log::log2fileGeneric($tipo_erro . $msg, $log_name, LOG_LEVEL_FATAL || LOG_LEVEL_ALL);
		
	}
	
	public function getErroTipo($tipo, $nome){
		
		$arr_tipo = array();
		if(LOG_LEVEL_ALL) $arr_tipo[] = "LOG_LEVEL_ALL";
		if($tipo) $arr_tipo[] = str_replace("LOG_LEVEL_", "", $nome);
		
		return "[". implode(" & ", $arr_tipo) ."] ";
		
	}
	
	public function log2fileGeneric($msg, $log_name = null, $tipo_log){
		
		if(LOG_NAME == "LOG_NAME"){
			if($log_name === null) $log_name = "../log/sigo-default-". date("Ymd") .".log";
			
			/** NÃO GERA LOG SE A VARÍAVEL 'LOG_NAME' NÃO ESTIVER ATIVA */
			return false;
			
		}else{
			if($log_name === null) $log_name = LOG_NAME;
		}
		
		if($log_name === null) $log_name = "../log/sigo-default.log";
		$msg = str_replace("\r", "", $msg);
		$msg = str_replace("\n", " ", $msg);
		$msg = str_replace("\t", " ", $msg);
		
		$msg = date("c") . " [IP=".  $_SERVER['REMOTE_ADDR'] .", USER(". ((int)$_SESSION['cod_usuario']) .", '". $_SESSION['usuario'] ."') ] ". $msg ."\r\n";
		
		if($tipo_log) return @error_log($msg, 3, $log_name);
		return false;
		
	}
	
	/** Método para remover logs anteriores a X dias
	* @LOG_APAGA_PERIODO --> variável global que indica o número de dias que os logs devem ser mantidos, se não for definida o valor default é '1'
	* @LOG_DIRECTORY --> Diretório que será monitorado de onde serão lidos os logs antigos.
	* */
	public function removerLogsAntigos(){
		
		$dias_apagar = LOG_APAGA_PERIODO;
		if((int)$dias_apagar == 0) $dias_apagar = 1;
		
		$dia = 60 * 60 * 24;
		$dias_30 = $dia * $dias_apagar;

		$arquivos_de_log = @scandir(LOG_DIRECTORY);
		
		/* SE HOUVER ARQUIVOS NO DIRETÓRIO */
		if($arquivos_de_log){
			Log::info("[Log->removerLogsAntigos] >>> INICIO rotina para excluir logs [ diretorio = ". LOG_DIRECTORY .", periodo_para_apagar = ". $dias_apagar ." dia(s)] [ Script name = ". $_SERVER["SCRIPT_NAME"] ."]");
			
			foreach($arquivos_de_log as $val){
				$val_arr = explode(".", $val);
				
				/* LENDO ARQUIVOS SOMENTE DE EXTENSÃO 'LOG' */
				if($val_arr[count($val_arr) - 1] == "log"){
					
					$LOG_NAME = LOG_DIRECTORY . "/". $val;
					$arquivo_antigo = ($dias_30 < (mktime() - filemtime($LOG_NAME)));
					$tempo_antigo = (int)((mktime() - filemtime($LOG_NAME))/ 60 / 60 / 24);
					//$mod_tempo_antigo = $tempo_antigo % 1;
					//$tempo_antigo = $tempo_antigo - $mod_tempo_antigo;
					
					
					Log::info("[Log->removerLogsAntigos] Arquivo de log identificado: [". $val ."] [Para excluir = ". ($arquivo_antigo ? "SIM": "NAO") ."] [ Dias antigo = ". $tempo_antigo ."]");
					
					/* REMOVENDO LOGS ANTIGOS - ANTERIORES A 30 DIAS */
					if($arquivo_antigo){
						
						$rem = @unlink($LOG_NAME);
						
						if($rem){
							Log::info("[Log->removerLogsAntigos] Arquivo removido com sucesso: [". LOG_DIRECTORY ."][". $val ."]");
						}else{
							Log::error("[Log->removerLogsAntigos] Erro ao tentar excluir o arquivo: [". LOG_DIRECTORY ."][". $val ."]");
						}
					}
					
				}
			}
			Log::info("[Log->removerLogsAntigos] >> FIM rotina para excluir logs [ diretorio = ". LOG_DIRECTORY ."]");
		}
	}
	
	/* Método para remover todos os LOGs do diretório principal e de outros diretórios, por exemplo,
	* logs que são gerados nos diretórios padrão do programa
	* */
	public function removerLogsAntigosV2(){
		
		Log::info("[Log->removerLogsAntigosV2] >>> INICIO Excluidno logs remanescentes");
		
		/** Todos os diretórios de onde os logs sertão apagados
		* Partindo do caminho de log '<root>/log' e indo até as demais pastas
		* */
		$array = array("..", "../class");
		$default_log_dir = LOG_DIRECTORY;
		
		for($i = 0; $i < count($array); $i++){
			Log::info("[Log->removerLogsAntigosV2] >>> Tentando entrar no diretorio [dir = '". $default_log_dir ."/". $array[$i] ."']");
			Log::removerLogsAntigosPastaV2($default_log_dir ."/". $array[$i]);
		}
		
		Log::info("[Log->removerLogsAntigosV2] >>> FIM Excluidno logs remanescentes");
		
	}
	
	/* Método que apagada todos os arquivos de LOG para um diretório fornecido
	* */
	public function removerLogsAntigosPastaV2($diretorio){
		
		$dias_apagar = LOG_APAGA_PERIODO;
		
		/** DIAS PADRÃO PARA EXCLUIR LOGS: 1 DIA */
		if((int)$dias_apagar == 0) $dias_apagar = 1;
		
		//$dia = 60 * 60 * 24;
		//$dias_30 = $dia * $dias_apagar;
		$dias_30 = Log::getSegundosDia($dias_apagar);

		$arquivos_de_log = @scandir($diretorio);
		
		/* SE HOUVER ARQUIVOS NO DIRETÓRIO */
		if($arquivos_de_log){
			Log::info("[Log->removerLogsAntigosPastaV2] >>> INICIO rotina para excluir logs [ diretorio = ". $diretorio .", periodo_para_apagar = ". $dias_apagar ." dia(s)] [ Script name = ". $_SERVER["SCRIPT_NAME"] ."]");
			
			foreach($arquivos_de_log as $val){
				$val_arr = explode(".", $val);
				
				/* LENDO ARQUIVOS SOMENTE DE EXTENSÃO 'LOG' */
				if($val_arr[count($val_arr) - 1] == "log"){
					
					$LOG_NAME = $diretorio . "/". $val;
					$arquivo_antigo = ($dias_30 < (mktime() - filemtime($LOG_NAME)));
					Log::info("[Log->removerLogsAntigosPastaV2] Arquivo de log identificado: [". $val ."] [Para excluir = ". ($arquivo_antigo ? "SIM": "NAO") ."] [ Dias antigo = ". (int)((mktime() - filemtime($LOG_NAME))/ 60 / 60 / 24) ."]");
					
					/* REMOVENDO LOGS ANTIGOS - ANTERIORES A x DIAS */
					if($arquivo_antigo){
						
						$rem = @unlink($LOG_NAME);
						
						if($rem){
							Log::info("[Log->removerLogsAntigosPastaV2] Arquivo removido com sucesso: [". $diretorio ."][". $val ."]");
						}else{
							Log::error("[Log->removerLogsAntigosPastaV2] Erro ao tentar excluir o arquivo: [". $diretorio ."][". $val ."]");
						}
					}
					
				}
			}
			Log::info("[Log->removerLogsAntigosPastaV2] >> FIM rotina para excluir logs [ diretorio = ". $diretorio ."]");
		}
	
	}
	
	/* Retorna o número de segundos de x dias
	* */
	public function getSegundosDia($dias_apagar){
		
		if((int)$dias_apagar <= 0) $dias_apagar = 1;
		
		$dia = 60 * 60 * 24;
		$dias_30 = $dia * $dias_apagar;
		return $dias_30;
		
	}
	
	
}
?>