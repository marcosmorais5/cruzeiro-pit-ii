<?php

if(!class_exists("Code")) require_once("Code.php");
if(!class_exists("Log")) require_once("Log.php");
if(!class_exists("Banco")) require_once("Banco.php");

Class Util{

	/** Retorna se a variável 'tipo' [POST|GET|Code] tem valor de 'atualizacao'
	* return boolean
	* */
	public static function isUpdate(){
		return (Code::getVar("tipo") == "atualizacao" || $_POST['tipo'] == "atualizacao" || $_GET['tipo'] == "atualizacao");
	}
	/** Retorna se a variável 'tipo' [POST|GET|Code] tem valor de 'excluir'
	* return boolean
	* */
	public static function isDelete(){
		return (Code::getVar("tipo") == "excluir" || $_POST['tipo'] == "excluir" || $_GET['tipo'] == "excluir");
	}
	
	/* Retorna a primeira palavra antes do primeiro espaço
	* */
	public static function getPrimeiroNome($str){
		$str = explode(" ", $str);
		return trim($str[0]);
	}
	/** Esta funcionalidade gera uma tabela e substitui um valor pelo outro, baseando no array de dados fornecido e no template
	* @param: (string)          --> Template a ser populado
	* @param: (array(array[2])) --> Array de dados. Cada posição do array deve conter um array de dois valores,
	*                               sendo o primeiro para ser o que será substituído e o segundo é o valor pelo
	*								qual o primeiro será substituído
	* */
	public function replaceVals($tmpl, $arr){
		
		if(gettype($arr) == 'array'){
			
			for($i_ = 0; $i_ < count($arr); $i_++){
				$curr_ = $arr[$i_];
				if(count($curr_) >= 2){
				
					$tmpl = str_replace($curr_[0], $curr_[1], $tmpl);
					
				}
			}
			
		}else{
			return $tmpl;
		}
		
		return $tmpl;
		
	}
	
	/* VERFICA SE A STRING É UTF8 OU NÃO
	* TRUE = É UTF8
	* FALSE = NÃO É UTF8
	* */
	public function check_utf8($str) {
		$len = strlen($str);
		for($i = 0; $i < $len; $i++){
			$c = ord($str[$i]);
			if ($c > 128) {
				if (($c > 247)) return false;
				elseif ($c > 239) $bytes = 4;
				elseif ($c > 223) $bytes = 3;
				elseif ($c > 191) $bytes = 2;
				else return false;
				if (($i + $bytes) > $len) return false;
				while ($bytes > 1) {
					$i++;
					$b = ord($str[$i]);
					if ($b < 128 || $b > 191) return false;
					$bytes--;
				}
			}
		}
		return true;
	} // end of check_utf8
	
	public function getArrSemConteudo($arr, $tirar){
		$ret = Util::getArrSemRegistrosGeneric($arr, $tirar);
		return $ret['sem'];
	}
	
	public function getArrComConteudo($arr, $tirar){
		$ret = Util::getArrSemRegistrosGeneric($arr, $tirar);
		return $ret['com'];
	}
	
	/** Retorna o valor da página máximo se o valor seleiconado de página for maior que o número total de págimas
	* @param: int --> número da págima selecionada
	* @param: int --> número de páginas máximas
	* return: o menor valor, ou o o número máximo de páginas se o param1 for maior que o número máximo de páginas
	* */
	public function getMaxPage($pag, $max){
		
		if((int)$pag > (int)$max){
			return (int)$max;
		}
		return (int)$pag;
		
	}
	/** Retorna o número 1 se o valor passado for menor ou igual a zero
	* @param: int --> número a ser fornecido
	* */
	public function getPage($cod = null){
		return Util::getNumberOneIfZero($cod);
	}
	
	/** Retorna o número 1 se o valor fornecido for menor ou igual a zero
	* @param: int --> número fornecido pelo usuário
	* return: int (número positivo maior que 0)
	* */
	public function getNumberOneIfZero($cod){
		
		if((int)$cod <= 0){
			return 1;
		}
		return $cod;
		
	}
	public function getArrSemRegistrosGeneric($arr, $tirar){
		
		$tmp_arr = array();
		$tmp_arr['com'] = array();
		$tmp_arr['sem'] = array();
		
		for($i = 0; $i < count($arr); $i++){
			
			if(strlen($arr[$i]) >= strlen($tirar)){
				
				if(Util::direita($arr[$i], strlen($tirar)) == $tirar){
					$tmp_arr['com'][] = $arr[$i];
				}else{
					$tmp_arr['sem'][] = $arr[$i];
				}
				
			}else{
				$tmp_arr['sem'][] = $arr[$i];
			}
			
		}
		
		return $tmp_arr;
		
	}
	
	/** Retorna o valor de zero registros em texto, se a string de entrada estiver vazia
	* @param: string --> texto de tabela a ser exibido para o usuário (pode ser vazio)
	* @param: int    --> número de colunas que a linha a ser gerada deverá tomar
	* @param: string --> Mensagem a ser exibida se o valor do @param1 estiver vazio
	* @param: string --> classe a ser usada, se o valor do @param1 estiver vazio
	* return (valor do @param1 se não estiver vazio OU da string de linha vazia)
	* */
	public function zeroLinhas($string, $num_col = null, $msg_zero = null, $class_registro = null){
		if($string == ""){
			return $string = "<tr><td class='". $class_registro ."' colspan='". ((int)$num_col) ."'>". $msg_zero ."</td></tr>";
		}else{
			return $string;
		}
	}
	
	public function ativar($tabela, $campo, $campo_cond, $id){
		Util::ativar_desativar($tabela, $campo, $campo_cond, $id, "S");
	}
	
	public function desativar($tabela, $campo, $campo_cond, $id){
		Util::ativar_desativar($tabela, $campo, $campo_cond, $id, "N");
	}
	
	public function check($val){
		
		if($val == "S" || $val == "s" || $val === true){
			return "checked";
		}
		return "";
		
	}
	
	public function ativar_desativar($tabela, $campo, $campo_cond, $id, $tipo = null){
		
		if($tipo == null) return false;
		$ban = new Banco();
		$ban->setTabela($tabela);
		$ban->setCampo($campo = "'". $tipo."'");
		$ban->setCondicao($campo_cond ." = ". ((int)$id) );
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		
		return $ret;
		
	}
	
	/** Padrão de página de processamento dos dados do formulário
	* @param: string --> Página que irá processar os dados do formulário.
	* @param: string (opcional) --> valores GET a seren enviados
	* return (string) [processar.php?u=<variável u>]
	* */
	public function form($processamento, $get_ = null){
	
		if($get_ == null){
			$get_ = $_GET['u'];
		}else{
			$get_ = $get_;
		}
		return "processar.php?u=". Code::juntarVar("inc", $processamento, $get_);
		
	}
	
	/* MANIPULAÇAO DE DIAS */
	public function tituloPagina($titulo, $EMPRESA_TITULO){
		return sprintf($EMPRESA_TITULO, $titulo);
	}
	
	public function blocoOpcoes($titulo){
		return sprintf("Op&ccedil;&otilde;es para %s", $titulo);
	}
	
	public function getDiasMenos($dias = null){
		return Util::getDiasMaisMenosGenerico($dias, "-");
	}
	
	public function getDiasMais($dias = null){
		return Util::getDiasMaisMenosGenerico($dias);
	}
	
	public function getDiasMaisMenosGenerico($dias, $tipo = null){
		
		//echo($dias."<br>". Util::getDia() ."<br>");
		
		$dias = (int)$dias * Util::getDia();
		if($tipo == "-"){
			$atual = mktime() - $dias;
		}else{
			$atual = mktime() + $dias;
		}
		return $atual;
	}
	
	public function getMktimeAnterior($dias = null, $tipo = null){
		
		//$dias = (int)$dias * Util::getDia();
		//$atual = mktime() - $dias;
		//return $atual;
		if($dias == null) $dias = 1;
		if($tipo == null) $tipo = "-";
		return Util::getDiasMaisMenosGenerico((int)$dias, $tipo);
	}
	
	/* RETORNA A DATA ANTERIOR A 'X' DIAS
	* */
	public function getDataSQLAnterior($dia = null){
		return date("Y-m-d H:i:s", Util::getMktimeAnterior($dia, "-"));
	}
	
	function convertRealFloat($valor){
		
		$valor = str_replace("R$ ", "", $valor);
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", ".", $valor);
		
		return (float)$valor;
		
	}
	/** 21.07.2014  - Adicionado */
	public function getDataSQLPosterior($dia = null){
		return date("Y-m-d H:i:s", Util::getMktimeAnterior($dia, "+"));
	}
	
	public function getDia(){
		return Util::getMinuto() * 24;
	}
	
	public function getMinuto(){
		return 60 * 60;
	}
	
	/* RETORNA O NÚMERO DE DIAS ENTRE A DATA ANTERIOR E A ATUAL
	* */
	public function getDiasPassados($sql_data){
		
		$sql_data = explode(" ", $sql_data);
		$data = explode("-", $sql_data[0]);
		$hora = explode(":", $sql_data[1]);
		
		$mk_ant = mktime((int)$hora[0], (int)$hora[1], (int)$hora[2], (int)$data[1], (int)$data[2], (int)$data[0]);
		$mk_atu = mktime();
		$mk_res = $mk_atu - $mk_ant;
		
		return (int) ($mk_res / Util::getDia());
		
	}
	
	function genFromDateToSQLWhole($dataHora){
		
		
		
		$dh = explode(" ", $dataHora);
		
		if(strlen($dh[1]) < 5){
			$dh[1] = "00:00:00";
		}
		
		if(strlen($dataHora) == 0){
			
			return null;
			
		}else if($dataHora == "dd/mm/YYYY HH:mm"){
			
			return null;
			
		}else{
			
			return Util::gentFromDateToSQL($dh[0], $dh[1]);
		
		}
		
		
	}
	
	function gentFromDateToSQL($data, $hora){
		
		
		if(sizeof($hora) < 8){
			$hora = $hora . ":00";
		}
		
		$d = explode("/", $data);
		$h = explode(":", $hora);
		
		if((sizeof($d) < 3 || sizeof($d) > 3) && (sizeof($h) > 3|| sizeof($h) < 2)){
			return null;
		}else{
			
			/* VERIFICA SE O USUÁRIO FORNECEU SEGUNDOS */
			if( (int)$h[2] > 0 && (int)$h[2] < 60 ){
				return Util::genDateTimeToSQL($d[2], $d[1], $d[0], $h[0], $h[1], $h[2]);
			}else{
				return Util::genDateTimeToSQL($d[2], $d[1], $d[0], $h[0], $h[1]);
			}
			
		}
		
	}


	public function direita($str, $len){
		return Util::right($str, $len);
	}
	
	public function esquerda($str, $len){
		return Util::left($str, $len);
	}
	
	public function genDataParaSQL($data){
		return Util::genFromDateOnlyToSQL($data);
	}
	
	public function genDataHoraParaSQL($data, $hora){
		return Util::genFromDateToSQL($data, $hora);
	}
	
	/*FUNCAO RIGHT*/
	public function right($str, $len){
		return substr($str, 0, $len);
	}

	/*FUNCAO LEFT*/
	public function left($str, $len){
		return substr($str, strlen($str) - $len, strlen($str));
	}
	
	public function genFromDateOnlyToSQL($data){
		return Util::genFromDateToSQL($data, "00:00:00");
	}
	
	/* FUN?O PARA CONVERTER "dd/mm/AAAA HH:mm[:ss]" em AAAA-mm-dd HH:mm:ss
	* */
	public function genFromDateToSQL($data, $hora = null){
		
		if(strlen($hora) < 5)  return null;
		if(strlen($data) < 10) return null;
		
		if($hora = null){
			$hora = "00:00:00";
		}else if(sizeof($hora) <= 0){
			$hora = "00:00:00";
		}else if(sizeof($hora) < 8 && sizeof($hora) > 0){
			$hora = $hora . ":00";
		}
		
		$d = explode("/", $data);
		$h = explode(":", $hora);
		
		if((sizeof($d) < 3 || sizeof($d) > 3) && (sizeof($h) > 3|| sizeof($h) < 2)){
			return null;
		}else{
			
			/* VERIFICA SE O USU?IO FORNECEU SEGUNDOS */
			if( (int)$h[2] > 0 && (int)$h[2] < 60 ){
				return Util::genDateTimeToSQL($d[2], $d[1], $d[0], $h[0], $h[1], $h[2]);
			}else{
				return Util::genDateTimeToSQL($d[2], $d[1], $d[0], $h[0], $h[1]);
			}
			
		}
		
	}
	
	
	public function genDateTimeToSQL($ano, $mes, $dia, $hora, $minuto, $segundo = null){
	
		$Y = $ano;
		$m = Util::twoFields($mes);
		$d = Util::twoFields($dia);
		$H = Util::twoFields($hora);
		$i = Util::twoFields($minuto);
		
		if($segundo != null){
			$s = Util::twoFields($segundo);
		}else{
			$s = "00";
		}
		
		if($ano == 0 || $mes == 0 || $dia == 0){
			return null;
		}else if($ano < 0 || $mes < 0 || $dia < 0 || $hora < 0 || $minuto < 0){
			return null;
		}else if($hora > 24 || $minuto > 59){
			return null;
		}else if($mes > 12){
			return null;
		}else if($mes == 2 && $dia > 29){
			return null;
		}else if($ano == "" || $mes == "" || $dia == ""){
			return null;
		}else{
			return $Y ."-". $m ."-". $d ." ". $H .":". $i .":". $s;
		}
	
	
	}
	
	public function twoFields($h){
	
		if((int)$h < 10){
			$h = "0". (int)$h;
		}
		return $h;
	}
	
	
	public function isDatePast($dateSQL){
		
		$dateSQL = (strlen($dateSQL) < 11) ? $dateSQL . " 00:00:00": $dateSQL;
		$mk_date = Util::mkTimeFromDateSQL($dateSQL);
		return $mk_date < mktime();
		
	}
	
	public function isPastTF($dateSQL){
		return Util::getTFs(Util::isDatePast($dateSQL));
	}
	
	
	
	public function mkTimeFromDateSQL($date){
		
		list($date, $time) = explode(" ", $date);
		list($yy, $mm, $dd) = explode("-", $date);
		list($hh, $ii, $ss) = explode(":", $time);
		return mktime((int)$hh, (int)$ii, (int)$ss, $mm, $dd, $yy);
		
	}
	
	//int mktime ([ int $hora [, int $minuto [, int $second [, int $mes [, int $dia [, int $ano [, int $is_dst ]]]]]]] )
	
	public function greaterDate($dateTimeA, $dateTimeB, $addSegundos = 0){
	
		//int mktime ([ int $hora [, int $minuto [, int $second [, int $mes [, int $dia [, int $ano [, int $is_dst ]]]]]]] )
		
		Log::info("Util::greaterDate('". $dateTimeA ."', '". $dateTimeB ."' + ".$addSegundos .")");
		$ret = Util::mkTimeFromDateSQL($dateTimeA) > ((Util::mkTimeFromDateSQL($dateTimeB) + $addSegundos));
		if($ret){
			return true;
		}else{
			return false;
		}
	
	}
	
	public function dateGreaterNow($dateTimeA){
		return Util::greaterDate($dateTimeA, Banco::getDateTimeDB());
	}
	
	public function retVal($arr, $pos, $val){
		
		if(gettype($arr) == "array"){
			if($pos >= sizeof($arr) - 1){
				return "";
			}else{
				return $val;
			}
		}else{
			return "";
		}
		
	}
	
	/* RETORNA: dd/mm/aaaa HH:mm:ss
	* */
	function getDateTimeFromSQL($data){
	
		$d = explode(" ", $data);
		
		$data = Util::getDateFromSQL($d[0]);
		return trim($data ." ". $d[1]);
		
	}
	
	/* RETORNA: dd/mm/aa HH:ii:ss
	* @param: string --> Data de entrada no formado MySQL "YYYY-mm-dd HH:ii:ss"
	* @param: [boolean] --> Se a data será curta ou não, somente retorna valor curto se o boolean 'true' for especificado
	* */
	function getShortDateTimeFromSQL($data, $short = true){
	
		$d = explode(" ", $data);
		
		$data = Util::getDateFromSQL($d[0], $short);
		
		if($short){
			$d[1] = Util::direita($d[1], 5);
		}
		
		return trim($data ." ". $d[1]);
		
	}
	
	public function getTracoSeNulo($valor){
		if($valor == null) return "-";
		return $valor;
	}
	
	/* BRASIL - Formato de Data em Português-BR */
	function getDateFromSQL($data, $short = null, $delimiter = null){
		
		$d = explode(" ", $data);
		$data_format = explode("-", $d[0]);
		
		if($data == null){
			if($delimiter != null){
				return $delimiter;
			}else{
				return null;
			}
		}else if(sizeof($data_format) < 3 || sizeof($data_format) > 3){
			return "Invalid date provided: input format expected /YYYY-mm-dd HH:mm:ss/";
		}
		
		if($short){
			$data_format[0] = Util::esquerda($data_format[0], 2);
		}
		return $data_format[2] ."/". $data_format[1] ."/". $data_format[0];
		
	}
	
	public function getPaginaAtual(){
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	public function msgShort($msg, $max = null){
		if($max == null) $max = 20;
		
		if(strlen($msg) > $max){
			return Util::right($msg, $max) ."...";
		}else{
			return $msg;
		}
	}
	
	/* Retorna o valor da strin1 um se verdadeiro, senão retorna a seguna
	* @param: boolean --> condição a ser testada
	* @param: string --> contição retornada se param1 for 'true'
	* @param: string --> contição retornada se param1 for 'false'
	* return String
	* */
	public function getBoolean($cond, $ret1, $ret2 = ""){
		if($cond){
			return $ret1;
		}
		return $ret2;
	}

	public function getTFs($bool){
		return Util::getBoolean($bool, "TRUE", "FALSE");
	}
	
	/* Verifica se o tamanho da string1 é maior que zero, se não for então retorna a string2
	* @param1: string
	* @param2: (se nulo default '-')
	* return string;
	* */
	public function getSeMaiorQueZero($str1, $str2 = null){
		if($str2 == null) $str2 = "-";
		return Util::getBoolean(strlen($str1) > 0, $str1, $str2);
	}
	
	public function removeAcentos($msg){
	
		$a = array(
        '/[ÂÀÁÄÃ]/u'=>'A',
        '/[âãàáäª]/u'=>'a',
        '/[ÊÈÉË]/u'=>'E',
        '/[êèéë]/u'=>'e',
        '/[ÎÍÌÏ]/u'=>'I',
        '/[îíìï]/u'=>'i',
        '/[ÔÕÒÓÖ]/u'=>'O',
        '/[ôõòóöº]/u'=>'o',
        '/[ÛÙÚÜ]/u'=>'U',
        '/[ûúùü]/u'=>'u',
        '/ç/u'=>'c',
        '/Ç/u'=>'C',
        '/\s/'=>'_'
		);
			
		// Tira o acento pela chave do array                        
		return preg_replace(array_keys($a), array_values($a), $msg);
	}	
	
	/** Exibe o valor de bytes em bytes, KB ou MB
	* @param: int --> valor a ser convertido
	* @return (string) --> valor convertido de bytes para string de bytes, KB e MB
	* */
	public function tamanhoArq($arquivo){
		$tamanho = $arquivo;
		
		if($tamanho < 1024){
			return $tamanho ." bytes";
		}else if($tamanho >= 1024){
			
			if($tamanho / 1024 < 1024){
				return round($tamanho / 1024, 1) ." KB";
			}else{
				return round($tamanho / 1024 / 1024, 1) ." MB";
			}
			
		}
	}
	
	public function replaceAll($tmpl, $arr){
	
		for($j = 0; $j < count($arr); $j++){
			$arr_curr = $tmpl[$i];
			$tmpl = str_replace($arr_curr[0], $arr_curr[1], $tmpl);
		}
		
		return $tmpl;
		
	}
	
	/** Retorna o valor, se a primeira for true, senão retorna vazio
	* @param: boolean --> true | false
	* @param: string --> retorna este valor se o parâmetro 1 for true, senão retorna vazio
	* */
	public function returnValue($bool, $string){
		if($bool) return $string;
		return "";
	}
	
	
	/* RETORNA: DD/MM/AAAA HH:MM:SS
	*
	* Independente se a entrada for da função time() ou de uma string SQL : "yyyy-mm-dd hh:mm:ss"
	* */
	public function getDateTimeAny($date){
		
		if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date)){
			
			return Util::getDateTimeFromSQL($date);
			
		}else{
		
			if((int)$date > 0){
				return date("d/m/Y H:i:s", $date);
			}else{
				return "-";
			}
			
		}
		
	}
	
	
	public function saveEmail($para, $titulo, $texto, $int = ""){

		$ban = new Banco();
		
		$campo = "";
		$valor = "";
		
		if($int == "SEND_LATER"){
			$campo = ", `atni_p030t_tentativa_counter`";
			$valor = ", 0";
		}
		//$campo = ", `atni_p030t_tentativa_counter`";

		
		$sql = "INSERT INTO `". $ban->getDB() ."`.`ged_030t_email_template`
		(`atsv_p030t_para`,
		`atsv_p030t_titulo`,
		`atsv_p030t_email`,
		`atdt_p030t_criado`,
		`atdt_p030t_atualizacao` ". $campo .")
		VALUES
		(
		'". addslashes($para) ."',
		'". addslashes($titulo) ."',
		'". addslashes($texto) ."',
		NOW(),
		NULL
		". $valor ."
		)";
		
		$ban = new Banco();
		$ban->setSql($sql);
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		
		return $ret;
		
	}
	public function enc($str){
		
		/* CODIFICAÇÃO: utf8 = true | false
		* */
		if(UTF8){
			if(!Util::check_utf8($str)){
				$str = utf8_encode($str);
			}
		}else{
			if(Util::check_utf8($str)){
				$str = utf8_decode($str);
			}
		}
		
		return $str;		
		
	}
	
	
	// 23.07.2014 - Verificação de senha
	// http://stackoverflow.com/questions/10752862/password-strength-check-in-php
	public function checkPassword($pwd) {
		
		$errors = "";

		if (strlen($pwd) < 6) {
			$errors = "A senha deve conter no m&iacute;nimo 6 caracteres!";
		}else if (!preg_match("#[0-9]+#", $pwd)) {
			$errors = "A senha deve conter no m&iacute;nimo um n&uacute;mero!";
		}else if (!preg_match("#[a-zA-Z]+#", $pwd)) {
			$errors = "A senha deve conter no m&iacute;nimo uma letra!";
		}          

		//if(strlen($errors) > 0) Log::log2fileWARNING("Erro na autenticação do usuário [usuario = '". $_SESSION['usuario'] ."', cod_usuario = '". $_SESSION['cod_usuario'] ."']");

		return $errors;
	}
	
	
	/* CONVERTENDO TODOS OS OBJETOS PARA 'ISO-8859-1' DE 'UTF8'*/
	public function parseAllFromUTF8($obj){
		
		$all_to_utf8 = $obj->getAll();
		
		Log::log2fileINFO("START [". get_class($obj) ."]: parsingAllFromUTF8::");
		Log::log2fileINFO("Total de registros para realizar parser: ". count($all_to_utf8) );
		
		for($o = 0; $o < count($all_to_utf8); $o++){
			Log::log2fileINFO("  >> LENDO Objeto [". get_class($obj) ."] na posição [". ($o + 1) ."]");
			Util::utf8_from_to($all_to_utf8[$o]);
			Log::log2fileINFO("  >> LEU Objeto [". get_class($obj) ."] na posição [". ($o + 1) ."]");
		}
		
		Log::log2fileINFO("END [". get_class($obj) ."]: parsingAllFromUTF8::");
		
	}
	
	/* CONVERTENDO TODOS OS OBJETOS PARA 'UTF8' DE 'ISO-8859-1' */
	public function parseAllToUTF8($obj){
		
		$all_to_utf8 = $obj->getAll();
		
		Log::log2fileINFO("START [". get_class($obj) ."]: parseAllToUTF8::");
		Log::log2fileINFO("Total de registros para realizar parser: ". count($all_to_utf8) );
		
		for($o = 0; $o < count($all_to_utf8); $o++){
			Log::log2fileINFO("  >> LENDO Objeto [". get_class($obj) ."] na posição [". ($o + 1) ."]");
			Util::utf8_from_to($all_to_utf8[$o], false);
			Log::log2fileINFO("  >> LEU Objeto [". get_class($obj) ."] na posição [". ($o + 1) ."]");
		}
		Log::log2fileINFO("END [". get_class($obj) ."]: parseAllToUTF8::<br>");
		
	}
	
	/* CONVERSÃO DE 'ISO' PARA 'UTF8' E VICE VERSA */
	public function utf8_from_to($tmp_obj, $from_utf8 = true){
		
		//log2file($msg, $log_name = null)
		/* CAPTURA TODOS OS MÉTODOS DE CLASSES */
		$arr_all = get_class_methods($tmp_obj);
		$update = false;
		
		/* LOG */
		//Log::log2fileINFO("  [A-> B] Convertendo objeto [". get_class($tmp_obj) ."] para [". (!$from_utf8 ? "UTF8" : "ISO-8859-1") ."]");
		
		
		/** PERCORRENDO POR TODOS OS REGISTROS */
		for($iUTF8 = 0; $iUTF8 < count($arr_all); $iUTF8++){
			
			
			/** CAPTURANDO SOMENTE OS MÉTODOS COM 'SET' */
			if(strpos($arr_all[$iUTF8], "set") > -1 && $arr_all[$iUTF8] != "setAll"){
				
				/** CAPTURANDO O VALOR DO GET */
				$valor_get = Util::getGet($tmp_obj, $arr_all, $arr_all[$iUTF8]);
				
				/** VERIFICANDO SE A STRING É UTF8 */
				$is_utf8 = Util::check_utf8($valor_get);
				
				/* VERIFICANDO PARA QUAL TIPO DE CONVERSÃO O OBJETO SERÁ FEITA - DE OU PARA UTF8 */
				if($from_utf8){
					
					if($is_utf8 && gettype($valor_get) == 'string'){
						$valor_get = utf8_decode($valor_get);
						$update = true;
					}
				}else{
					
					if(!$is_utf8 && gettype($valor_get) == 'string'){
						$valor_get = utf8_encode($valor_get);
						$update = true;
					}
				}
				
				
				/** SE TIVER VALOR, ENTÃO INICIALIZA O VALOR CONVERTIDO */
				if(((int)$valor_get != "") == 1){
					
					
					// old php --> $tmp_obj->$arr_all[$iUTF8]($valor_get);
					echo var_dump($tmp_obj) ."<br>";
					
					$tmp_obj->{$arr_all[$iUTF8]}($valor_get);
					
					/* LOG */
					////Log::log2fileTRACE("    ". get_class($tmp_obj) ."->". $arr_all[$iUTF8] ." = ". $valor_get ."[isUTF8=". ((int)$is_utf8) . "]");
				}
				
			}
		
			
		}
		
		/** ATUALIZAÇÃO DO OBJETO */
		if($update){
			$ret = $tmp_obj->atualizar();
			//Log::log2fileINFO("  Atualizando o objeto [". get_class($tmp_obj) ."] [RESULTADO  = ". ($ret ? "OK" : "NOK") ."] ");
			
		}else{

			//Log::log2fileINFO("  Sem necessidade de converter o objeto [". get_class($tmp_obj) ." --> [Convertendo DE UTF8=". ($from_utf8 ? "TRUE" : "FALSE") ."]");
			
		}
		
		
	}
	public function getGet($obj_real, $obj_all, $curr){
		
		
		for($j = 0; $j < count($obj_all); $j++){
			
			//echo((int) ((string)$obj_all[$j] == str_replace("s", "g", $curr)) ." - É?<br>");
			$curr_method = $obj_all[$j];
			
			
			$not_standar_method = ($curr_method != "getTodosRegistros");
			$not_standar_method &= ($curr_method != "getOne");
			$not_standar_method &= ($curr_method != "getAll");
			$not_standar_method &= ($curr_method != "getUmRegistroChave");
			$not_standar_method &= ($curr_method != "getFluxo");
			$not_standar_method &= ($curr_method != "getStatusPorID");
			$not_standar_method &= ($curr_method != "getGenericSelect");
			$not_standar_method &= ($curr_method != "getGet");
			$not_standar_method &= ($curr_method != "getCampo");
			
			if(strpos($curr_method, "get") > -1 && $not_standar_method){
			
				$to_search = str_replace("set", "get", $curr);
				
				if($curr_method == $to_search){
					return $obj_real->$curr_method();
				}
			
			}
			
		}

		return "";
		
	}
	
	/* Mostra todas as variáveis de configuração do servidor
	* */
	public function getServerConfiguration(){
		
		$arr_keys = array_keys($_SERVER);
		
		foreach($arr_keys as $key){
			echo($key . " = ". $_SERVER[$key] ."\r\n<br>");
		}
		
	}
	
	public function getServerVars(){
		
		$arr_keys = array_keys($_SERVER);
		$arr = array();
		
		foreach($arr_keys as $key){
			$arr[] = $key . " = ". $_SERVER[$key];
		}
		
		return $arr;
		
	}
	
	/* Cria log do evento 'getUmRegistro' de cada classe
	* */
	public function logUmRegistro($arr_ret, $classe, $keys = null){
		
		$valores = array();
		if($keys != null){
			foreach($keys as $key => $value){
				$valores[] =  $key ." --> ". $value;
			}
			
		}
		if(count($valores) > 0){
			$valores = implode(", ", $valores);
			$valores= "[". $valores ."]";
		}else{
			$valores = "";
		}
		
		if(count($arr_ret) > 0){
			return Log::info("[". $classe ."->getUmRegistroChave] Capturando o registro de um ". $classe .".". $valores);
		}else{
			return Log::warning("[". $classe ."->getUmRegistroChave] Não há registros para os parâmetros informados ". $classe .".". $valores);
		}
		
	}
	
	/* Criar a lista de arquivos anexos [ADAPT0002]
	* @param: Lista de objetos de ArquivoAnexo
	* return string: tabela
	* */
	public function criarLinksArquivosAnexos($list_anexos = null){
		
		$output = array();
		
		$ESTA_ABERTO = true;
		
		/** [ADAPT0056] */
		if(Code::getVar("inc") == "atu_sacp.php" || Code::getVar("inc") == "atu_rnc.php"){
			
			if(!class_exists("RNC")) require_once("RNC.php");
			if(!class_exists("SACP")) require_once("SACP.php");
			
			$tmp_obj = Util::getBoolean(Code::getVar("inc") == "atu_rnc.php", new RNC(), new SACP());
			$tmp_obj->setCod(Code::getVar("id_registro"));
			$obj = $tmp_obj->getUmRegistroChave();
			if($obj != null){
				$ESTA_ABERTO = !($obj->isFechado());
			}
		}
		
		for($i = 0; $i < count($list_anexos); $i++){
		
			$obj = $list_anexos[$i];
			$url = Code::codificar("file=". $obj->getNomearquivofisico()  ."&file_name=". $obj->getNomearquivo() ."&tipo=". $obj->getTipoarquivo());
			
			/** [ADAPT0056] - PAGINA PARA EXCLUSAO DO ARQUIVO */
			$pagina_del = Code::getVar("inc");
			$pagina_del = $pagina_del ."?u=". Code::codificar("tipo=excluir_anexo&id_registro=". $obj->getCod());
			
			if($ESTA_ABERTO){
				$pagina_del = "<a href='". $pagina_del ."' nome_arquivo=\"". $obj->getNomearquivo() ."\" class='excluir_arquivo_ro_bom' lineid='line_". $obj->getCod() ."'>
						<img title='Excluir o arquivo' height=20px src='img/excluir-24x24.png'>
					</a>";
			}else{
				$pagina_del = "";
			}
			
			
			
			if(Util::check_utf8($obj->getNomearquivo())){
				$obj->setNomearquivo(utf8_decode($obj->getNomearquivo()));
			}
			
			$tam_coluna = Util::getBoolean($ESTA_ABERTO, "80", "40");
			$output[] = "<tr id='line_". $obj->getCod() ."'>
							<td class='centro padding3' width='150px'>". Util::getDateTimeFromSQL($obj->getCriado()) ."</td>
							<td class='centro padding3' width=". $tam_coluna ."px>
								<a href='baixar_anexo.php?u=". $url ."'>
									<img title='Baixar o arquivo' height=20px src='img/baixar-arquivo-24x24.png'>
								</a>
								". $pagina_del ."
							</td>
							<td class='padding3'>
								<a title='Baixar o arquivo' href='baixar_anexo.php?u=". $url ."'>
								". $obj->getNomearquivo() ."
								</a>
							</td>
						</tr>";
		}
		
		if(count($output) > 0){
			$output = implode("\n", $output);
			$output = "<table width='100%' border='0'><tr><td>Criado</td><td colspan='2'>Arquivo</td></tr>". $output ."</table>";
		}else{
			$output = "<div style='background-color: #F1F1F1; padding: 10px; border-radius: 10px'>Nenhum arquivo anexado...</div>";
		}
		
		return $output;
		
	}
	
	/** Substitui as barras por valores de HTML
	* @param: string --> Entrada de valores com até '\' barras e substitui por '&#92;'
	* return (string) - com valores '\' substituído por '&#92;'
	* */
	public function replaceChars($isso){
		
		$isso = str_replace("\\", "&#92;", $isso);
		$isso = str_replace("'", "&#39;", $isso);
		if(strlen($isso) > 0) Log::trace("[Util::replaceChars] Convertido: ". $isso);
		
		return $isso;
		
	}
	
	public function spe($isso){
		return Util::replaceChars($isso);
	}
	
	/** Substitui os valores de '\n' por quebra de linha HTML '<br>'
	* @param: string --> valor de entrada
	* return (string) --> valor do @param com os valores de 'enter' substituído por 'enter HTML'
	* */
	public function printEnter($value){
		return str_replace("\n", "<br>", $value);
	}
	
	public function getCaminhoAtualLog(){
		//echo($_SERVER['SCRIPT_FILENAME']);
		$caminho_atual = substr($_SERVER["SCRIPT_FILENAME"], 0, strrpos($_SERVER["SCRIPT_FILENAME"],"/")+1);
		
		$dir = "arquivos_fisicos,class,config,css,db_back,dir_suporte,editor,form,html,img,inc,inf_o,js,log,resources";
		$dir = explode(",", $dir);
		
		for($i = 0; $i < count($dir); $i++){
			$caminho_atual = str_replace($dir[$i], "", $caminho_atual);
		}
		$caminho_atual = str_replace("//", "/", $caminho_atual);
		
		return $caminho_atual;
	}
	
	/* REMOVER COLABORADORES DA LS DA LISTA DE COLABORADORES
	* @param: string --> nome do campo da tabela que deverá ser considerada na condição
	* return @param + " IN("+ emails definidos no parâmetro CONFIG_LS_USERS +") "
	* */
	public function excluiRegistros($campo){
		
		if(CONFIG_NAO_EXIBIR_LS_USER){
			$arr = explode(",", CONFIG_LS_USERS);
			
			if(strlen($arr[0]) > 0){
			
				for($j = 0; $j < count($arr); $j++){
					$arr[$j] = "'". $arr[$j] ."'";
				}
				
				return $campo ." NOT IN (". implode(", ", $arr) .")";
				
			}
			
			return "";
		}
		
		return "";
		
	}
	
	/** [ADAPT0031] */
	public function replaceCamposEmail($enviar_email, $params){
		
		/* PARAMETROS */
		if(gettype($params) == "array"){
			
			if($params['nome']             != null) $enviar_email = str_replace("#nome#", $params['nome'], $enviar_email);
			
			if($params['usuario_sugestor'] != null) $enviar_email = str_replace("#usuario_sugestor", $params['usuario_sugestor'], $enviar_email);
			if($params['nome']             != null) $enviar_email = str_replace("#nome", $params['nome'], $enviar_email);
			if($params['documento']        != null) $enviar_email = str_replace("#documento", $params['documento'], $enviar_email);
			if($params['versao']           != null) $enviar_email = str_replace("#versao", $params['versao'], $enviar_email);
			if($params['link']             != null) $enviar_email = str_replace("#link", $params['link'], $enviar_email);
			if($params['caminhodoc']       != null) $enviar_email = str_replace("#caminhodoc", $params['caminhodoc'], $enviar_email);
			if($params['documento']        != null) $enviar_email = str_replace("#documento", $params['documento'], $enviar_email);
			if($params['usuarioaprovador'] != null) $enviar_email = str_replace("#usuarioaprovador", $params['usuarioaprovador'], $enviar_email);
			if($params['usuarioconsenso']  != null) $enviar_email = str_replace("#usuarioconsenso", $params['usuarioconsenso'], $enviar_email);
			if($params['usuarioreprovou']  != null) $enviar_email = str_replace("#usuarioreprovou", $params['usuarioreprovou'], $enviar_email);
			if($params['obs']              != null) $enviar_email = str_replace("#obs", $params['obs'], $enviar_email);
			if($params['componentes_consenso_li'] != null) $enviar_email = str_replace("#componentes_consenso_li", $params['componentes_consenso_li'], $enviar_email);
			if($params['link_doc']        != null) $enviar_email = str_replace("#link_doc", $params['link_doc'], $enviar_email);
			if($params['nome_lido']       != null) $enviar_email = str_replace("#nome_lido", $params['nome_lido'], $enviar_email);
			if($params['gr_link']         != null) $enviar_email = str_replace("#gr_link", $params['gr_link'], $enviar_email);
			if($params['gr_cod']          != null) $enviar_email = str_replace("#gr_cod", $params['gr_cod'], $enviar_email);
			if($params['gr_descricao']    != null) $enviar_email = str_replace("#gr_descricao", $params['gr_descricao'], $enviar_email);
			if($params['meta']            != null) $enviar_email = str_replace("#meta", $params['meta'], $enviar_email);
			if($params['prazo']           != null) $enviar_email = str_replace("#prazo", $params['prazo'], $enviar_email);
			if($params['recurso']         != null) $enviar_email = str_replace("#recurso", $params['recurso'], $enviar_email);
			if($params['verificado']      != null) $enviar_email = str_replace("#verificado", $params['verificado'], $enviar_email);
			
			//if($params['nome'] != null)          $enviar_email = str_replace("#nome", $params['nome'], $enviar_email);
			if($params['link_ro'] != null)       $enviar_email = str_replace("#link_ro", $params['link_ro'], $enviar_email);
			if($params['ro_codigo'] != null)     $enviar_email = str_replace("#ro_codigo", $params['ro_codigo'], $enviar_email);
			if($params['ro_descricao'] != null)  $enviar_email = str_replace("#ro_descricao", $params['ro_descricao'], $enviar_email);
			if($params['bom_codigo'] != null)    $enviar_email = str_replace("#bom_codigo", $params['bom_codigo'], $enviar_email);
			if($params['bom_descricao'] != null) $enviar_email = str_replace("#bom_descricao", $params['bom_descricao'], $enviar_email);
			if($params['sacp_descricao'] != null) $enviar_email = str_replace("#sacp_descricao", $params['sacp_descricao'], $enviar_email);
			if($params['previsao_encerramento'] != null)  $enviar_email = str_replace("#previsao_encerramento", $params['previsao_encerramento'], $enviar_email);
			if($params['emissao'] != null)       $enviar_email = str_replace("#emissao", $params['emissao'], $enviar_email);
			if($params['ro_acao_imediata'] != null)       $enviar_email = str_replace("#ro_acao_imediata", $params['ro_acao_imediata'], $enviar_email);
			
			if($params['pa_cod_bom'] != null)    $enviar_email = str_replace("#pa_cod_bom", $params['pa_cod_bom'], $enviar_email);
			if($params['pa_cod_item'] != null)   $enviar_email = str_replace("#pa_cod_item", $params['pa_cod_item'], $enviar_email);
			if($params['pa_atividade'] != null)  $enviar_email = str_replace("#pa_atividade", $params['pa_atividade'], $enviar_email);
			if($params['pa_como'] != null)       $enviar_email = str_replace("#pa_como", $params['pa_como'], $enviar_email);
			if($params['pa_quando'] != null)     $enviar_email = str_replace("#pa_quando", $params['pa_quando'], $enviar_email);
			if($params['pa_verificado'] != null) $enviar_email = str_replace("#pa_verificado", $params['pa_verificado'], $enviar_email);
			
		}
		
		return $enviar_email;
		
	}
	
	/* CAPTURA O NÚMERO TOTAL DE REGISTROS DE UMA TABELA
	* @param: string --> nome da tabela
	* @param: Banco --> Objeto de banco a ser fornecido, ele será usado para realizar a consulta no banco de dados. Se for necessário fornecer condições adicionais na query, sugere-se que as mesmas sejam passadas através do objeto 'Banco'.
	* return int: total de registros da tabela;
	* */
	public function getTotalRecords($tabela, $ban = null){
		
		if($ban == null) $ban = new Banco();
		$ban->setTabela($tabela);
		$ban->setCampo("count(*)");
		$ban->setResult($ban->getSelect());
		$ret = $ban->getResult();
		$valor = 0;
		if($linha = mysqli_fetch_array($ret)){
			$valor = $linha[0];
		}
		$ban->fecharConexao();
		return $valor;
		
	}
	
	
	public function getDBUpdate($sql, $ban = null){
		if($ban == null) $ban = new Banco();
		if(strlen($sql) > 0) $ban->setSql($sql);
		$bool = $ban->setResult($ban->getSelect(), true);
		$ret = $ban->getResult();
		return $ret;
	}
	
	/** CAPTURA COMO ARRAY OS DADOS DE UMA QUERY DO BANCO DE DADOS
	* @param: string --> SQL de select a ser executado no banco de dados
	* @param: Banco --> Objeto de banco a ser fornecido, ele será usado para realizar a consulta no banco de dados.
	*                   Se não for fornecido, um objeto é criado em tempo de execução
	* @param: boolean --> default 'false', se for fornecido como true então o array é retornado com as chaves das tabelas.
	* return mixed: arrray, boolean;
	* */
	public function getDBRecords($sql, $ban = null, $with_cols = null){
		
		
		$ret_arr = array();
		
		if($with_cols == null){
			$with_cols =  false;
		}else{
			if(!$with_cols) $with_cols = true;
		}
		
		if($ban == null) $ban = new Banco();
		if(strlen($sql) > 0) $ban->setSql($sql);
		$bool = $ban->setResult($ban->getSelect(), true);
		$ret = $ban->getResult();
		
		$i = 0;

		//echo(mysqli_num_fields($ret) ."<br>");
		$columns = array();
	
		while($linha = mysqli_fetch_array($ret)){
			/* SE FOR A PRIMEIRA LINHA CAPTURA AS COLUMAS */
			if(count($columns) <= 0) $columns = Util::getTableKeysHeader($linha);
			$ret_arr[] = $linha;
		}
		
		$ban->fecharConexao();
		
		/* EM CASO DE ERRO RETORNA FALSE */
		if(!$bool) return $bool;
		if($with_cols){
			return array("columns" => $columns, "data" => $ret_arr);
		}else{
			return $ret_arr;
		}
		
		
	}
	
	/* RETORNA TODOS O RESULTADO DE UMA CONSULTA SQL COMO TABELA
	* @param: string --> Select a ser realizado no banco de dados para retorno dos registros
	* @param: Banco --> Objeto de banco a ser fornecido, ele será usado para realizar a consulta no banco de dados
	* return void;
	* */
	public function getDBRecordsAsTable($sql, $ban = null, $columns = null){
		$obj = Util::getDBRecords($sql, $ban, true);
		
		if($columns == null){
			$columns = $obj['columns'];
		}
		$data   = $obj['data'];
		
		echo("<table style='border: 1px solid #AAAAAA' cellpadding=0 cellspacing=0>");
		echo("<tr style='background-color: #C0C0C0'>");
		for($i = 0; $i < count($columns); $i++){
			echo("<td style='text-align: center'>". $columns[$i] ."</td>");
		}
		echo("</tr>");
		
				
		for($j = 0; $j < count($data); $j++){
			$line = $data[$j];
			echo("<tr>");
			for($i = 0; $i < count($columns); $i++){
				echo("<td style='padding: 4px; background-color: ". Util::getBoolean($i % 2 == 0, "#F1F1F1", "#FFFFFF") ."; border-bottom: 1px solid #000000;'>". $line[$columns[$i]] ."&nbsp;</td>");
			}
			echo("</tr>");
		}
		echo("</table>");
		
	}
	
	/* RETORNA TODAS AS CHAVES DE UMA LINHA DE RESULTSET DO BANCO DE DADOS
	* @param: array --> linha de um registro de resultset
	* return string;
	* */
	public function getTableKeysHeader($linha){
		
		$linha = array_keys($linha);
		
		$out_header = array();
		for($j = 1; $j < count($linha); $j = $j + 2){
			$out_header[] = $linha[$j];
		}
		return $out_header;
	}
	
	
	/* RETORNA UM ARRAY DE MESES; 1 = JANEIRO, 12 = DEZEMBRO
	* @param: int --> opicional, se for fornecido um valor maior que zero, o nome do mês é retornado no tamanho
	* return array
	* */
	public function getMeses($reduzir = null, $start_in_zero = false){
		
		$start_in = 0;
		if($start_in_zero){
			$meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		}else{
			$meses = array("", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
			$start_in = 1;
		}
		
		if((int)$reduzir > 0){
			for($i = $start_in; $i < count($meses); $i++){
				$meses[$i] = substr($meses[$i], 0, (int)$reduzir);
			}
		}
		return $meses;
	}
	
	public function color($value){
		if($value % 2 == 0) return "#C0C0C0";
		return "";
	}
	
		
	public static function mensagemNova($data){
		$dias = Util::getDiasPassados($data);
		
		if($dias <= 0){
			return "<span class=\"novo\">Novo</span>";
		}
		
		return "";
	}
	
	public function extractSecond($seconds){
		return $seconds % 60;
	}
	
	public function extractMinutes($seconds){
		$sec_mod = Util::extractSecond($seconds);
		$seconds = ($seconds - $sec_mod) / 60;
		return $seconds % 60;
	}
	
	public function extractHours($seconds){
		$sec_mod = Util::extractSecond($seconds);
		$min_mod = Util::extractMinutes($seconds) * 60;
		
		$seconds = ($seconds - $sec_mod - $min_mod) / (60 * 60);
		//return $seconds % 24;
		return $seconds;
	}
	
	
	public function secToHour($seconds){
		
		return Util::twoFields(Util::extractHours($seconds)) .":". Util::twoFields(Util::extractMinutes($seconds)) .":". Util::twoFields(Util::extractSecond($seconds));
		//$seconds = (int)$seconds;
		//if($seconds >= 10 && $seconds < 60)
			//return "00:00:". $seconds;
		
		

	}
	
	/** [ADAPT0037] */
	public static function addGrafico($dados, $params, $ret_msg = false, $msg = ""){
		
		if(strlen($dados) > 0){
			
			$graph_height = Code::getVar("graph_height", Code::codificar($dados ."&". $params) );
			$graph_width  = Code::getVar("graph_width", Code::codificar($dados ."&". $params) );
			
			if($graph_height == "") $graph_height = 200;
			if($graph_width == "")  $graph_width = 700;
			
			return "<img style='background-color:#F1F1F1' height=". $graph_height ." width=". $graph_width ." src='". BIBLIOTECA_GRAFICO_URL ."?u=". Code::codificar($dados ."&". $params) ."'>\n";
		}
		
		if($ret_msg){
			return "<div>N&atilde;o h&aacute; dados para gerar o gr&aacute;fico [". Code::getVar("graph_title", Code::codificar($params)) ."].</div>";
		}else{
			return $msg;
		}
	}
	
	public static function zeroFill($number){
		if((int)$number < 10){
			return "0". $number;
		}
		return $number;
		
	}
	public static function somarTamanhoTodos($arr){
		$total = 0;
		for($i = 0; $i < count($arr); $i++){
			$total += strlen($arr[$i]);
		}
		return (int)$total;
	}
	
	public function metodo($str){
		//__METHOD__
		return "[". str_replace("::", "->", $str) ."]";
	}
	
	function url_exists($url){
		
		$arr = get_headers($url);
		if(strpos($arr[0], "404") !== false && (int)strpos($arr[0], "404") >= 0){
			return false;
		}
		return true;
		
	}
	
	public function temPermissao($bool, $operacao = null){
		
		if(!$bool){
			
			$arr_ret = array();
			$arr_ret['msg'] = "Voc&ecirc; n&atilde;o tem permiss&atilde;o para executar esta opera&ccedil;&atilde;o". Util::getBoolean($operacao != null, ": ". $operacao, "."); 
			$arr_ret['retorno'] = false;
			
			echo(json_encode($arr_ret));
			die();
			
		}
		
	}
	
	
	public function getDiasRegra($regra, $data_target, $texto = null){
		
		if((gettype($regra) == 'boolean' && $regra) || strlen($regra) <= 0){
			$dias = Util::getDiffDias($data_target);

			$resto = explode(".", $dias);
			
			if((int)$resto[1] > 0){
				$dias = (int)$dias + 1;
			}
			
			
			if($dias == 0){
				return "(". $texto ." hoje".")";
			}else if($dias > 0){
				return "(". $texto ." ". $dias ." ". Util::getBoolean($dias > 1, "dias", "dia") .")";
			}else if($dias < 0){
				return "(". $texto ." vencido".")";
			}
			
		}
		
		return "";
		
	}
	
	public function getDiffDias($date_until, $date_from = null){
		
		$date_until = Util::mkTimeFromDateSQL($date_until);
		if($date_from == null){
			$date_from = mktime();
		}else{
			$date_from = Util::mkTimeFromDateSQL($date_from);
		}
		
		return ((($date_until - $date_from)/ ((float)Util::getDia())));
		
	}
	
	public function toPercent($valor){
		return str_replace(".", ",", sprintf("%01.1f", $valor));
	}
	

}
//echo(Util::getTotalRecords("ged_030t_email_template"));
//echo(print_r(Util::getDBRecords("SELECT  pksv_p030t_cod FROM ged_030t_email_template LIMIT 1")));
if($_GET['sql_display'] == "sim"){
	//Util::getDBRecordsAsTable("SHOW TABLES");
	$tables = Util::getDBRecords("SHOW TABLES", null, false);
	
	foreach($tables as $table){
		echo("<h1>". $table[0] ."</h1>");
		
		$columns = $tables = Util::getDBRecords("DESC ". $table[0], null, false);
		foreach($columns as $column){
			echo($column[0] .";");
		}
		echo("<hr>");
		
		foreach($columns as $column){
			echo("public:". $column[0] .";");
		}
		
		echo("<hr>");
		
	}
}

?>
