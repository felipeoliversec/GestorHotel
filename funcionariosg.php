<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdfunc = $_POST["cdfunc"];
	$defunc = $_POST["defunc"];
	$nrtele = $_POST["nrtele"];
	$nrregi = $_POST["nrregi"];
	$dtnasc = $_POST["dtnasc"];
	$dtadmi = $_POST["dtadmi"];
	$dtdemi = $_POST["dtdemi"];
	$deobse = $_POST["deobse"];
	$dtcada = $datahoje;

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

	$aFunc=ConsultarDados("funcionarios", "cdfunc", $cdfunc, false, false, $cdclie);

	if (count($aFunc) > 0 ){
		$demens = "Código do funcionário já cadastrado!";
		$detitu = "Weber Hotel&copy; | Inclusão de Funcionários";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdclie";
			$aNomes[]= "cdfunc";
			$aNomes[]= "defunc";
			$aNomes[]= "nrtele";
			$aNomes[]= "nrregi";
			$aNomes[]= "dtnasc";
			$aNomes[]= "dtadmi";
			$aNomes[]= "dtdemi";
			$aNomes[]= "deobse";
			$aNomes[]= "dtcada";

			// armazena em array
			$aDados=array();
			$aDados[]= $cdclie;
			$aDados[]= $cdfunc;
			$aDados[]= $defunc;
			$aDados[]= $nrtele;
			$aDados[]= $nrregi;
			$aDados[]= $dtnasc;
			$aDados[]= $dtadmi;
			$aDados[]= $dtdemi;
			$aDados[]= $deobse;
			$aDados[]= $dtcada;

			GravarDados("funcionarios",$aDados,$aNomes,"I","cdfunc",$cdfunc);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Funcionários:".$cdfunc;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão de Funcionários";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "funcionarios.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>