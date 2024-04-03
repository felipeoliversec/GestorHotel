<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$hoje=date('d-m-Y');
	$fladmi = "C";

	$cdhosp=trim(substr($_POST ["cdhosp"], 0, strpos($_POST ["cdhosp"], '-')));
	$cdloca=trim(substr($_POST ["cdloca"], 0, strpos($_POST ["cdloca"], '-')));
	$qthosp = $_POST["qthosp"];
	$dehosp = $_POST["dehosp"];
	$dtentp = $_POST["dtentp"];
	$dtsaip = $_POST["dtsaip"];
	$vlante = $_POST["vlante"];
	$deobse = $_POST["deobse"];

	$vlante = str_replace(".","",$vlante);
	$vlante = str_replace(",",".",$vlante);

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

	$alocais=ConsultarDados("locais", "cdloca", $cdloca, false, false, $cdclie);

	if (count($alocais) > 0 ){

		$qtloca = $alocais[0]["qthosp"];

		if ($qthosp > $qtloca) {
			$demens = "Quantidade de hóspedes superior à permitida na acomodação!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

		if ($qthosp < 1) {
			$demens = "Quantidade de hóspedes deve ser no mínimo 1!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

	}

	if ($Flag == true) {

		if (strtotime($dtentp) < strtotime($hoje)) {
			$demens = "Data de Entrada deve ser superior ou igual à Data de Hoje!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}


	if ($Flag == true) {

		if (strtotime($dtsaip) < strtotime($hoje)) {
			$demens = "Data de Saída deve ser superior ou igual à Data de Hoje!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true) {

		if (strtotime($dtentp) > strtotime($dtsaip)) {
			$demens = "Data de Saída deve ser superior à Data de Entrada!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true) {

		$Tem=VerificaReserva($cdclie, $cdloca, $dtentp);

		if (empty($Tem) == false) {

			if (strtotime($dtentp) == strtotime($hoje) ) {

				$Entrou = VerificaReservaEntrou($cdclie, $cdloca, $dtentp);

				if ($Entrou == "S") {

					$TaLivre = VerificaQuarto($cdclie, $cdloca);

					if ($TaLivre == "N") {
						$demens = "Já existe uma reserva no: ".$Tem." para esse acomodação!";
						$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
						header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
						$Flag=false;
					}
				}
			} Else {
				$demens = "Já existe uma reserva no: ".$Tem." para esse acomodação!";
				$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
				header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
				$Flag=false;
			}
		}
	}

	if ($Flag == true) {

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdclie";
			$aNomes[]= "cdhosp";
			$aNomes[]= "cdloca";
			$aNomes[]= "qthosp";
			$aNomes[]= "dehosp";
			$aNomes[]= "dtrese";
			$aNomes[]= "dtentp";
			$aNomes[]= "dtsaip";
			$aNomes[]= "vlante";
			$aNomes[]= "deobse";
			$aNomes[]= "cdusua";

			// armazena em array
			$aDados=array();
			$aDados[]= $cdclie;
			$aDados[]= $cdhosp;
			$aDados[]= $cdloca;
			$aDados[]= $qthosp;
			$aDados[]= $dehosp;
			$aDados[]= traduz_data_para_banco($hoje);
			$aDados[]= $dtentp;
			$aDados[]= $dtsaip;
			$aDados[]= $vlante;
			$aDados[]= $deobse;
			$aDados[]= $cdusua;

			$cdrese=GravarReservas($aDados);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Reservas:".$cdrese.":".$cdclie;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Reserva efetuada com sucesso! Anote o código da reserva --> ".$cdrese.". Para imprimir o contrato utilize a opção visualizar.";
			$detitu = "Weber Hotel&copy; | Reservas";
			$devolt = "reservas.php";

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>