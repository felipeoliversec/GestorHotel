<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdprod = $_POST["cdprod"];
	$deprod = $_POST["deprod"];
	$vlprod = $_POST["vlprod"];
	$qtprod = $_POST["qtprod"];
	$flativ = substr($_POST ["flativ"],0,1);

	$vlprod = str_replace(".","",$vlprod);
	$vlprod = str_replace(",",".",$vlprod);

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
			$aNomes[]= "deprod";
			$aNomes[]= "vlprod";
			$aNomes[]= "qtprod";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $deprod;
			$aDados[]= $vlprod;
			$aDados[]= $qtprod;
			$aDados[]= $flativ;

			GravarDados("produtos",$aDados,$aNomes,"A","cdprod",$cdprod, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Produtos:".$cdprod.":".$cdclie;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("produtos", "cdprod", $cdprod, false, $cdclie);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Produtos:".$cdprod.":".$cdclie;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Produtos";
		$devolt = "produtos.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
