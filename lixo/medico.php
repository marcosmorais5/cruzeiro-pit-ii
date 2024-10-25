<?php

ini_set("display_error", "on");

//$conn = mysqli_connect("127.0.0.1", "root", "@tsystems.com", "db_medicoolhos");
//
//if (!$link) {
//    echo "Error: Unable to connect to MySQL." . PHP_EOL;
//    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
//    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
//    exit;
//}else{
//	
//	echo("connected...");
//	
//	
//}
//
//mysqli_query($conn, "select * from tb_medico");



require_once("Banco.php");


$arr_opme = "AHAMED;CLAREON;COLA;CRAWFORD;EYLIA;IQ;IQ TÓRICA;LUCENTIS;MA60AC;MA60MA;MIGS;MONOKA;NACIONAL;OLOGEM;ORZUDEX;PANOPTIX;PANOPTIX TÓRICA;RAYONE;RAYONE ASF;RAYONE TRI;RESTOR;SA60AT;SENSAR;SMFONY;SMFONY TÓRICA;SUZANA;TCN;TECNISS;TECNISS MT;TECNISS MULTI;TECNISS TÓRICA;TRÉPANO;TRÉPANO A VÁCUO;RESTOR TÓRICA;AVASTIN;VISCOAT;RADIOFREQUÊNCIA";
$arr_lateralidade = "AO;INFERIOR;OD;OE;SUPERIOR";
$arr_medico = "Aécio Yoshikazu Oshiro;Alexandre Achille Grandinetti;Alexandre Lass Siqueira;Alisson Cirino Bedin;Ana Carolina Cordeiro de Andrade Spelier;Ana Carolina Romanini Trautwein;Ana Claudia Munemori Mariushi;Ana Maria Gori Gomes;Ana Paula Vatanabe Shinmi;Artur José Schmitt;Bruno Bandolin Barbosa;Caio Solheid Meister;Carla Schuchovski Ribeiro;Carlos Henrique Amaral Teixeira;Cristina de Camargo Cury;Cristina Gerhardt;Danilo Araujo Micheletto;Dayane Cristine Issaho;Deborah Fischer;Diogo Boschini Rodrigues;Diogo Sunaga;Edson Messias Sobreiro;Eduardo Arana;Eliandra Machado da Silva;Elmar Zeve Junior;Fabio Koji  Naka;Felipe Augusto Morais;Felipe Moraes Erthal Tardin;Felipe Roberto Exterhotter Branco;Felipe Roberto Extrhotter Branco;Felipe Shuster Battaglin;Fernanda de Santana Pissetti;Fernando Costa de Oliveira;Germano Leal Ehlke;Guilherme Gubert Muller;Guilherme Jose Marques Rocha;Guilherme Vinicius Pagnoncelli;Gustavo Telli da Silva;Hamilton Moreira;Hilton Vargas Amaral Filho;Isabella Cristina Simas de Aguiar;Jayme Arana;Jeferson Adriano Druszcz;Jhony de Polo;João Fernandes Alves;João Paulo Wengrzynovski;José Roberto Depicolli Junior;Jussara Moraes Foggiato;Leonardo Volpini;Lucas Shiokawa;Luis Augusto Arana;Luiz Eduardo Osowski;Maiara Radigonda;Maitê Daniele Bazetti Basso;Marcelo Alves Vilar de Siqueira;Marcelo de Oliveira Mendes;Marcio Zapparoli;Marco Túlio Nunes Cordeiro;Michelle Stoppa Castro Gomes;Mirella Almeida de Oliveira;Muna Georges Nasr;Murilo Dallarmi Carneiro;Natasha Danilow Fachin;Nathalia de Almeida Raupp;Nayra Funato Menezes;Osny Sedano Filho;Otavio Siqueira Bisneto;Pedro Cesar Blum Filho;Rachel Lopes Franke Bezerra;Rafael de Matos;Rafaela Modelli;Raisa Vitória Rubert Aoki;Raphael Calixto Penatti;Renan Magalhães Cascardo;Renan Pedro de Almeida Torres;Renata Kozlowski Bekin;Ricardo Faraco Martinez Cebrian;Roberta Cristine Suetugo;Robson Antonio de Almeida Torres;Rochelli da Costa Scalco;Rodrigo Beraldi Kormann;Rodrigo Caires de Souza;Rodrigo Massaroli da Silva;Rodrigo Takeshi Omoto;Ronaldo Brandão de Proença Bettega;Sabrina Leticia Zeglin Nicolau Cavalli;Saulo Marcel Diaz Henriquez;Sérgio Luiz de Lara Junior;Tatiana Herbas Arguellez;Tiago Fagundes Chichetti;Virginia Soranco Buzelin;Vito Angelo Duarte Pascaretta;Wagner Kamikawa;Felipe Ishida";

$arr_procedimento = "AGULHAMENTO;ALL SCAN – BINO;ANEL;ANGIOGRAFIA – AVANTI – OCTA;ANGIOGRAFIA – TRITON – OCTA;ANGIOGRAFIA COM CONTRASTE;AUTO REFRAÇÃO – BINO;AVALIAÇÃO VIAS LACRIMAIS;BAT – BINO;BIOMETRIA – IMERSÃO – BINO;BIOMICROSCOPIA ULTR. (UBM);BLEFARO;CAMPO VISUAL – (COMPASS);CHECK-UP;CHECK-UP + BIO;CIRURGIA FISTULIZANTE;CRIO;CROSSLINKING;DACRIOCISTORRINOSTOMIA;DESCOMPRESSÃO DA ORBITA;DIOPSYS (PONT. EVOCADO);ECOGRAFIA;ECTROPIO / ENTROPIO;ENUCLEAÇÃO OU EVISCERAÇÃO SEM / COM IMPLANTE;ENXERTO DE ESCLERA;ESTEREOFOTO DE PAPILA;ESTRABISMO;FACO + LIO;FOTO A LASER- INTEGRE- 1º aplicação;FOTO A LASER- INTEGRE- 2º aplicação;FOTOCOAGULAÇÃO A LASER – argonio;GALILEI G4 – BINO;GALILEI G4 + ALL SCAN OU LENSTAR;GALILEI G6 – BINO;GONIOSCOPIA;IMPLANTE SECUNDARIO DE LIO;IMPLANTE VALVULAR;INFILTRAÇÃO CONJUTIVAL;INJEÇÃO;IRIDECTOMIA CIRURGICA;LAGOFTALMO;LASIK;LENSTAR – BINO;LENSX + LIO;LUZ PULSADA;MANUTENÇÃO ANUAL LUZ PULSADA;MAPEAMENTO DE RETINA;MICROSCOPIA ESPECULAR- BINO;OCT DE CÓRNEA – BINO;OCT DE GLAUCOMA – GDX – nervo optico;OCT DE RETINA/ MACULA;OPDSCAN ABERROMETRO - – BINO;PAQUIMETRIA – BINO;PARACENTESE DE CAMERA ANTERIOR;PCT- LUZ PULSADA/TEAR LAB/SCHIRMER/PINÇA DE EXPRESSÃO;PINÇA DE EXPRESSÃO – MEIBOMIUS;PLUG DE SILICONE;PONTECIAL MACULAR(SPH) - – BINO;PRK;PTERIGIO;PTOSE;RASPAGEM DA CORNEA;RECOBRIMENTO CONJUNTIVAL;RECONSTITUIÇÃO DE FORNIX CONJUNTIVAL;RECONSTITUIÇÃO DE GLOBO OCULAR;RECONSTITUIÇÃO DE VIAS LACRIMAIS;RECONSTRUÇÃO DE CAMARA ANTERIOR;RECONSTRUÇÃO DE CAVIDADE ORBITÁRIA;RECONSTRUÇÃO DE PALPEBRA;RECONSTRUÇÃO TOTAL DE PALPEBRA;REPOSICIONAMENTO DE LIO;RESSECÇÃO LAMELAR;RETINOGRAFIA- EIDON;RETINOGRAFIA- EIDON- alta -AUTOFLORESCENCIA;RETIRADA DE CORPO ESTRANHO;RETIRADA DE LIO;RETIRADA DE OLEO;RETIRADA DE PONTOS;RETIRADA DE TUBOS;RETRACAO PALPEBRAL;SIMBLEFARO;SLT-TRABECULOPLASTIA BINO;SLT-TRABECULOPLASTIA MONO;SONDAGEM DE VIAS LACRIMAIS;SUTURA DE CONJUNTIVA;SUTURA DE CORNEA;SUTURA DE ESCLERA;SUTURA DE PALPEBRA;SUTURA OU RECONSTITUIÇÃO DOS CANALICULOS;TARSORRAFIA;TEAR LAB-TESTE DE OSMOLARIDADE;TESTE DE SCHIRMER;TESTE DE SOBRECARGA HIDRICA – TSH;TONOMETRIA DE GOLDMANN;TOPOGRAFIA – BINO;TOPOLYZER – BINO;TOPOPLASTIA;TRABECULECTOMIA;TRANSPLANTE CONJUNTIVAL;TRANSPLANTE DE CORNEA;TRANSPLANTE DE ESCLERA;TRIQUIASE COM DIATERMO COAGULAÇÃO;TUMOR DE CONJUNTIVA / SHAVE;TUMOR DE ORBITA - EXERESE;TUMOR DE PALPEBRA / SHAVE;VERION;VISITA HOSPITALAR;VITRECTOMIA ANTERIOR;VPP;VPP + FACO;XANTELASMA;YAG – bino;YAG – mono;ESTRABISMO + FORNICE;CALAZIO";


$tabela_opme = "INSERT INTO tb_opme (opme) ";
$tabela_lateralidade = "INSERT INTO tb_lateralidade (lateralidade) ";
$tabela_medico = "INSERT INTO tb_medico (nome_medico) ";
$tabela_procedimento = "INSERT INTO tb_procedimento (procedimento) ";

$arr_opme = explode(";", $arr_opme);
$arr_lateralidade = explode(";", $arr_lateralidade);
$arr_medico = explode(";", $arr_medico);
$arr_procedimento = explode(";", $arr_procedimento);

$ban = new Banco();

foreach($arr_opme as $temp){
	
	$ban->setSql($tabela_opme ." VALUES('". $temp ."')");
	$ban->applyResult("insert");
	
}

foreach($arr_lateralidade as $temp){
	
	$ban->setSql($tabela_lateralidade ." VALUES('". $temp ."')");
	$ban->applyResult("insert");
	
}
foreach($arr_medico as $temp){
	
	$ban->setSql($tabela_medico ." VALUES('". $temp ."')");
	$ban->applyResult("insert");
	
}
foreach($arr_procedimento as $temp){
	
	$ban->setSql($tabela_medico ." VALUES('". $temp ."')");
	$ban->applyResult("insert");
	
}






