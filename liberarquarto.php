<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$hoje=date('Y-m-d');
	$fladmi = "C";

    $cdrese = $_GET["cdrese"];
    $cdloca = $_GET["cdloca"];

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

	$aPag = VerificaPagamento($cdclie, $cdrese);

	if (count($aPag) > 0 ){

		$vlpago = $aPag[0]["vlpago"];

		if ($vlpago < 1.00) {
			$demens = "Pagamento pendente. Não é possível liberar o Quarto/Suíte/Chalé!";
			$detitu = "Weber Hotel&copy; | Liberar Quarto/Suíte/Chalé";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true) {

		$demens = "Liberação efetuada com sucesso!";

		LiberarQuarto($cdclie, $cdloca);

		// logar historico
		$aDados=array();
		$aDados[]=$datahoje;
		$aDados[]=$cdusua;
		$aDados[]="Liberar Quarto:".$cdrese.":".$cdclie;
		$aDados[]="Alteração";
		$aDados[]=$ip;

		GravarIPLog($aDados);
		$detitu = "Weber Hotel&copy; | Liberar Quartos/Suítes/Chalés";
		$devolt = "index1c.php";
		if ($fladmi == "A") {
			$devolt = "index1a.php";
		}
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
