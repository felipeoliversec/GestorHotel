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

	$Flag = true;

	if ($Flag == true) {

		switch (get_post_action('edita','apaga')) {
	    case 'edita':
			$demens = "Atualização efetuada com sucesso!";

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "deloca";
			$aNomes[]= "nranda";
			$aNomes[]= "nrrama";
			$aNomes[]= "qthosp";
			$aNomes[]= "cdtipo";
			$aNomes[]= "deobse";

			// armazena em array
			$aDados=array();
			$aDados[]= $deloca;
			$aDados[]= $nranda;
			$aDados[]= $nrrama;
			$aDados[]= $qthosp;
			$aDados[]= $cdtipo;
			$aDados[]= $deobse;

			GravarDados("locais",$aDados,$aNomes,"A","cdloca",$cdloca, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Locais:".$cdloca.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("locais", "cdloca", $cdloca, false, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Locais:".$cdloca.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Locais";
		$devolt = "locais.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
