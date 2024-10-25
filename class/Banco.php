<?php
if(!class_exists('Util')) require_once('Util.php');
//if(!class_exists('Log')) require_once('Log.php');




/** CONFIGURAÇÃO DO BANCO DE DADOS
* */


define("DB_IP", "127.0.0.1");
//define("DB_IP", "mmorais.online");
define("DB_USER", "root");
//define("DB_USER", "mmoraiso_olhos");
define("DB_PWD", "@tsystems.com");


define("DB_SCHEMA", "db_medicoolhos");
//define("DB_SCHEMA", "mmoraiso_medicodeolhos");

class Banco{
	
	
	/* CHAGES
	* 2012.06.23 - Adicionado nome do banco a variavel global
	* */
	//2011-01-05
	protected $sql = "";
	protected $campo = array();
	protected $condicao = array();
	protected $ordem = array();
	protected $juncao = array();
	protected $having = array();
	protected $tabela = "";
	protected $con = false;
	protected $result = false;
	protected $totalreg = false;
	protected $nomebanco = "";
	protected $limit = 0;
	protected $offset = 0;
	protected $utf8_decode = false; /*2012-09-17*/
	
	//0212-06-02
	protected $group = array();
	
	//2011-12-03
	protected $default_ip   = "";
	protected $default_user = "";
	protected $default_pass = "";
	protected $default_db   = "";

	/*
	* CRIA A CONEXÃO COM O BANCO DE DADOS E SALVA A MESMA EM UM OBJETO DENTRO DO OBJETO
	* [0] banco --> string: nome do banco de dados que se deseja conectar
	* [1] params --> array: Se desejar conectar ao um servidor de banco de dados dirente, fornecer como param
	*								params['ip']   = ip do servidor do banco de daos
	*								params['user'] = usuário do banco de dados
	*								params['pass'] = senha do banco de dados
	*  
	*/
	public function Banco($banco = null, $params = null, $semConexao = null){
		
		
		if($semConexao != null){
			return;
		}
		
		//2011-12-03
		if(gettype($params) == "array"){
			if(sizeof($params) == 3){
				$this->default_ip   = $params['ip'];
				$this->default_user = $params['user'];
				$this->default_pass = $params['pass'];
			}
		}
		
		if(DB_IP != "DB_IP")      $this->default_ip   = DB_IP;
		if(DB_USER != "DB_USER")  $this->default_user = DB_USER;
		if(DB_PWD != "DB_PWD")    $this->default_pass = DB_PWD;
		if(DB_SCHEMA != "DB_SCHEMA")    $this->default_db = DB_SCHEMA;
		
		/* 2013-0513: Alterado de 'mysqli_connect' para 'mysqli_pconnect'*/
		$this->con = @mysqli_connect($this->default_ip, $this->default_user, $this->default_pass);
		
		if(!$this->con){
			if(class_exists("Log")) Log::log2fileFATAL("Erro ao conectar no banco de dados. Usuário ou senha podem estar errados: ". $this->default_user ."@". $this->default_ip ."");
			$this->voidErro("Erro ao tentar conectar ao banco de dados.<br>A senha ou usuário podem estar incorretos!");
		}
		
		if($banco == null){
			$banco = $this->setDB($this->default_db);
		}else{
			//$banco = mysqli_select_db($banco);
			$banco = $this->setDB($banco);
		}
		
		if(!$banco){
			$this->voidErro("Erro ao selecionar o banco.<br>O banco informado pode não existir!");
		}
	}
	
	public function setDB($nomebanco){
		return $this->setDeNomeBanco($nomebanco);
	}
	
	public function getDB(){
		return $this->getDeNomeBanco();
	}
	
	/* @Deprecated */
	public function setDeNomeBanco($nomebanco){
		$this->nomebanco = $nomebanco;
		
		if(class_exists("Log")) Log::log2fileINFO("Selecionado o banco de dados [". $this->nomebanco ."]");
		$bool = mysqli_select_db($this->con, $this->nomebanco);
		if($bool)  return true;
		if(!$bool) return false;
		
	}
	/* @Deprecated */
	public function getDeNomeBanco(){
		return $this->nomebanco;	
	}
	
	public function voidErro($msg_erro = null){
	
		$erro_mysql = (mysqli_errno($this->con) != 0)? " | Err no.: ". mysqli_errno($this->con) ." | Desc: ". mysqli_error($this->con) ."" : "";
		
		//echo("<div><div style='padding:3px;background-color:#C0C0C0;font-family:arial;font-size:12px;font-weight:bold'>ERRO - Houve uma falha ao realizar uma operação.</div><div style='padding:2px;border:1px solid #C0C0C0;font-size:12px;background-color:#F1F1F1;font-family:arial'>".
		//		$msg_erro . $erro_mysql ."</div><div>");
		
		echo("Erro no banco de dados!<br>Tente executar a operação novamente, se o erro persistir, contate o administrador.");
		if(class_exists("Log")) Log::log2fileFATAL($msg_erro . $erro_mysql);
		//return false;
		
		die();
	}
	
	public function setSql($sql){
		$this->sql = $sql;
	}

	public function getSql(){
		return $this->sql;
	}

	/*2012-09-17*/
	public function setUtf8encode($utf8_decode){
		$this->utf8_decode = $utf8_decode;
	}
	
	/*2012-09-17*/
	public function getUtf8encode(){
		return $this->utf8_decode;
	}
	
	public function setArrCampos($arrCampos){
		for($i = 0; $i < sizeof($arrCampos); $i++){
			$local = $arrCampos[$i];
			
			/* IF THE POSITION IS AN ARRAY, THEN SET THE 3 POSITIONS CONDITION
			* */
			if(gettype($local) == "array"){
				$this->setCampo($local[0], $local[1], $local[2]);
				$local = null;
			
			/* ONLY FIELDS FOR SELECTION WERE PROVIDED
			* */
			}else{
				if(strlen($local) > 0) $this->setCampo($local);
			}
			
		}
	}
	
	public function setArrFields($arrCampos){
		return $this->setArrCampos($arrCampos);
	}
	
	public function setCampo($campo, $valor = null, $tipo = null){
		$tmpArr = array($campo,$valor,$tipo);
		$this->campo[] = $tmpArr;
	}

	public function getCamposSelect(){
		
		$tmpCampo = " * ";
		if(sizeof($this->campo) > 0){
			
			//$tmpCampo = implode(",\n", $this->campo);
			
			$out_campos = array();
			foreach($this->campo as $campo){
				$out_campos[] = $campo[0];
			}
			
			$tmpCampo = implode(",\n", $out_campos);
			//for($iT = 0; $iT < sizeof($this->campo); $iT++){
			//	$tmpArr = $this->campo[$iT];
			//	$tmpVirgula = ($iT < (sizeof($this->campo)-1) ) ? ","  : "";
			//	$tmpCampo .= $tmpArr[0] . $tmpVirgula . " \n";
			//}
		}

		return $tmpCampo;
		
	}
	
	/* RECEBE UM CAMPO DE ARRAY E RETORNA O CAMPO + VALOR PARA UPDATE
	* @param --> array(campo, valor)
	* return / CAMPO = 'VALOR' /
	* */
	public function getCampoUpdate($arr_campo){
		
		return $arr_campo[0] ." = ". $this->getDado($arr_campo);
		
	}
	
	public function getDado($arr){
		
		if($arr[2] == "number" || $arr[2] == "float" || $arr[2] == "int" || $arr[2] == "now" || $arr[2] == "now()" || $arr[2] == "null" || $arr[2] == "NULL"){
			
			return $arr[1];
			
		}
		
		return "'". addslashes($arr[1]) ."'";
		
	}
	
	
	public function getCamposUpdate(){
		
		$tmpCampo = "";
		if(sizeof($this->campo) > 0){
		
			$arrAfterParser = array();
			foreach($this->campo as $campo_local){
				
				$arrAfterParser[] = $this->getCampoUpdate($campo_local);
				
			}
			$tmpCampo = implode(",\n", $arrAfterParser);
			
			//for($iT = 0; $iT < sizeof($this->campo); $iT++){
			//	$tmpArr = $this->campo[$iT];
			//	$tmpVirgula = ($iT < (sizeof($this->campo)-1) ) ? ","  : "";
			//	
			//	
			//	$tmpArr[1] = $tmpArr[1];
			//	if($this->utf8_decode) $tmpArr[1] = utf8_decode($tmpArr[1]); /* CONVERTING FROM UTF8 TO ISO-8859-1 */
			//	$valor = ($tmpArr[2] == "int" || $tmpArr[2] == "now" || $tmpArr[2] == "now()") ? $tmpArr[1] : "'". addslashes($tmpArr[1]) ."'";
			//	$tmpCampo .= $tmpArr[0] ." = ". $valor . $tmpVirgula . " ";
			//}
			
		}else{
			
			$this->voidErro("Erro ao realizar o UPDATE.<br>Nenhum campo foi fornecido para ser atualizado!");
			
		}
		return $tmpCampo;		
	}
	
	public function getCamposInsert(){
		
		$campos = array();
		$valor = array();
		
		if(sizeof($this->campo) > 0){
		
			
			foreach($this->campo as $campo_local){
				
				$campos[] = $campo_local[0];
				$valor[] = $this->getDado($campo_local);
				
			}
			
			
			//for($iT = 0; $iT < sizeof($this->campo); $iT++){
			//	$tmpArr = $this->campo[$iT];
			//	$tmpVirgula = ($iT < (sizeof($this->campo)-1) ) ? ", "  : "";
			//	
			//	//ADICIONANDO ASPAS PARA OS CAMPOS QUE TEM ASPAS
			//	$tmpArr[1] = addslashes($tmpArr[1]);
			//	if($this->utf8_decode) $tmpArr[1] = utf8_decode($tmpArr[1]); /* CONVERTING FROM UTF8 TO ISO-8859-1 */
			//	
			//	$valor .= ($tmpArr[2] == "int" || $tmpArr[2] == "now" || $tmpArr[2] == "now()") ? $tmpArr[1] . $tmpVirgula : "'". $tmpArr[1] ."'". $tmpVirgula;
			//	$tmpCampo .= $tmpArr[0] . $tmpVirgula;
			//}
			
		}else{
		
			$this->voidErro("Erro ao realizar o INSERT.<br>Nenhum campo foi fornecido para inserir os dados!");
		
		}
		$tmpCampo = "(". implode(", ", $campos) .") VALUES (". implode(", ", $valor) .")";
		return $tmpCampo;
	}
	
	
	
	public function setCondicao($condicao, $tp_condicao = null){
		if(strlen($condicao) > 0){
			$this->condicao[] = array($condicao, $tp_condicao);
			return true;
		}else{
			return false;
		}
	}
	
	public function setCondicaoNotIn($campo, $arr_valor, $tp_condicao = null, $tipo_valor = null){
	
		return $this->setCondicaoIn($campo, $arr_valor, $tp_condicao, $tipo_valor, "not_in");
	}
	
	public function setCondicaoIn($campo, $arr_valor, $tp_condicao = null, $tipo_valor = null, $in_cond = null){
		
		/** [INICIO] EVITANDO PROBLEMAS DE POSIÇÕES NA ARRAY */
		$arr_tmp = array();
		foreach($arr_valor as $obj){
			$arr_tmp[] = $obj;
			
		}
		$arr_valor = $arr_tmp;
		/** [FIM] EVITANDO PROBLEMAS DE POSIÇÕES NA ARRAY */
		
		$add_campo = "";
		$tem_obj = false;
		
		for($i = 0; $i < sizeof($arr_valor); $i++)
		{
			if(strlen(trim($arr_valor[$i])) > 0){ /* 2012-09-14 - Filter empty values */
				if($tipo_valor == null || $tipo_valor == "string") $add_campo .= "'". $arr_valor[$i] ."'";
				if($tipo_valor == "int") $add_campo .= $arr_valor[$i];
				if($tipo_valor == "date") $add_campo .= $arr_valor[$i];
				
				if($i < sizeof($arr_valor) - 1){
					$add_campo .= ", ";
				}
				
				$tem_obj = true;
			}
			
		}
		
		
		
		if($add_campo != "" && $tem_obj = true){
			
			if($in_cond == null){
				$add_campo = $campo . " IN(". $add_campo .") ";
			}else{
				$add_campo = $campo . " NOT IN(". $add_campo .") ";
			}
			$this->condicao[] = array($add_campo, $tp_condicao);
			
		}
		
		
	}
	
	/*
	@ campo --> Campo do banco de dados
	@ sentenca --> Frase que será buscada
	@ tipo_like --> [ENTIRE, ANY = ANY_AND, ANY_OR]
	@               ENTIRE - Busca palavra / senença ineira
	@               ANY = ANY_AND - Busca por cada palavra, independente da posição com condição E
	@               ANY_OR - Busca por cada palavra, independente da posição com condição OU
	*/
	public function setCondicaoLike($campo, $sentenca, $tipo_like, $tp_condicao = null){
		
		$ret = "";
		$process = false;
		
		//$sentenca = split(" ", $sentenca);
		if($tipo_like == "ENTIRE"){
			$ret = $campo. " LIKE '%". $sentenca ."%' ";
			$process = true;
		}else{
			
			$del = "";
			
			if($tipo_like == "ANY" OR $tipo_like == "ANY_AND"){
				$del = " AND ";
				
			}else{ // ANY_OR
				$del = " OR ";
			}
			
			$sentenca = str_replace("  ", " ", trim($sentenca));
			$sentenca = split(" ", $sentenca);
			
			for($k_ = 0; $k_ < sizeof($sentenca); $k_++){
				
				if(strlen(trim($sentenca[$k_])) > 2){
					$process = true;
					$ret .= $campo ." LIKE '%". $sentenca[$k_] ."%' ". $del;
				}
				
			}
			
			$ret = substr($ret, 0, strlen($ret) - strlen($del));
		}
		
		
		// SE TEM ALGO PARA ADICIONAR À CONDIÇÃO
		if($process) $this->setCondicao($ret, $tp_condicao);
		
		return $process;
		
	}
	
	public function parseOrFromArray($strArray){
		return $this->parseFromArray($strArray, "OR");
	}
	
	public function parseAndFromArray($strArray){
		return $this->parseFromArray($strArray, "AND");
	}
	
	/* PARSE DE ARRAY DE CONDICOES 
	* $strArray = Array de condicoes
	* $type = OR | AND
	* */
	public function parseFromArray($strArray, $type = null){
		
		$ret = "";
		
		if($type == null) $type = "AND";
		for($oO = 0; $oO < sizeof($strArray); $oO++){
			
			$ret .= "(". $strArray[$oO] .")";
			
			if($oO < sizeof($strArray) - 1) $ret .= " ". $type ." ";
			
		}
		
		return $ret;
		
	}
	
	public function setWhere($condicao, $tp_condicao = null){
		$this->setCondicao($condicao, $tp_condicao);
	}
	
	public function getCondicao(){
		return $this->condicao;
	}
	
	public function getCondicoes(){
	
		$tmp_cond = "";
		$tem_condicao = false;
		
		if(sizeof($this->getCondicao()) > 0){
		
			for($iC = 0; $iC < sizeof($this->getCondicao()); $iC++){
				$arr_cond = $this->getCondicao();
				$pos_cod = $arr_cond[$iC];
				
				if(strtolower($pos_cod[1]) != "or" && strtolower($pos_cod[1]) != "and"){
					$pos_cod[1] = "AND";
				}
				
				$tp_comp = ($iC < sizeof($this->getCondicao()) - 1) ? " ". $pos_cod[1] ." " : "";
				
				$tmp_cond = "(". $tmp_cond . $pos_cod[0] .") ". $tp_comp ."\n";
				
				$tem_condicao = true;
			}
			
		}else{
			
			$tmp_cond = "";
			
		}
		
		
		
		if($tem_condicao){
			$tmp_cond = " WHERE ". $tmp_cond;
			return $tmp_cond;
		}else{
			return "";
		}
	}

	public function setGroupBy($campo){
		$this->group[] = $campo;
	}
	
	public function getGroupBy($campo){
		return $this->group;
	}
	
	public function getGroupBys(){
		
		$ret = "";
		if(sizeof($this->group) > 0){
			
			for($i = 0; $i < sizeof($this->group); $i++){
				
				$coma = ($i < sizeof($this->group) - 1) ? "," : "";
				$ret .= $this->group[$i] . $coma ." ";
				
			}
			
		}
		
		if($ret != "") $ret = " GROUP BY ". $ret;
		return $ret;
		
	}
	
	public function setOrdem($campo, $ordem = null){
		$this->ordem[] = array($campo, $ordem);
	}

	public function getOrdem(){
		return $this->ordem;
	}
	
	// 26/02/2012
	public function setOrdemArray($arr){
		
		if(gettype($arr) == "array"){
			for($i_ = 0; $i_ < sizeof($arr); $i_++){
				
				$local_ = $arr[$i_];
				
				$this->setOrdem($local_[0], $local_[1]);
				
			}
			
		}
		
	}
	
	public function getOrdens(){
		$tmp_ordem = "";
		$arr_ordem = $this->getOrdem();
		
		if(sizeof($arr_ordem) > 0){
			for($iO = 0; $iO < sizeof($arr_ordem); $iO++){
				$pos_atual = $arr_ordem[$iO];
				$tmp_virg = ($iO < (sizeof($arr_ordem) - 1)) ? ", " : " ";
				$tmp_orde = (strtolower($pos_atual[1]) == strtolower("DESC")) ? " DESC" : " ASC";
				$tmp_ordem .= $pos_atual[0] . $tmp_orde .$tmp_virg;
			}
			
			$tmp_ordem = " ORDER BY ". $tmp_ordem;
		}
		
		return $tmp_ordem;
		
	}

	public function setJoin($tabela, $campo1 = null, $campo2 = null){
		$this->setJuncao($tabela, $campo1, $campo2);
	}
	
	public function setJuncao($tabela, $campo1 = null, $campo2 = null){
		$this->juncao[] = array($tabela, $campo1, $campo2);
	}

	public function getJuncao(){
		return $this->juncao;
	}
	
	public function getJuncoes(){
	
		$tmp_junc = "";
		$arr_junc = $this->getJuncao();
		
		for($iJ = 0; $iJ < sizeof($arr_junc); $iJ++){
			$junc_atual = $arr_junc[$iJ];
			
			if($junc_atual[1] != '' && $junc_atual[2] != ''){
				$tmp_junc .= " INNER JOIN ". $junc_atual[0] ." ON ". $junc_atual[1] ." = ". $junc_atual[2] ." ";
			}
			
		}
		
		return $tmp_junc;
		
	}

	public function setTabela($tabela){
		$this->tabela = $tabela;
	}

	public function getTabela($tp_execute = null){
		if($this->tabela == ""){
			$this->voidErro("Não foi selecionada uma tabela para realizar a operação de ". $tp_execute);
		}else{
			return $this->tabela;
		}
	}

	public function setCon($con){
		$this->con = $con;
	}

	public function getCon(){
		return $this->con;
	}

	/* CHANGES
	* Parametro /query/ AGORA ACEITA 'update', 'insert', 'delete' e cria a query automaticamente;
	* ---- ADICIONAR NO PORTAL DE DOCUMENTACAO
	* */
	public function setResult($query, $return = null){
		
		if(strtolower($query) == "update"){
			if(class_exists("Log")) Log::log2fileTRACE($this->getUpdate());
			$tmp_result = @mysqli_query($this->con, $this->getUpdate());
			$query = $this->getUpdate();
		}else if(strtolower($query) == "insert"){
			if(class_exists("Log")) Log::log2fileTRACE($this->getInsert());
			$tmp_result = @mysqli_query($this->con, $this->getInsert());
			$query = $this->getInsert();
		}else if(strtolower($query) == "select"){
			if(class_exists("Log")) Log::log2fileTRACE($this->getSelect());
			$tmp_result = @mysqli_query($this->con, $this->getSelect());
			$query = $this->getSelect();
		}else if(strtolower($query) == "delete"){
			if(class_exists("Log")) Log::log2fileTRACE($this->getDelete());
			$tmp_result = @mysqli_query($this->con, $this->getDelete());
			$query = $this->getDelete();
		}else{
			
			/* QUERY É UM SELECT, NÃO UM PARÂMETRO
			* */
			if(class_exists("Log")) Log::log2fileTRACE($query);
			$tmp_result = @mysqli_query($this->con, $query);
		}
		
		
		if($tmp_result){
			
			$this->result = $tmp_result;
			
			if(class_exists("Log")) Log::log2fileTRACE("Query executada com sucesso [TOTAL = ". @mysqli_num_rows($this->con, $this->getResult()) .", AFFECTED = ". mysqli_affected_rows($this->con)."]: ". $query);
			//if(class_exists("Log")) Log::log2fileTRACE("Query executada com sucesso: ". $query);
			
			if($return != null){
				return true;
			}
			
			//if(class_exists("Log")) Log::log2fileTRACE("Query executada com sucesso: ". $query);
			
		}else{
			
			if(class_exists("Log")) Log::log2fileERROR("Erro na execução da query: ". $query);
			if(class_exists("Log")) Log::log2fileERROR("MySQL [mysqli_errno = ". mysqli_errno($this->con) .", error = ". mysqli_error($this->con));
			
			if($return != null){
				
				
				return false;
			}
			
			/* SE O RETORNO NÃO FOR SE TRATADO, ENTÃO MOSTRA O ERRO NA TELA */
			$this->voidErro("Falha ao tentar realizar a query: <br><span style='font-family:courier new'>". $query ."</span>");
		}
	}
	
	public function applyResult($query){
	
		if(strtolower($query) == "u" || strtolower($query) == "update") $query = "update";
		if(strtolower($query) == "i" || strtolower($query) == "insert") $query = "insert";
		if(strtolower($query) == "s" || strtolower($query) == "select") $query = "select";
		if(strtolower($query) == "d" || strtolower($query) == "delete") $query = "delete";
		$pos = $this->setResult($query, true);
		
		if($pos){
			return $this->result;
		}else{
			return false;
		}
	}

	public function getResult(){
		return $this->result;
	}
	
	/* RETORNA TODOS OS DADOS DE UM SELECT EM FORMATO DE ARRAY */
	public function getResultSelect(){
		
		$output = array();
		
		$this->setResult($this->getSelect());
		$ret = $this->getResult();

		while($lista = mysqli_fetch_array($ret) ){
			$output[] = $lista;
		}
		
		return $output;
		
	}
	
	public function voidFreeResult(){
		@mysqli_free_result($this->getResult());
	}
	
	public function free(){
		$this->voidFreeResult();
	}

	/* @Deprecated */
	public function getTotalReg(){
		return mysqli_num_rows($this->con, $this->result);
	}
	
	public function getSelect(){
		
		/* PRIORIDADE PARA O SQL ENTRADO MANUALMENTE 
		* */
		if($this->getSql() != ""){
		
			return $this->getSql();
			
		}else{
			
			$campos = $this->getCamposSelect();
			$condicoes = $this->getCondicoes();
			$ordem = $this->getOrdens();
			$juncoes = $this->getJuncoes();
			$tabela = $this->getTabela("SELECT.");
			$limite = $this->getLimits();
			$offset = $this->getOffsets();
			$group_by = $this->getGroupBys();
			
			$tmp_sql = "SELECT ". $campos ." FROM ". $tabela ." ". $juncoes ." ". $condicoes ." ". $group_by ." ". $ordem ." ". $limite ." ". $offset;
			return $tmp_sql;
			
		}
		
		return false;
		
	}
	
	public function getUpdate(){
		
		if($this->getSql() != ""){
		
			return $this->getSql();
			
		}else{
			
			$campos = $this->getCamposUpdate();
			$condicoes = $this->getCondicoes();
			$tabela = $this->getTabela("UPDATE.");
			
			$tmp_sql = "UPDATE ". $tabela ." SET ". $campos ." ". $condicoes;
			return $tmp_sql;
			
		}
		
	}
	
	public function getInsert(){
		
		if($this->getSql() != ""){
		
			return $this->getSql();
			
		}else{
			
			$campos = $this->getCamposInsert();
			$condicoes = $this->getCondicoes();
			$tabela = $this->getTabela("DELETE.");
			
			$tmp_sql = "INSERT INTO ". $tabela ." ". $campos;
			return $tmp_sql;
			
		}
		
	}
	
	public function getDelete(){
		
		if($this->getSql() != ""){
		
			return $this->getSql();
			
		}else{

			
			$condicoes = $this->getCondicoes();
			$tabela = $this->getTabela("INSERT.");
			
			$tmp_sql = "DELETE FROM ". $tabela ." ". $condicoes;
			return $tmp_sql;
			
		}
		
	}
	
	public function getId(){
		return $this->getInsertId();
	}
	public function getInsertId(){
		return mysqli_insert_id($this->con);
	}
	
	public function getAffectedRows(){
		return mysqli_affected_rows();
	}
	
	public function getNumRegs(){
		
		$tmp_num = @mysqli_num_rows($this->con, $this->getResult());
		
		if(!$this->getResult()){
			//$this->voidErro("Falha ao buscar o número de registros.<br>Não há um resultset para mostrar o valor.");
			return -1; /* 2012-09-14 - In case of failure returns -1 */
		}
		
		return $tmp_num;
	}
	
	/* ADDED: 2012-06-01 */
	public function getCount(){
		return $this->getNumRegs();
	}
	
	/* ADDED: 2012-06-01 */
	public function getTotal(){
		return $this->getNumRegs();
	}
	
	/* @Deprecated */
	public function fecharConexao(){
		@mysqli_close($this->con);
	}
	
	/* ADDED: 2012-06-01 */
	public function close(){
		@mysqli_close($this->con);
	}
	
	public function iniciarConexao(){
		$this->Banco();
	}

	public function ini(){
		$this->iniCampos();
	}
	
	public function iniCampos(){
		$this->sql = "";
		$this->campo = array();
		$this->condicao = array();
		$this->ordem = array();
		$this->juncao = array();
		$this->having = array();
		$this->tabela = "";
		$this->con = false;
		$this->result = false;
		$this->totalreg = false;
		$this->limit = 0;
		$this->group = array();
	}
	
	public function inicialize(){
		$this->iniCampos();
	}
	
	/* MÉTODO IMPLEMNTADO PARA RESOLVER O PROBLEMA DO PHP DE -- DATE TIME --
	* */
	public static function getDateTimeDB(){
		
		$con = new Banco();
		$con->setSql("select now() as date_time");
		$con->setResult($con->getSelect());
		$ret = $con->getResult();
		$arr = mysqli_fetch_array($ret);
		$con->fecharConexao();
		
		return $arr[0];
		
	}
	
	public function getOrIfNotEmpty($strVal){
		if(strlen($strVal) > 0){
			return "OR";
		}else{
			return "";
		}
	}
	
	public function setLimit($limit){
		$this->limit = (int)$limit;
	}
	
	public function getLimit(){
		return $this->limit;
	}
	
	public function getLimits(){
		if((int)$this->limit > 0){
			return " LIMIT ". $this->limit;
		}else{
			return "";
		}
	}
	
	public function setOffset($offset){
		$this->offset = (int)$offset;
	}
	
	public function getOffset(){
		return $this->offset;
	}
	
	public function getOffsets(){
		if((int)$this->offset > 0){
			return " OFFSET ". $this->offset;
		}else{
			return "";
		}
	}

}

//$ret = $ban->setCondicaoLike("de_title", "de", "ANY", "AND");

/*
$ban = new Banco();
$ban->setTabela("ged_010t_status_arquivo");
$ban->setCampo("atsv_p010t_descricao", utf8_encode("4 - Publicado"));
$ban->setCondicao("pkni_p010t_cod = 4");
$ban->setResult($ban->getUpdate());
$ban->fecharConexao();
*/
?>