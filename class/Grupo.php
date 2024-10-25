<?php
	
class Grupo{
	
	
	
	public $grupos = array(
		"USUARIO",
		"CAIXA",
		"ADMINISTRADOR",
		"ADMINISTRADOR_MESTRE"
		);
	
	public static $USUARIO = "USUARIO";
	public static $CAIXA = "CAIXA";
	public static $ADMINISTRADOR = "ADMINISTRADOR";
	public static $ADMINISTRADOR_MESTRE = "ADMINISTRADOR_MESTRE";
	
	public static $GROUP_USUARIO = array("idgrupo" => "USUARIO", "grupo" => "Usuário");
	public static $GROUP_CAIXA = array("idgrupo" => "CAIXA", "grupo" => "Caixa");
	public static $GROUP_ADMINISTRADOR = array("idgrupo" => "ADMINISTRADOR", "grupo" => "Administrador");
	public static $GROUP_ADMINISTRADOR_MESTRE = array("idgrupo" => "ADMINISTRADOR_MESTRE", "grupo" => "Administrador (mestre)");
	
	//public static $ALL_GROUPS_HASH = "";
	public static $ALL_GROUPS_HASH = array("USUARIO" => "Usuário", "CAIXA" => "Caixa", "ADMINISTRADOR" => "Administrador", "ADMINISTRADOR_MESTRE" => "Administrador (mestre)");
	
	
}
	
?>