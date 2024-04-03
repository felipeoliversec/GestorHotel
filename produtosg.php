<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdprod = $_POST["cdprod"];
	$deprod = $_POST["deprod"];
	$vlprod = $_POST["vlprod"];
	$qtprod = $_POST["qtprod"];
	$dtcada = $datahoje;
	$flativ = substr($_POST ["flativ"],0,1);

	$vlprod = str_replace(".","",$vlprod);
	$vlprod = str_replace(",",".",$vlprod);

    //codigo
    $cdusua="00000000000";
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    //codigo do cliente
	$cdclie="00000000000000";
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    //tipo de usuario
    if (isset($_COOKIE['fladmi'])) {
        $fladmi = $_COOKIE['fladmi'];
    }

	$Flag=true;

	$aprod=ConsultarDados("produtos", "cdprod", $cdprod, false, false, $cdclie);

	if (count($aprod) > 0 ){
		$demens = "Código do produto já cadastrado!";
		$detitu = "Weber Hotel&copy; | Inclusão de Produtos";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdclie";
			$aNomes[]= "cdprod";
			$aNomes[]= "deprod";
			$aNomes[]= "vlprod";
			$aNomes[]= "qtprod";
			$aNomes[]= "dtcada";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $cdclie;
			$aDados[]= $cdprod;
			$aDados[]= $deprod;
			$aDados[]= $vlprod;
			$aDados[]= $qtprod;
			$aDados[]= $dtcada;
			$aDados[]= $flativ;

			GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Produtos:".$cdprod.":".$cdclie;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão de Produtos";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "produtos.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>