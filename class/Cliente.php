<?php 
if(!class_exists('Banco')) require_once('Banco.php');
if(!class_exists('Orcamento')) require_once('Orcamento.php');

class Cliente{

	public $idcliente;
	public $nome;
	public $datanascimento;
	public $cpf;
	public $email;
	public $telefone;
	public $datecreated;
	public $createdby;
	public $orcamentos = array();

	public function setIdcliente($idcliente){
		$this->idcliente = $idcliente;
	}

	public function getIdcliente(){
		return $this->idcliente;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setDatanascimento($datanascimento){
		$this->datanascimento = $datanascimento;
	}

	public function getDatanascimento(){
		return $this->datanascimento;
	}

	public function setCpf($cpf){
		$this->cpf = $cpf;
	}

	public function getCpf(){
		return $this->cpf;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}

	public function getTelefone(){
		return $this->telefone;
	}

	public function setDatecreated($datecreated){
		$this->datecreated = $datecreated;
	}

	public function getDatecreated(){
		return $this->datecreated;
	}

	public function setCreatedby($createdby){
		$this->createdby = $createdby;
	}

	public function getCreatedby(){
		return $this->createdby;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_cliente");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		
		if($ret){
			
			$this->setIdcliente($ban->getId());
			
		}
		
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_cliente");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_cliente = ". $this->getIdcliente());

		$this->setAll($ban);

		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){

		$ban = new Banco();
		$ban->setTabela("tb_cliente");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_cliente = ". $this->getIdcliente());

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
		$ban->setCondicao("id_cliente = ". $this->getIdcliente());

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
					$ban->setCondicaoIn('id_cliente', $params['ids'], 'AND', 'int');
				}else{
					return array();
				}
			}
			
			if($params['nome'] != null){
					
					$ban->setCondicao(" (nome like '%". $params['nome'] ."%') ", "AND");
				
			}
		}

		return $this->getGenericSelect($ban, $json);
	}

	public function getAll($params = null){
		return $this->getTodosRegistros($params);
	}

	/*IMPLEMENTANDO O MÉTODO GENERICO DE SELECT*/
	public function getGenericSelect($ban, $json = null){

		$ban->setTabela("tb_cliente");
		$ban->setCampo("id_cliente");
		$ban->setCampo("nome");
		$ban->setCampo("data_nascimento");
		$ban->setCampo("cpf");
		$ban->setCampo("email");
		$ban->setCampo("telefone");
		$ban->setCampo("date_created");
		$ban->setCampo("created_by");
		
		$ban->setOrdem("nome");

		//executando o resultset criado: SELECT

		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new Cliente();
				$um_obj->setIdcliente($lista['id_cliente']);
				$um_obj->setNome($lista['nome']);
				$um_obj->setDatanascimento($lista['data_nascimento']);
				$um_obj->setCpf($lista['cpf']);
				$um_obj->setEmail($lista['email']);
				$um_obj->setTelefone($lista['telefone']);
				$um_obj->setDatecreated($lista['date_created']);
				$um_obj->setCreatedby($lista['created_by']);
	

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
		$ban->setTabela("tb_cliente");
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
		$ban->setCampo("nome", $this->getNome());
		
		if($this->getDatanascimento() != "" && $this->getDatanascimento() != null)
			$ban->setCampo("data_nascimento", $this->getDatanascimento());
		if($this->getDatanascimento() == null) $ban->setCampo("data_nascimento", "NULL", "int");
		$ban->setCampo("cpf", $this->getCpf());
		$ban->setCampo("email", $this->getEmail());
		$ban->setCampo("telefone", $this->getTelefone());
		
		$ban->setCampo("created_by", $this->getCreatedby(), "int");
	}
	
	
	public static function getAllStatic($get_hash = null, $carregar_orcamentos = null){
		
		$obj = new Cliente();
		$all = $obj->getAll();
		
		$out_so_nome = array();
		$lista_clientes = array();
		
		foreach($all as $local_obj){
			
			if($get_hash == null || !$get_hash){
				
				$tmp_obj = new Cliente();
				$tmp_obj->setIdcliente($local_obj->getIdcliente());
				$tmp_obj->setNome($local_obj->getNome());
				
				/* PREENCHENDO OS ORÇAMENTOS DO CLIENTE */
				if($carregar_orcamentos != null)
					$tmp_obj->getOrcamentos();
				
				$out_so_nome[] = $tmp_obj;
				
			}else{
				
				$out_so_nome['idcliente_'. $local_obj->getIdcliente()] = $local_obj->getNome();
				
			}
			
			
		}
		
		if(!($get_hash == null || !$get_hash)){
				
				
			$out_so_nome['idcliente_-1'] = "Não definido";	
			$out_so_nome['idcliente_0'] = "Não definido";	
			
		}
		return $out_so_nome;
			
	}
	
	public function getOrcamentos(){
		
		$tmp_obj = new Orcamento();
		$tmp_obj->setIdcliente($this->getIdcliente());
		
		$this->orcamentos = $tmp_obj->getOrcamentosPorCliente();
		
		return $this->orcamentos;
	}


}

?>