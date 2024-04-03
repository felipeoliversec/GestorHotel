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
	//$dtcada = $datahoje;

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
			$aNomes[]= "defunc";
			$aNomes[]= "nrtele";
			$aNomes[]= "nrregi";
			$aNomes[]= "dtnasc";
			$aNomes[]= "dtadmi";
			$aNomes[]= "dtdemi";
			$aNomes[]= "deobse";

			// armazena em array
			$aDados=array();
			$aDados[]= $defunc;
			$aDados[]= $nrtele;
			$aDados[]= $nrregi;
			$aDados[]= $dtnasc;
			$aDados[]= $dtadmi;
			$aDados[]= $dtdemi;
			$aDados[]= $deobse;

			GravarDados("funcionarios",$aDados,$aNomes,"A","cdfunc",$cdfunc, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Funcionarios:".$cdfunc.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("funcionarios", "cdfunc", $cdfunc, false, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Funcionarios:".$cdfunc.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Funcionarios";
		$devolt = "funcionarios.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
