<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdprod=trim(substr($_POST ["cdprod"], 0, strpos($_POST ["cdprod"], '-')));
	$cdserv=trim(substr($_POST ["cdserv"], 0, strpos($_POST ["cdserv"], '-')));
	$qtcons = $_POST["qtcons"];
	$cdrese	= $_POST["cdrese"];
	$preco=0.00;
	$flativ="S";

	//echo $_POST ["cdprod"]."<br />";
	//echo $_POST ["cdserv"]."<br />";
	//die();

	$Flag=true;

    //codigo do cliente
	$cdclie="00000000000000";
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    //codigo do usuario
    $cdusua="00000000000";
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    // não deixa informar produto e serviço juntos ou nenhum deles
    if (substr($_POST ["cdprod"], 0,6) == "Nenhum" and substr($_POST ["cdserv"], 0,6) == "Nenhum"){
		$demens = "É preciso informar um produto ou um serviço!";
		$detitu = "Weber Hotel&copy; | Consumo - Inclusão";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}


	if ($Flag == true) {
	    if (substr($_POST ["cdprod"], 0,6) !== "Nenhum" and substr($_POST ["cdserv"], 0,6) !== "Nenhum") {
			$demens = "Não é permitido informar produto e serviço! Escolha apenas um deles.";
			$detitu = "Weber Hotel&copy; | Consumo - Inclusão";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

    // quantidade deve ser maior que zero
	if ($Flag == true) {
	    if ($qtcons < 1.00) {
			$demens = "É preciso informar a quantidade!";
			$detitu = "Weber Hotel&copy; | Consumo - Inclusão";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true) {

		if (substr($_POST ["cdprod"], 0,6) !== "Nenhum") {

			$aProd=ConsultarDados("produtos", "cdprod", $cdprod, false, false, $cdclie);
			$preco = $aProd[0]["vlprod"];
			$flcons = "P";
			$cdcons = $aProd[0]["cdprod"];
		}

		if (substr($_POST ["cdserv"], 0,6) !== "Nenhum") {

			$aServ=ConsultarDados("servicos", "cdserv", $cdserv, false, false, $cdclie);
			$preco = $aServ[0]["vlserv"];
			$flcons = "S";
			$cdcons = $aServ[0]["cdserv"];
		}

	    // calcular o preço pela quantidade
		$vlprec = $qtcons * $preco;
	}


	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "flcons";
			$aNomes[]= "cdclie";
			$aNomes[]= "cdrese";
			$aNomes[]= "cdcons";
			$aNomes[]= "dtcons";
			$aNomes[]= "qtcons";
			$aNomes[]= "vlcons";
			$aNomes[]= "vlprec";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $flcons;
			$aDados[]= $cdclie;
			$aDados[]= $cdrese;
			$aDados[]= $cdcons;
			$aDados[]= $datahoje;
			$aDados[]= $qtcons;
			$aDados[]= $vlprec;
			$aDados[]= $preco;
			$aDados[]= $flativ;

			GravarConsumo($aDados);
			AtualizarConsumoReserva($cdrese, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Registro do Consumo:".$cdrese.":".$cdclie;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Consumo - Inclusão";
			//$devolt = "index1c.php";
			$devolt = "consumoi.php?chave=".$cdrese;
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>