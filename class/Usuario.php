<?php 
if(!class_exists('Banco')) require_once('Banco.php');

class Usuario{

	public $idusuario;
	public $loginusuario;
	public $nomeusuario;
	public $senhausuario;
	public $ativo = "Y";
	public $datecreated;
	public $dateupdated;
	
	/* GRUPO = {
		USUARIO,
		CAIXA,
		ADMINISTRADOR,
		ADMINISTRADOR_MESTRE
	}
	*/
	public $grupo = "USUARIO";

	public function setGrupo($grupo){
		$this->grupo = $grupo;
	}

	public function getGrupo(){
		return $this->grupo;
	}
	
	public function setIdusuario($idusuario){
		$this->idusuario = $idusuario;
	}

	public function getIdusuario(){
		return $this->idusuario;
	}

	public function setLoginusuario($loginusuario){
		$this->loginusuario = $loginusuario;
	}

	public function getLoginusuario(){
		return $this->loginusuario;
	}

	public function setNomeusuario($nomeusuario){
		$this->nomeusuario = $nomeusuario;
	}

	public function getNomeusuario(){
		return $this->nomeusuario;
	}

	public function setSenhausuario($senhausuario){
		$this->senhausuario = $senhausuario;
	}

	public function getSenhausuario(){
		return $this->senhausuario;
	}

	public function setAtivo($ativo){
		$this->ativo = $ativo;
	}

	public function getAtivo(){
		return $this->ativo;
	}

	public function setDatecreated($datecreated){
		$this->datecreated = $datecreated;
	}

	public function getDatecreated(){
		return $this->datecreated;
	}

	public function setDateupdated($dateupdated){
		$this->dateupdated = $dateupdated;
	}

	public function getDateupdated(){
		return $this->dateupdated;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_usuario");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_usuario");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_usuario = ". $this->getIdusuario());

		//$this->setDateupdated(date("Y-m-d H:i:s"));
		$this->setAll($ban);
		
		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){

		$ban = new Banco();
		$ban->setTabela("tb_usuario");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("id_usuario = ". $this->getIdusuario());

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
		$ban->setCondicao("id_usuario = ". $this->getIdusuario());

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
					$ban->setCondicaoIn('id_usuario', $params['ids'], 'AND', 'int');
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

	public function fazerLogin(){
		
		$ban = new Banco();
		$ban->setCondicao("login_usuario = '". $this->getLoginusuario() ."'");
		$all = $this->getGenericSelect($ban, $json);
		
		if(sizeof($all) > 0){
			return $all[0];
		}else{
			return null;
		}
		
	}
	
	/*IMPLEMENTANDO O MÉTODO GENERICO DE SELECT*/
	public function getGenericSelect($ban, $json = null){

		$ban->setTabela("tb_usuario");
		$ban->setCampo("id_usuario");
		$ban->setCampo("login_usuario");
		$ban->setCampo("nome_usuario");
		$ban->setCampo("senha_usuario");
		$ban->setCampo("ativo");
		$ban->setCampo("date_created");
		$ban->setCampo("date_updated");
		$ban->setCampo("grupo");
		

		//executando o resultset criado: SELECT

		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new Usuario();
				$um_obj->setIdusuario($lista['id_usuario']);
				$um_obj->setLoginusuario($lista['login_usuario']);
				$um_obj->setNomeusuario($lista['nome_usuario']);
				$um_obj->setSenhausuario($lista['senha_usuario']);
				$um_obj->setAtivo($lista['ativo']);
				$um_obj->setDatecreated($lista['date_created']);
				$um_obj->setDateupdated($lista['date_updated']);
				$um_obj->setGrupo($lista['grupo']);
	

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
		$ban->setTabela("tb_usuario");
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
		$ban->setCampo("login_usuario", $this->getLoginusuario());
		$ban->setCampo("nome_usuario", $this->getNomeusuario());
		$ban->setCampo("senha_usuario", $this->getSenhausuario());
		$ban->setCampo("ativo", $this->getAtivo());
		
		//if($this->getDatecreated() != "" && $this->getDatecreated() != null)
			//$ban->setCampo("date_created", $this->getDatecreated());
		//if($this->getDatecreated() == null) $ban->setCampo("date_created", "NULL", "int");
		
		if($this->getDateupdated() != "" && $this->getDateupdated() != null)
			$ban->setCampo("date_updated", $this->getDateupdated());
		if($this->getDateupdated() == null) $ban->setCampo("date_updated", "NULL", "int");
		
		$ban->setCampo("grupo", $this->getGrupo());
		
	}
	
	public function getGrupoUsuario($id_usuario){
		
		
		$tmp_obj = new Usuario();
		$tmp_obj->setIdusuario($id_usuario);
		$obj = $tmp_obj->getOne();
		
		if($obj != null){
			
			return $obj->getGrupo();
			
		}
		
		return "USUARIO";
		
	}
	
	public static function getAllStatic($get_hash = null){
		
		$obj = new Usuario();
		$all = $obj->getAll();
		
		$out_so_nome = array();
		$lista_clientes = array();
		
		foreach($all as $local_obj){
			
			if($get_hash == null || !$get_hash){
				
				$tmp_obj = new Usuario();
				$tmp_obj->setIdusuario($local_obj->getIdusuario());
				$tmp_obj->setNomeusuario($local_obj->getNomeusuario());
				$tmp_obj->setLoginusuario($local_obj->getLoginusuario());
				$out_so_nome[] = $tmp_obj;
				
			}else{
				
				$out_so_nome['idusuario_'. $local_obj->getIdusuario()] = $local_obj->getNomeusuario();
				
			}
			
			
		}
		
		if(!($get_hash == null || !$get_hash)){
				
				
			$out_so_nome['idusuario_-1'] = "Não definido";	
			$out_so_nome['idusuario_0'] = "Não definido";	
			
		}
		return $out_so_nome;
			
	}

}

?>