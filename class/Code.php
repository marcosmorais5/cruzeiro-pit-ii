<?php
/* CLASSE PARA CODIFICAR IDS
* 
* -- REGRA DE CRIPTOGRAFIA
* -
* - A regra para codificação é, converter 2 vezes o dado
* - de entrada base64_encode, e depois pegar o dado de
* - saída e fazer a ordem reversa. por exemplo a palavra
* - "ABCDE" fica "EDCBA"
* ------------------------------
* 
* -- REGRA DE DECRIPTOGRAFIA
* -
* - A regra para docodificação é primeiro fazer a inversão
* - da string para o valor original, exemplo: "EDCBA" fica
* - "ABCDE".
* - Depois usase a funçaõ base64_decode 2 vezes para fazer
* - a decodificação do dado convertiso.
* ------------------------------
* 
* */
class Code{

	/* Método para codificar uma string
	* */
	public function codificar($str){
		
		//$str = base64_encode(base64_encode(base64_encode($str)));
		$str = base64_encode(base64_encode($str));
		$ret = Code::reverse($str);
		return $ret;
	}

	/* Função para escrever uma string de maneira reversa,
	* por exemplo "ABC" se torna "CBA"
	* */
	public function reverse($val){
		
		$ret = "";
		for($i = strlen($val) - 1; $i >= 0; $i--){
			$ret .= substr($val, $i, 1);
		}
		return $ret;
		
	}
	
	/* Método para descodificar uma string previamente
	* codificada pela mesma lógica
	* */
	public function decodificar($str){

		$ret = Code::reverse($str);
		//return base64_decode(base64_decode(base64_decode($ret)));
		return base64_decode(base64_decode($ret));
		
	}
	
	/* Método curto para descodificar uma string
	* */
	public function de($str){
		return Code::decodificar($str);
	}
	
	/* Método para adicionar ou SUBSTITUIR um parâmetro
	* existente via GET na variável "u". A variável GET
	* de nome "u" contém uma string codificada para evitar
	* intervenção do usuário no processamento das páginas
	* */
	/*
	public function juntarVar($var, $valor, $u = null, $codificar = true){
		
		
		$u = ($u == null)? Code::decodificar($_GET['u']) : Code::decodificar($u);
		$u = @split("\&", $u);
		
		$url_ret = "";
		$exist = false;
		
		for($i = 0; $i < sizeof($u); $i++){
			
			
			$curr_s = $u[$i];
			$curr = @split("=", $curr_s);
			
			if(sizeof($curr) >= 2){
				
				$tmp = "";
				if($curr[0] == $var){
					$tmp = $var ."=". $valor;
					
					if(!$exist)
						$exist = true;
				}else{
					$tmp = $curr[0] ."=". $curr[1];
				}
				$url_ret .= $tmp ."&";
				
			}
			
		}
		
		
		if(!$exist){
			if(strlen($var) > 0){
				$url_ret .= $var ."=". $valor;
			}
		}
		
		
		
		if($codificar){
			$url_ret = Code::codificar($url_ret);
		}
		
		
		return $url_ret;
		
	}
	*/
	
	public function juntarVar($var, $valor, $u = null, $codificar = true){
		
		
		
		$u = ($u == null)? Code::decodificar($_GET['u']) : Code::decodificar($u);
		
		
		$u = explode("&", $u);
		
		$exist = false;
		$url_ret = array();;
		
		for($i = 0; $i < sizeof($u); $i++){
			
			$curr_s = $u[$i];
			$curr = explode("=", $curr_s);
		
			if(sizeof($curr) >= 2){
				
				if($var == $curr[0]){
					
					$tmp = $var ."=". $valor;
					
					if(!$exist){
						$exist = true;
					}
				}else{
					$tmp = $curr[0] ."=". $curr[1];
				}
				//$url_ret .= $tmp ."&";
				$url_ret[] = $tmp;
				
				
			}
			
		}
		
		if(!$exist){
			//$url_ret = $url_ret ."&". $var ."=". $valor;
			$url_ret[] = $var ."=". $valor;
		}
		$url_ret = implode("&", $url_ret);
		
		if($codificar) $url_ret = Code::codificar($url_ret);
		return $url_ret;
		
	}
	/* Método para capturar o valor de uma variável
	* codificada dentro da variável GET de nome "u"
	* */
	public function getVariavelDecode($var, $val = null){
		
		if($val == null){
			if(isset($_GET['url']) || isset($_GET['u'])){
				
				if(isset($_GET['url'])){
					$val = $_GET['url'];
					
				}else if(isset($_GET['u'])){
					$val = $_GET['u'];
				}
			}else{
				return "";
			}
		}
		
		$val = Code::decodificar($val);
		$val = @explode("&", $val);
		
		
		
		for($i = 0; $i < sizeof($val); $i++){
			$arr_tmp = @explode("=", $val[$i]);
			
			if($arr_tmp[0] == $var){
				if(sizeof($arr_tmp) >= 2){
					return $arr_tmp[1];
				}
			}
		}
		
	}
	
	/* Método curto para capturar uma variável
	* */
	public function getVar($var, $val = null){
		return Code::getVariavelDecode($var, $val);
	}
	
	public function temVars($arr_vars){
		
		for($i = 0; $i < count($arr_vars); $i++){
			$curr = Code::temVar($arr_vars[$i]);
			if($curr) return true;
		}
		
		return false;
		
	}
	
	public function temVar($var){
		if(Code::getVar($var) != ""){
			return true;
		}
	}

}

class C extends Code{
	
}
?>