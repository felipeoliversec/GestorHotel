<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdloca = $_POST["cdloca"];
	$deloca = $_POST["deloca"];
	$nranda = $_POST["nranda"];
	$nrrama = $_POST["nrrama"];
	$qthosp = $_POST["qthosp"];
	$cdtipo = substr($_POST ["cdtipo"],0,1);
	$deobse = $_POST["deobse"];

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

	$aloca=ConsultarDados("locais", "cdloca", $cdloca, false, false, $cdclie);

	if (count($aloca) > 0 ){
		$demens = "Código do Local já cadastrado!";
		$detitu = "Weber Hotel&copy; | Inclusão de locaiços";
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

			// armazena em array
			$aDados=array();
			$aDados[]= $cdclie;
			$aDados[]= $cdloca;
			$aDados[]= $deloca;
			$aDados[]= $nranda;
			$aDados[]= $nrrama;
			$aDados[]= $qthosp;
			$aDados[]= $cdtipo;
			$aDados[]= $deobse;
			$aDados[]= "S";

			GravarDados("locais",$aDados,$aNomes,"I","cdloca",$cdloca);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Locais:".$cdloca.":".$cdclie;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão de Locais";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "locais.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>