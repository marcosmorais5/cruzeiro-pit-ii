<?php 
if(!class_exists('Banco')) require_once('Banco.php');

class Lateralidade{

	public $idlateralidade;
	public $lateralidade;

	public function setIdlateralidade($idlateralidade){
		$this->idlateralidade = $idlateralidade;
	}

	public function getIdlateralidade(){
		return $this->idlateralidade;
	}

	public function setLateralidade($lateralidade){
		$this->lateralidade = $lateralidade;
	}

	public function getLateralidade(){
		return $this->lateralidade;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_lateralidade");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_lateralidade");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_lateralidade = ". $this->getIdlateralidade());

		$this->setAll($ban);

		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){

		$ban = new Banco();
		$ban->setTabela("tb_lateralidade");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_lateralidade = ". $this->getIdlateralidade());

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
		$ban->setCondicao("id_lateralidade = ". $this->getIdlateralidade());

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
					$ban->setCondicaoIn('id_lateralidade', $params['ids'], 'AND', 'int');
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

		$ban->setTabela("tb_lateralidade");
		$ban->setCampo("id_lateralidade");
		$ban->setCampo("lateralidade");

		//executando o resultset criado: SELECT

		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new Lateralidade();
				$um_obj->setIdlateralidade($lista['id_lateralidade']);
				$um_obj->setLateralidade($lista['lateralidade']);
	

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
		$ban->setTabela("tb_lateralidade");
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
		$ban->setCampo("lateralidade", $this->getLateralidade());
	}
	
	//public static function getAllStatic(){
	//	
	//	$obj = new Lateralidade();
	//	return $obj->getAll();
	//	
	//}
	
	public static function getAllStatic($get_hash = null){
		
		$obj = new Lateralidade();
		$all = $obj->getAll();
		
		$out_so_nome = array();
		
		foreach($all as $local_obj){
			
			if($get_hash == null || !$get_hash){
				
				$tmp_obj = new Lateralidade();
				$tmp_obj->setIdlateralidade($local_obj->getIdlateralidade());
				$tmp_obj->setLateralidade($local_obj->getLateralidade());
				$out_so_nome[] = $tmp_obj;
				
			}else{
				
				$out_so_nome['idlateralidade_'. $local_obj->getIdlateralidade()] = $local_obj->getLateralidade();
				
			}
			
			
		}
		
		if(!($get_hash == null || !$get_hash)){
				
				
			$out_so_nome['idlateralidade_-1'] = "Não definido";	
			$out_so_nome['idlateralidade_0'] = "Não definido";	
			
		}
		return $out_so_nome;
			
	}

}

/* CLASS METHODS DEFINITION 
* $tmp_obj = new Lateralidade();
* $tmp_obj->setIdlateralidade($idlateralidade);
* $tmp_obj->setLateralidade($lateralidade);
* $tmp_obj = null;
* $tmp_obj->apply("i"); // Insert
* $tmp_obj->apply("u"); // Update
* $tmp_obj->apply("d"); // Delete
* * * * * * * * * * * * * */
?>