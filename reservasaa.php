<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$hoje=date('Y-m-d');
	$fladmi = "C";

	$cdrese = trim($_POST["cdrese"]);
	$cdhosp = trim(substr($_POST ["cdhosp"], 0, strpos($_POST ["cdhosp"], '-')));
	$cdloca = trim(substr($_POST ["cdloca"], 0, strpos($_POST ["cdloca"], '-')));
	$qthosp = $_POST["qthosp"];
	$dehosp = $_POST["dehosp"];
	$dtrese = $_POST["dtrese"];
	$dtentp = $_POST["dtentp"];
	$dtsaip = $_POST["dtsaip"];
	$dtconf = $_POST["dtconf"];
	$dtentr = $_POST["dtentr"];
	$dtsaid = $_POST["dtsaid"];
	$vlrese = $_POST["vlrese"];
	$vlcons = $_POST["vlcons"];
	$vlante = $_POST["vlante"];
	$deobse = $_POST["deobse"];
	$vlpago = $_POST["vlpago"];

	$dtentp1 = $_POST["dtentp1"];
	$dtsaip1 = $_POST["dtsaip1"];

	if (empty($dtconf)==true) {
		$dtconf = null;
	}
	if (empty($dtentr)==true) {
		$dtentr = null;
	}
	if (empty($dtsaid)==true) {
		$dtsaid = null;
	}

	$vlrese = str_replace(".","",$vlrese);
	$vlrese = str_replace(",",".",$vlrese);

	$vlcons = str_replace(".","",$vlcons);
	$vlcons = str_replace(",",".",$vlcons);

	$vlante = str_replace(".","",$vlante);
	$vlante = str_replace(",",".",$vlante);

	$vlpago = str_replace(".","",$vlpago);
	$vlpago = str_replace(",",".",$vlpago);

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
	$Acao=get_post_action('edita','apaga');

	$alocais=ConsultarDados("locais", "cdloca", $cdloca, false, false, $cdclie);

	if (count($alocais) > 0 and $Acao == "edita"){

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


	if ($Flag == true and $Acao == "edita" and strtotime($dtentp) !== strtotime($dtentp1)) {

		if (strtotime($dtentp) < strtotime($hoje)) {
			$demens = "Data de Entrada deve ser superior ou igual à Data de Hoje!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}


	if ($Flag == true and $Acao == "edita"  and strtotime($dtsaip) !== strtotime($dtsaip1)) {

		if (strtotime($dtsaip) < strtotime($hoje)) {
			$demens = "Data de Saída deve ser superior ou igual à Data de Hoje!";
			$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true and $Acao == "edita") {
		if (strtotime($dtentp) !== strtotime($dtentp1) or strtotime($dtsaip) !== strtotime($dtsaip1)) {
			if (strtotime($dtentp) > strtotime($dtsaip)) {
				$demens = "Data de Saída deve ser superior à Data de Entrada!";
				$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
				header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
				$Flag=false;
			}
		}
	}

	if ($Flag == true and $Acao == "edita") {

		$Tem=VerificaReserva($cdclie, $cdloca, $dtentp);
		if (empty($Tem) == false) {
			if (trim($Tem) !== $cdrese) {
				$demens = "Já existe uma reserva no: ".trim($Tem)." para essa acomodação!";
				$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
				header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
				$Flag=false;				
			}
		}
	}

	if ($Flag == true) {

		switch (get_post_action('edita','apaga')) {
	    case 'edita':
			$demens = "Atualização efetuada com sucesso!";

			// armazena em array
			$aDados=array();
			$aDados[]= $cdrese;
			$aDados[]= $cdclie;
			$aDados[]= $cdhosp;
			$aDados[]= $cdloca;
			$aDados[]= $qthosp;
			$aDados[]= $dehosp;
			$aDados[]= $dtentp;
			$aDados[]= $dtsaip;
			$aDados[]= $dtconf;
			$aDados[]= $dtentr;
			$aDados[]= $dtsaid;
			$aDados[]= $vlrese;
			$aDados[]= $vlcons;
			$aDados[]= $vlante;
			$aDados[]= $cdusua;
			$aDados[]= $deobse;
			$aDados[]= $vlpago;

			AtualizarReservas($aDados);

			$dt=strtotime($dtentr);
		    if (empty($dt) == false) {
		    	OcuparQuarto($cdclie, $cdloca);
		    }

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Reservas:".$cdrese.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("reservas", "cdrese", $cdrese, false, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Reservas:".$cdrese.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Reservas";
		$devolt = "reservas.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
