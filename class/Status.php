<?php 
if(!class_exists('Banco')) require_once('Banco.php');

class Status implements BancoOperacoes{

	public static $FECHADO = 1;
	public static $NAO_FECHADO = 2;
	public static $PENDENTE = 3;
	public static $S_D = 4;
	public static $C_D = 5;
	/*
	1 --> FECHADO
	2 --> NÃƒO FECHADO
	3 --> PENDENTE
	4 --> S/D
	5 --> C/D
	*/
	public $idstatus;
	public $destatus;

	public function setIdstatus($idstatus){
		$this->idstatus = $idstatus;
	}

	public function getIdstatus(){
		return $this->idstatus;
	}

	public function setDestatus($destatus){
		$this->destatus = $destatus;
	}

	public function getDestatus(){
		return $this->destatus;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_status");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_status");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_status = ". $this->getIdstatus());

		$this->setAll($ban);

		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){

		$ban = new Banco();
		$ban->setTabela("tb_status");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_status = ". $this->getIdstatus());

		//executando o resultset criado: DELETE
		$ret = $ban->setResult($ban->getDelete(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO DE UM REGISTRO*/
	public function getOne(){
		return $this->getUmRegistroChave();
	}

	public function getUmRegistroChave(){

		$ban = new Banco();

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_status = ". $this->getIdstatus());

		$ret_ = $this->getGenericSelect($ban);
		if(sizeof($ret_) > 0){
			return $ret_[0];
		}else{
			return null;
		}

	}

	/*IMPLEMENTANDO O MÉTODO DE TODOS OS REGISTROS*/
	public function getTodosRegistros($params = null){

		$json = null;

		$ban = new Banco();
		if(gettype($params) == "array"){

			/* PASSANDO O PARÂMETRO DE JSON */
			if($params['json']){
				$json = $params['json'];
			
}
			if((int)$params['limit'] > 0)
				$ban->setLimit($params['limit']);

			if((int)$params['offset'] > 0){
				$ban->setOffset((int)$params['offset']);
				if((int)$params['limit'] <= 0) $ban->setLimit(1);
			}

			if(gettype($params['ids']) == 'array'){
				if(count($params['ids']) > 0){
					$ban->setCondicaoIn('id_status', $params['ids'], 'AND', 'int');
				}else{
					return array();
				}
			}
		}

		return $this->getGenericSelect($ban, $json);
	}

	public function getAll($params = null){
		return $this->getTodosRegistros($params);
	}

	/*IMPLEMENTANDO O MÉTODO GENERICO DE SELECT*/
	public function getGenericSelect($ban, $json = null){

		$ban->setTabela("tb_status");
		$ban->setCampo("id_status");
		$ban->setCampo("de_status");

		//executando o resultset criado: SELECT

		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new Status();
				$um_obj->setIdstatus($lista['id_status']);
				$um_obj->setDestatus($lista['de_status']);
	

				$arr_ret_todos[] = $um_obj;
				$um_obj = null;

			}else{
				$arr_ret_todos[] = $lista;
			}
		}
		$ban->fecharConexao();

		return $arr_ret_todos;
	}

	public function apply($opt){
		if($opt == "insert" or $opt == "i") return $this->inserir();
		if($opt == "update" or $opt == "u") return $this->atualizar();
		if($opt == "delete" or $opt == "d") return $this->excluir();

	}

	/* IMPLEMENTANDO O MÉTODO DE CONTAGEM DE DADOS */
	public function totalRecords(){

		$ban = new Banco();
		$ban->setTabela("tb_status");
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

	public function setAll($ban){
	/*IMPLEMENTANDO O MÉTODO SET DOS CAMPOS DESTA CLASSE*/
		$ban->setCampo("de_status", $this->getDestatus());
	}

	//public static function getAllStatic(){
	//	
	//	$obj = new Status();
	//	return $obj->getAll();
	//	
	//}
	
	public static function getAllStatic($get_hash = null){
		
		$obj = new Status();
		$all = $obj->getAll();
		
		$out_so_nome = array();
		
		foreach($all as $local_obj){
			
			if($get_hash == null || !$get_hash){
				
				return $all;
				
			}else{
				
				$out_so_nome['idstatus_'. $local_obj->getIdstatus()] = $local_obj->getDestatus();
				
			}
			
			
		}
		
		if(!($get_hash == null || !$get_hash)){
				
				
			$out_so_nome['idstatus_-1'] = "Não definido";	
			$out_so_nome['idstatus_0'] = "Não definido";	
			
		}
		return $out_so_nome;
			
	}
	
}

?>