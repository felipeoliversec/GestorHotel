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
	$flativ = substr($_POST ["flativ"],0,1);

	$vlserv = str_replace(".","",$vlserv);
	$vlserv = str_replace(",",".",$vlserv);

    //codigo
    $cdusua="00000000000";
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    //codigo do cliente
	$cdclie="0";
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
			$aNomes[]= "deserv";
			$aNomes[]= "vlserv";
			$aNomes[]= "qtserv";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $deserv;
			$aDados[]= $vlserv;
			$aDados[]= $qtserv;
			$aDados[]= $flativ;

			GravarDados("servicos",$aDados,$aNomes,"A","cdserv",$cdserv, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Serviços:".$cdserv.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("servicos", "cdserv", $cdserv, false, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Serviços:".$cdserv.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Serviços";
		$devolt = "servicos.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
