<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$nrsequ = $_POST["nrsequ"];
	$cdcons = $_POST["cdcons"];
	$qtcons = $_POST["qtcons"];
	$vlprec	= $_POST["vlprec"];
	$vlcons	= $_POST["vlcons"];
	$cdrese	= $_POST["cdrese"];

	$vlprec = str_replace(".","",$vlprec);
	$vlprec = str_replace(",",".",$vlprec);

	$vlcons = str_replace(".","",$vlcons);
	$vlcons = str_replace(",",".",$vlcons);

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

    if ($qtcons < 1.00) {
		$demens = "É preciso informar a quantidade!";
		$detitu = "Weber Hotel&copy; | Consumo - Alteração";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if ($Flag == true) {

		switch (get_post_action('edita','apaga')) {
	    case 'edita':
			$demens = "Atualização efetuada com sucesso!";

			// armazena em array
			$aDados=array();
			$aDados[]= $nrsequ;
			$aDados[]= $cdclie;
			$aDados[]= $cdrese;
			$aDados[]= $qtcons;
			$aDados[]= $vlcons;
			$aDados[]= $vlprec;

			AtualizarConsumo($aDados);
			AtualizarConsumoReserva($cdrese, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Consumo:".$nrsequ."-".$cdrese.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("consumo", "nrsequ", $nrsequ, false, $cdclie);
			AtualizarConsumoReserva($cdrese, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Consumo: ".$nrsequ."-".$cdrese.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Consumo";
		$devolt = "consumoi.php?chave=".$cdrese;
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
