<?php 
if(!class_exists('Banco')) require_once('Banco.php');
if(!class_exists('Status')) require_once('Status.php');
if(!class_exists('Usuario')) require_once('Usuario.php');
if(!class_exists('Util')) require_once('Util.php');
if(!class_exists('Grupo')) require_once('Grupo.php');

class Orcamento implements BancoOperacoes {

	public $cod;
	public $data = null;
	public $idcliente = -1;
	public $valoroperacao = 0;
	public $idtipopagamento = -1;
	public $idservico = -1;
	public $idprocedimento = -1;
	public $datarealizacao = null;
	public $idmedico = -1;
	public $idlateralidade = -1;
	public $idopme = -1;
	public $idstatus = -1;
	public $datecreated;
	public $dateupdate = null;
	public $datedeleted = null;
	public $craetedby = -1;
	public $updatedby = -1;
	public $deletedby = -1;
	public $obs;
	public $dateclosed = null;
	public $caixaok = "N";
	public $caixaokby = -1;
	public $caixaokdatetime = null;
	public $tudook = "N";
	public $tudookpor = -1;
	public $tudookdatetime = null;

	public function setTudook($tudook){
		$this->tudook = $tudook;
	}

	public function getTudook(){
		return $this->tudook;
	}

	public function setTudookpor($tudookpor){
		$this->tudookpor = $tudookpor;
	}

	public function getTudookpor(){
		return $this->tudookpor;
	}

	public function setTudookdatetime($tudookdatetime){
		$this->tudookdatetime = $tudookdatetime;
	}

	public function getTudookdatetime(){
		return $this->tudookdatetime;
	}
	
	public function setCaixaokdatetime($caixaokdatetime){
		$this->caixaokdatetime = $caixaokdatetime;
	}

	public function getCaixaokdatetime(){
		return $this->caixaokdatetime;
	}
	
	public function setCaixaokby($caixaokby){
		$this->caixaokby = $caixaokby;
	}

	public function getCaixaokby(){
		return $this->caixaokby;
	}
	
	public function setCaixaok($caixaok){
		$this->caixaok = $caixaok;
	}

	public function getCaixaok(){
		return $this->caixaok;
	}

	public function setDateclosed($dateclosed){
		$this->dateclosed = $dateclosed;
	}

	public function getDateclosed(){
		return $this->dateclosed;
	}
	
	public function setCod($cod){
		$this->cod = $cod;
	}

	public function getCod(){
		return $this->cod;
	}

	public function setData($data){
		$this->data = $data;
	}

	public function getData(){
		return $this->data;
	}

	public function setIdcliente($idcliente){
		$this->idcliente = $idcliente;
	}

	public function getIdcliente(){
		return $this->idcliente;
	}

	public function setValoroperacao($valoroperacao){
		$this->valoroperacao = $valoroperacao;
	}

	public function getValoroperacao(){
		return $this->valoroperacao;
	}

	public function setIdtipopagamento($idtipopagamento){
		$this->idtipopagamento = $idtipopagamento;
	}

	public function getIdtipopagamento(){
		return $this->idtipopagamento;
	}

	public function setIdservico($idservico){
		$this->idservico = $idservico;
	}

	public function getIdservico(){
		return $this->idservico;
	}

	public function setIdprocedimento($idprocedimento){
		$this->idprocedimento = $idprocedimento;
	}

	public function getIdprocedimento(){
		return $this->idprocedimento;
	}

	public function setDatarealizacao($datarealizacao){
		$this->datarealizacao = $datarealizacao;
	}

	public function getDatarealizacao(){
		return $this->datarealizacao;
	}

	public function setIdmedico($idmedico){
		$this->idmedico = $idmedico;
	}

	public function getIdmedico(){
		return $this->idmedico;
	}

	public function setIdlateralidade($idlateralidade){
		$this->idlateralidade = $idlateralidade;
	}

	public function getIdlateralidade(){
		return $this->idlateralidade;
	}

	public function setIdopme($idopme){
		$this->idopme = $idopme;
	}

	public function getIdopme(){
		return $this->idopme;
	}

	public function setIdstatus($idstatus){
		$this->idstatus = $idstatus;
	}

	public function getIdstatus(){
		return $this->idstatus;
	}

	public function setDatecreated($datecreated){
		$this->datecreated = $datecreated;
	}

	public function getDatecreated(){
		return $this->datecreated;
	}

	public function setDateupdate($dateupdate){
		$this->dateupdate = $dateupdate;
	}

	public function getDateupdate(){
		return $this->dateupdate;
	}

	public function setDatedeleted($datedeleted){
		$this->datedeleted = $datedeleted;
	}

	public function getDatedeleted(){
		return $this->datedeleted;
	}

	public function setCraetedby($craetedby){
		$this->craetedby = $craetedby;
	}

	public function getCraetedby(){
		return $this->craetedby;
	}

	public function setUpdatedby($updatedby){
		$this->updatedby = $updatedby;
	}

	public function getUpdatedby(){
		return $this->updatedby;
	}

	public function setDeletedby($deletedby){
		$this->deletedby = $deletedby;
	}

	public function getDeletedby(){
		return $this->deletedby;
	}

	public function setObs($obs){
		$this->obs = $obs;
	}

	public function getObs(){
		return $this->obs;
	}


	/*IMPLEMENTANDO O MÉTODO INSERIR*/
	public function inserir(){

		$ban = new Banco();
		$ban->setTabela("tb_cirurgia");

		$this->setAll($ban);

		//executando o resultset criado: INSERT
		$ret = $ban->setResult($ban->getInsert(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO ATUALIZAR*/
	public function atualizar(){

		$ban = new Banco();
		$ban->setTabela("tb_cirurgia");

		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("cod = ". $this->getCod());

		/* UPDATE */
		$this->setDateupdate(date("Y-m-d H:i:s"));
		
		
		$this->setAll($ban);
		
		//executando o resultset criado: UPDATE
		$ret = $ban->setResult($ban->getUpdate(), true);
		$ban->fecharConexao();
		return $ret;

	}

	/*IMPLEMENTANDO O MÉTODO EXCLUIR*/
	public function excluir(){
		
		$ban = new Banco();
		$ban->setTabela("tb_cirurgia");

		/* UPDATE */
		$this->setDatedeleted(date("Y-m-d H:i:s"));
		$this->setDeletedby((int)$_SESSION['cod_usuario']);
		
		/* SET ALL PARAMETERS */
		$this->setAll($ban);
		
		//INICIALIZANDO OS CAMPOS DE CONDIÇÃO
		$ban->setCondicao("cod = ". $this->getCod());
		
		
		//executando o resultset criado: DELETE
		//$ret = $ban->setResult($ban->getDelete(), true);
		$ret = $ban->setResult($ban->getUpdate(), true);
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
		$ban->setCondicao("cod = ". $this->getCod(), "AND");

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
					$ban->setCondicaoIn('cod', $params['ids'], 'AND', 'int');
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

	public function getFilaCaixa($json = null){
		
		$ban = new Banco();
		
		$ban->setCondicao("caixa_ok = 'N' and date_deleted is null", "AND");
		$ban->setCondicao("(id_status = ". Status::$FECHADO ." OR id_status = ". Status::$C_D .")", "AND");
		$ban->setOrdem("date_closed");
		
		return $this->getGenericSelect($ban);
		
	
	}
	
	
	public function requerPagamento(){
		
		return $this->getIdstatus() == Status::$FECHADO || $this->getIdstatus() == Status::$C_D;
		
	}
	
	/* ESTE MÉTODO DEVE SER CHAMADO ANTES DE USAR O MÉTODO 'setStatus' */
	public function setClosedByStatus($new_status){
		
		
		$STATUS_CONSIDERADO_FECHADO = (int)$new_status == Status::$FECHADO || (int)$new_status == Status::$C_D || (int)$new_status == Status::$S_D;
		
		if($this->getIdstatus() != (int)$new_status && $STATUS_CONSIDERADO_FECHADO){
			
			$this->setDateClosedNow();
			
		}else{
			
			$this->clearDateClosedNow();
			
		}
		
	}
	//$search_obj->getIdstatus() != (int)$json->idstatus && (int)$json->idstatus == Status::$FECHADO
	
	
	public function getProcesimentosAcontecerProximosDias($dias){
		
		
		$ban = new Banco();
		$ban->setCondicao(" datediff( date(data_realizacao), date(now())) <= ". (int)$dias, "AND");
		$ban->setCondicao("craeted_by = ". (int)$_SESSION['cod_usuario'] ." AND date_deleted IS NULL AND tudo_ok = 'N'", "AND");
		$ban->setCondicao(" (id_status IN (". implode(", ", array(Status::$FECHADO, Status::$S_D, Status::$C_D)) .") AND ID_SERVICO = 1 )");
		/* SERVICO [ 1 = CIRURGIA ]*/
		$ban->setOrdem("data_realizacao", "ASC");
		
		return $this->getGenericSelect($ban);

	}
	
	public function getFila($json = null){
		
		
		
		$ban = new Banco();
		
		/** [CONDICAO 1]: Adicionar ou não o código do usuário */
		
		/** SE FOR ADMINISTRADOR MOSTRA TUDO */
		if( ($_SESSION['grupo'] == "ADMINISTRADOR" ||$_SESSION['grupo'] == "ADMINISTRADOR_MESTRE")
			
			&& isset($json['minha_fila_somente']) && !$json['minha_fila_somente'] 
			
		){
			
			$ban->setCondicao("date_deleted IS NULL", "AND");
			
		/** MOSTRA SÓ A FILA DO USUÁRIO */	
		}else{
			
			$ban->setCondicao("craeted_by = ". (int)$_SESSION['cod_usuario'] ." AND date_deleted IS NULL", "AND");
			
		}
		
		
		$TEM_DATA_FILTRO = !(Util::genFromDateToSQLWhole($_GET["fechado_de"]) == null && Util::genFromDateToSQLWhole($_GET["fechado_ate"]) == null && Util::genFromDateToSQLWhole($_GET["criado_de"]) == null && Util::genFromDateToSQLWhole($_GET["criado_ate"]) == null); 
		
		
		/** [CONDICAO 2]: Mostrar só registros fechados no mesmo dia ou pendentes OU de um status específico do filtro */
		
		/* MOSTRANDO A FILA PADRÃO: OU SEJA DADOS PADRÕES. MOSTRA REGISTROS FECHADOS HOJE */
		if((int)$json["idstatus"] == 0 || (int)$json["idstatus"] == -1){
			
			/* ESTE FILTRO SÓ PODE SER USADO QUANDO NÃO TEM PERÍODO DE DATAS */
			if(
				!($TEM_DATA_FILTRO)
			){
				
				$ban->setCondicao("( (id_status NOT IN(". Status::$FECHADO .", ". Status::$C_D .", ". Status::$S_D .") OR date(date_closed) = date(now()) ) OR (id_status IN(". Status::$FECHADO .", ". Status::$C_D .") AND caixa_ok = 'N' ) )", "AND");
			
			}
			
		/* FILTRA POR UM STATUS ESPECÍFICO*/
		}else{
			
			$ban->setCondicao("id_status = ". $json["idstatus"], "AND");
			
		}
		
		/** [CONDICAO 3]: Tipo de pagamento */
		
		/* FILTRA O TIPO DE PAGAMENTO */
		if((int)$_GET['idtipopagamento'] > 0){
			
			$ban->setCondicao("id_tipo_pagamento = ". (int)$_GET['idtipopagamento'], "AND");
			
		}
		
		/** [CONDICAO 4]: Usando a data de fechamento para fazer filtro */
		
		
		
		/* FILTRA POR DATA DE FECHAMENTO */
		//if(Util::genFromDateToSQLWhole($_GET["fechado_de"]) != null || Util::genFromDateToSQLWhole($_GET["fechado_ate"]) != null){
		if($TEM_DATA_FILTRO){
			
			if(Util::genFromDateToSQLWhole($_GET["fechado_de"]))
				$ban->setCondicao("(date_closed IS NOT NULL AND DATE(date_closed) >= DATE('". Util::genFromDateToSQLWhole($_GET["fechado_de"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["fechado_ate"]))
				$ban->setCondicao("(date_closed IS NOT NULL AND DATE(date_closed) <= DATE('". Util::genFromDateToSQLWhole($_GET["fechado_ate"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["criado_de"]))
				$ban->setCondicao("(date_created IS NOT NULL AND DATE(date_created) >= DATE('". Util::genFromDateToSQLWhole($_GET["criado_de"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["criado_ate"]))
				$ban->setCondicao("(date_created IS NOT NULL AND DATE(date_created) <= DATE('". Util::genFromDateToSQLWhole($_GET["criado_ate"]) ."') )", "AND");
			
		}
		
		
		
		$ban->setOrdem("data_realizacao");
		return $this->getGenericSelect($ban);
		
	}
	
	public function getOrcamentosPorCliente(){
		
		$ban = new Banco();
		$ban->setCondicao("id_cliente = ". $this->getIdcliente());
		
		return $this->getGenericSelect($ban);
	}

	/*IMPLEMENTANDO O MÉTODO GENERICO DE SELECT*/
	public function getGenericSelect($ban, $json = null){

		$ban->setTabela("tb_cirurgia");
		$ban->setCampo("cod");
		$ban->setCampo("data");
		$ban->setCampo("id_cliente");
		$ban->setCampo("valor_operacao");
		$ban->setCampo("id_tipo_pagamento");
		$ban->setCampo("id_servico");
		$ban->setCampo("id_procedimento");
		$ban->setCampo("data_realizacao");
		$ban->setCampo("id_medico");
		$ban->setCampo("id_lateralidade");
		$ban->setCampo("id_opme");
		$ban->setCampo("id_status");
		$ban->setCampo("date_created");
		$ban->setCampo("date_update");
		$ban->setCampo("date_deleted");
		$ban->setCampo("craeted_by");
		$ban->setCampo("updated_by");
		$ban->setCampo("deleted_by");
		$ban->setCampo("date_closed");
		$ban->setCampo("obs");
		$ban->setCampo("caixa_ok");
		$ban->setCampo("tudo_ok");
		$ban->setCampo("tudo_ok_por");
		$ban->setCampo("tudo_ok_datetime");

		$arr_ret_todos = array();
		
		//echo($ban->getSelect());
		//echo("\n");
		
		//executando o resultset criado: SELECT
		$ban->setCondicao("date_deleted is null", "AND");
		
		$arr_all_objects = $ban->getResultSelect();

		foreach($arr_all_objects as $lista){

			if($json == null || $json === false){
				$um_obj = new Orcamento();
				$um_obj->setCod($lista['cod']);
				$um_obj->setData($lista['data']);
				$um_obj->setIdcliente((int)$lista['id_cliente']);
				$um_obj->setValoroperacao((float)$lista['valor_operacao']);
				$um_obj->setIdtipopagamento((int)$lista['id_tipo_pagamento']);
				$um_obj->setIdservico((int)$lista['id_servico']);
				$um_obj->setIdprocedimento((int)$lista['id_procedimento']);
				$um_obj->setDatarealizacao($lista['data_realizacao']);
				$um_obj->setIdmedico((int)$lista['id_medico']);
				$um_obj->setIdlateralidade((int)$lista['id_lateralidade']);
				$um_obj->setIdopme((int)$lista['id_opme']);
				$um_obj->setIdstatus((int)$lista['id_status']);
				$um_obj->setDatecreated($lista['date_created']);
				$um_obj->setDateupdate($lista['date_update']);
				$um_obj->setDatedeleted($lista['date_deleted']);
				$um_obj->setCraetedby((int)$lista['craeted_by']);
				$um_obj->setUpdatedby((int)$lista['updated_by']);
				$um_obj->setDeletedby((int)$lista['deleted_by']);
				$um_obj->setObs($lista['obs']);
				$um_obj->setDateclosed($lista['date_closed']);
				$um_obj->setCaixaok($lista['caixa_ok']);
				$um_obj->setCaixaokby($lista['caixa_ok_by']);
				$um_obj->setCaixaokdatetime($lista['caixa_ok_datetime']);
				$um_obj->setTudook($lista['tudo_ok']);
				$um_obj->setTudookpor($lista['tudo_ok_por']);
				$um_obj->setTudookdatetime($lista['tudo_ok_datetime']);

				$arr_ret_todos[] = $um_obj;
				$um_obj = null;

			}else{
				$arr_ret_todos[] = $lista;
			}
		}
		$ban->fecharConexao();

		return $arr_ret_todos;
	}
	
	public function setDateClosedNow(){
		
		$this->setDateclosed(date("Y-m-d H:i:s"));
		
	}
	
	public function clearDateClosedNow(){
		
		$this->setDateclosed(null);
		
	}

	public function apply($opt){
		if($opt == "insert" or $opt == "i") return $this->inserir();
		if($opt == "update" or $opt == "u") return $this->atualizar();
		if($opt == "delete" or $opt == "d") return $this->excluir();

	}

	/* IMPLEMENTANDO O MÉTODO DE CONTAGEM DE DADOS */
	public function totalRecords(){

		$ban = new Banco();
		$ban->setTabela("tb_cirurgia");
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
	
	public function isCaixaOK(){
		
		return ($this->getCaixaok() == "Y");
		
	}

	public function setAll($ban){
	/*IMPLEMENTANDO O MÉTODO SET DOS CAMPOS DESTA CLASSE*/
		
		
		if($this->getIdstatus() == Status::$FECHADO || $this->getIdstatus() == Status::$C_D){
			
			if($this->getDateclosed() == null)
				$this->setDateClosedNow();
			
		}
		
		
		
		
		if($this->getData() != "" && $this->getData() != null)
			$ban->setCampo("data", $this->getData());
		if($this->getData() == null) $ban->setCampo("data", "NULL", "int");
		$ban->setCampo("id_cliente", $this->getIdcliente());
		$ban->setCampo("valor_operacao", $this->getValoroperacao());
		$ban->setCampo("id_tipo_pagamento", $this->getIdtipopagamento());
		$ban->setCampo("id_servico", $this->getIdservico());
		$ban->setCampo("id_procedimento", $this->getIdprocedimento());
		
		if($this->getDatarealizacao() != "" && $this->getDatarealizacao() != null)
			$ban->setCampo("data_realizacao", $this->getDatarealizacao());
		if($this->getDatarealizacao() == null) $ban->setCampo("data_realizacao", "NULL", "int");
		$ban->setCampo("id_medico", $this->getIdmedico());
		$ban->setCampo("id_lateralidade", $this->getIdlateralidade());
		$ban->setCampo("id_opme", $this->getIdopme());
		$ban->setCampo("id_status", $this->getIdstatus());
		
		//if($this->getDatecreated() != "" && $this->getDatecreated() != null)
		//	$ban->setCampo("date_created", $this->getDatecreated());
		//if($this->getDatecreated() == null) $ban->setCampo("date_created", "NULL", "int");
		
		if($this->getDateupdate() != "" && $this->getDateupdate() != null)
			$ban->setCampo("date_update", $this->getDateupdate());
		if($this->getDateupdate() == null) $ban->setCampo("date_update", "NULL", "int");
		
		if($this->getDatedeleted() != "" && $this->getDatedeleted() != null)
			$ban->setCampo("date_deleted", $this->getDatedeleted());
		if($this->getDatedeleted() == null) $ban->setCampo("date_deleted", "NULL", "int");
		$ban->setCampo("craeted_by", $this->getCraetedby());
		$ban->setCampo("updated_by", $this->getUpdatedby());
		$ban->setCampo("deleted_by", $this->getDeletedby());
		$ban->setCampo("obs", $this->getObs());
		
		if($this->getDateclosed() != "" && $this->getDateclosed() != null)
			$ban->setCampo("date_closed", $this->getDateclosed());
		if($this->getDateclosed() == null) $ban->setCampo("date_closed", "NULL", "int");
		
		/* CAIXA 'OK' */
		$ban->setCampo("caixa_ok", $this->getCaixaok());
		$ban->setCampo("caixa_ok_by", (int)$this->getCaixaokby());
		
		//if($this->getCaixaokdatetime() != "" && $this->getCaixaokdatetime() != null)
		//	$ban->setCampo("caixa_ok_datetime", $this->getCaixaokdatetime());
		//
		//if($this->getCaixaokdatetime() == null) $ban->setCampo("caixa_ok_datetime", "NULL", "int");
		
		if($this->getCaixaok() == "Y") $ban->setCampo("caixa_ok_datetime", "NOW()", "int");
		if($this->getCaixaok() == "N") $ban->setCampo("caixa_ok_datetime", "NULL", "int");
		
		
		/* SUMIR DA FILA */
		$ban->setCampo("tudo_ok", $this->getTudook());
		$ban->setCampo("tudo_ok_por", (int)$this->getTudookpor());
		
		
		if($this->getTudook() == "Y") $ban->setCampo("tudo_ok_datetime", "NOW()", "int");
		if($this->getTudook() == "N") $ban->setCampo("tudo_ok_datetime", "NULL", "int");
		
		//if($this->getTudookdatetime() != "" && $this->getTudookdatetime() != null)
		//	$ban->setCampo("tudo_ok_datetime", $this->getTudookdatetime());
		//if($this->getTudookdatetime() == null) $ban->setCampo("tudo_ok_datetime", "NULL", "int");
		
	}
	
	
	
	
	
	public function getRelatorioFiltro($json = null){
		
		
		$ban = new Banco();
		
			$ban->setCondicao("date_deleted IS NULL", "AND");

		
		
		$TEM_DATA_FILTRO = !(Util::genFromDateToSQLWhole($_GET["fechado_de"]) == null && Util::genFromDateToSQLWhole($_GET["fechado_ate"]) == null && Util::genFromDateToSQLWhole($_GET["criado_de"]) == null && Util::genFromDateToSQLWhole($_GET["criado_ate"]) == null); 
		
		
		/* MOSTRANDO A FILA PADRÃO: OU SEJA DADOS PADRÕES. MOSTRA REGISTROS FECHADOS HOJE */
		if((int)$_GET["idstatus"] > 0){
			
			$ban->setCondicao("id_status = ". ((int)$_GET["idstatus"]), "AND");
			
		}
		
		/* FILTRA O TIPO DE PAGAMENTO */
		if((int)$_GET['idtipopagamento'] > 0){
			
			$ban->setCondicao("id_tipo_pagamento = ". (int)$_GET['idtipopagamento'], "AND");
			
		}
		
		
		if((int)$_GET['idpendente_caixa'] > 0){
			
			 $status = implode(", ", array(Status::$FECHADO, Status::$C_D));
			 
			$ban->setCondicao(" (caixa_ok = 'N' AND id_status IN(". $status .") )", "AND");
		
		}
		/** [CONDICAO 4]: Usando a data de fechamento para fazer filtro */
		
		if((int)$_GET['idusuario'] > 0){
			
			$ban->setCondicao(" (craeted_by = ". (int)$_GET['idusuario'] ." )", "AND");
			
			
		}
		if((int)$_GET['idmedico'] > 0){
			
			$ban->setCondicao(" (id_medico = ". (int)$_GET['idmedico'] ." )", "AND");
			
			
		}
		
		/* FILTRA POR DATA DE FECHAMENTO */
		//if(Util::genFromDateToSQLWhole($_GET["fechado_de"]) != null || Util::genFromDateToSQLWhole($_GET["fechado_ate"]) != null){
		if($TEM_DATA_FILTRO){
			
			if(Util::genFromDateToSQLWhole($_GET["fechado_de"]) != null)
				$ban->setCondicao("(date_closed IS NOT NULL AND DATE(date_closed) >= DATE('". Util::genFromDateToSQLWhole($_GET["fechado_de"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["fechado_ate"]) != null)
				$ban->setCondicao("(date_closed IS NOT NULL AND DATE(date_closed) <= DATE('". Util::genFromDateToSQLWhole($_GET["fechado_ate"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["criado_de"]) != null)
				$ban->setCondicao("(date_created IS NOT NULL AND DATE(date_created) >= DATE('". Util::genFromDateToSQLWhole($_GET["criado_de"]) ."') )", "AND");
			
			if(Util::genFromDateToSQLWhole($_GET["criado_ate"]) != null)
				$ban->setCondicao("(date_created IS NOT NULL AND DATE(date_created) <= DATE('". Util::genFromDateToSQLWhole($_GET["criado_ate"]) ."') )", "AND");
			
		}
		
		
		$ban->setOrdem("data_realizacao");
		return $this->getGenericSelect($ban);
		
	}
	
	public function jaExisteCadastrado(){
		
		$idcliente = (int)$this->getIdcliente();
		$idservico = (int)$this->getIdservico();
		$idprocedimento = (int)$this->getIdprocedimento();
		$idlateralidade = (int)$this->getIdlateralidade();
		
		$ban = new Banco();
		
		$id_status = " id_status NOT IN (". Status::$FECHADO .", ". Status::$C_D .") ";
		
		$ban->setCondicao("(id_cliente = ". $idcliente ." AND id_servico = ". $idservico ." AND id_procedimento = ". $idprocedimento ." AND id_lateralidade = ". $idlateralidade ." and date_deleted IS NULL and ". $id_status .")", "AND");
		
		return $this->getGenericSelect($ban);
		
	}

}


?>