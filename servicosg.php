<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdserv = $_POST["cdserv"];
	$deserv = $_POST["deserv"];
	$vlserv = $_POST["vlserv"];
	$qtserv = $_POST["qtserv"];
	$dtcada = $datahoje;
	$flativ = substr($_POST ["flativ"],0,1);

	$vlserv = str_replace(".","",$vlserv);
	$vlserv = str_replace(",",".",$vlserv);

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

	$aserv=ConsultarDados("servicos", "cdserv", $cdserv, false, false, $cdclie);

	if (count($aserv) > 0 ){
		$demens = "Código do produto já cadastrado!";
		$detitu = "Weber Hotel&copy; | Inclusão de Serviços";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdclie";
			$aNomes[]= "cdserv";
			$aNomes[]= "deserv";
			$aNomes[]= "vlserv";
			$aNomes[]= "qtserv";
			$aNomes[]= "dtcada";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $cdclie;
			$aDados[]= $cdserv;
			$aDados[]= $deserv;
			$aDados[]= $vlserv;
			$aDados[]= $qtserv;
			$aDados[]= $dtcada;
			$aDados[]= $flativ;

			GravarDados("servicos",$aDados,$aNomes,"I","cdserv",$cdserv);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Serviços:".$cdserv.":".$cdclie;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão de Serviços";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "servicos.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>