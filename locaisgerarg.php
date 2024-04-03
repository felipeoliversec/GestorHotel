<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$deini = $_POST["deini"];
	$defim = $_POST["defim"];

	//$vlloca = str_replace(".","",$vlloca);
	//$vlloca = str_replace(",",".",$vlloca);

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

	$aloca=ConsultarLocal ($cdclie, $deini, $defim);

	if (count($aloca) > 0 ){
		$demens = "Já existem acomadações cadastradas nessa faixa. Tente outra faixa!";
		$detitu = "Weber Hotel&copy; | Inclusão de Acomodações";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdclie";
			$aNomes[]= "cdloca";
			$aNomes[]= "deloca";
			$aNomes[]= "nranda";
			$aNomes[]= "nrrama";
			$aNomes[]= "qthosp";
			$aNomes[]= "cdtipo";
			$aNomes[]= "deobse";
			$aNomes[]= "fllibe";


			// Criando acomodações automaticamente
			for ($x = $deini; $x <= $defim; $x++) {

				// armazena em array
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= str_pad($x,3,"0",STR_PAD_LEFT);
				//$aDados[]= "Acomodação ".str_pad($x,3,"0",STR_PAD_LEFT);
				$aDados[]= "Acomodação";
				$aDados[]= "1";
				$aDados[]= "9";
				$aDados[]= "2";
				$aDados[]= "C";
				$aDados[]= "Acomodação Padrão";
				$aDados[]= "S";

				GravarDados("locais",$aDados,$aNomes,"I","cdclie",$cdclie);
			}

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Locais:".$cdclie;
			$aDados[]="Inclusão Auto";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro gerado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão Automática de Locais";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "locais.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>