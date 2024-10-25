<?php 
if(!class_exists('Banco')) require_once('Banco.php');

class TipoPagamento{

	public $idtipopagamento;
	public $tipopagamento;

	public function setIdtipopagamento($idtipopagamento){
		$this->idtipopagamento = $idtipopagamento;
	}

	public function getIdtipopagamento(){
		return $this->idtipopagamento;
	}

	public function setTipopagamento($tipopagamento){
		$this->tipopagamento = $tipopagamento;
	}

	public function getTipopagamento(){
		return $this->tipopagamento;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_tipo_pagamento");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_tipo_pagamento");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_tipo_pagamento = ". $this->getIdtipopagamento());

		$this->setAll($ban);

		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){

		$ban = new Banco();
		$ban->setTabela("tb_tipo_pagamento");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_tipo_pagamento = ". $this->getIdtipopagamento());

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
		$ban->setCondicao("id_tipo_pagamento = ". $this->getIdtipopagamento());

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
					$ban->setCondicaoIn('id_tipo_pagamento', $params['ids'], 'AND', 'int');
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

		$ban->setTabela("tb_tipo_pagamento");
		$ban->setCampo("id_tipo_pagamento");
		$ban->setCampo("tipo_pagamento");

		//executando o resultset criado: SELECT

		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new TipoPagamento();
				$um_obj->setIdtipopagamento($lista['id_tipo_pagamento']);
				$um_obj->setTipopagamento($lista['tipo_pagamento']);
	

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
		$ban->setTabela("tb_tipo_pagamento");
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
		$ban->setCampo("tipo_pagamento", $this->getTipopagamento());
	}
	
		
	//public static function getAllStatic(){
	//	
	//	$obj = new TipoPagamento();
	//	return $obj->getAll();
	//	
	//}
	
	public static function getAllStatic($get_hash = null){
		
		$obj = new TipoPagamento();
		$all = $obj->getAll();
		
		$out_so_nome = array();
		
		foreach($all as $local_obj){
			
			if($get_hash == null || !$get_hash){
				
				$tmp_obj = new TipoPagamento();
				$tmp_obj->setIdtipopagamento($local_obj->getIdtipopagamento());
				$tmp_obj->setTipopagamento($local_obj->getTipopagamento());
				$out_so_nome[] = $tmp_obj;
				
			}else{
				
				$out_so_nome['idtipopagamento_'. $local_obj->getIdtipopagamento()] = $local_obj->getTipopagamento();
				
			}
			
			
		}
		
		if(!($get_hash == null || !$get_hash)){
				
				
			$out_so_nome['idtipopagamento_-1'] = "Não definido";	
			$out_so_nome['idtipopagamento_0'] = "Não definido";	
			
		}
		return $out_so_nome;
			
	}

}

/* CLASS METHODS DEFINITION 
* $tmp_obj = new TipoPagamento();
* $tmp_obj->setIdtipopagamento($idtipopagamento);
* $tmp_obj->setTipopagamento($tipopagamento);
* $tmp_obj = null;
* $tmp_obj->apply("i"); // Insert
* $tmp_obj->apply("u"); // Update
* $tmp_obj->apply("d"); // Delete
* * * * * * * * * * * * * */
?>