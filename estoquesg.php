<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdprod = trim(substr($_POST ["cdprod"], 0, strpos($_POST ["cdprod"], '-')));
	$qtprod = $_POST["qtprod"];
	$cdtipo = strtoupper($_POST["cdtipo"]);

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

	$Flag=true;

	if ($qtprod <= 0 ){
		$demens = "Quantidade deve ser maior que zero!";
		$detitu = "Weber Hotel&copy; | Estoques";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($cdtipo <> "E" and $cdtipo <> "S" ){
		$demens = "Tipo deve ser 'E' ou 'S'!";
		$detitu = "Weber Hotel&copy; | Estoques";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

			AtualizaEstoques($cdclie, $cdprod, $qtprod, $cdtipo);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Estoques:".$cdprod.":".$cdclie;
			$aDados[]="E/S";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro de Produtos atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Estoques";
			$devolt = "estoques.php";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>