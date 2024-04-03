<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

	$cdhosp = $_POST["cdhosp"];
	$dehosp = $_POST["dehosp"];
	$demail = $_POST["demail"];
	$defoto = $_POST["defoto"];
	$dtulti = $_POST["dtulti"];
	$vlulti = $_POST["vlulti"];
	$dtcada	= $datahoje;
	$flativ	= substr($_POST["flativ"], 0,1);

    //codigo
    $cdusua="00000000000";
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    //codigo do cliente
	$cdclie="";
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    if (strlen($cdhosp) < 15) {
		$chave=RetirarMascara($cdhosp,"cpf");
    } Else {
		$chave=RetirarMascara($cdhosp,"cnpj");
    }

	$Flag = true;

	if ($Flag == true) {

		// tratando o upload da foto
		$uploaddir = 'img/'.$chave;
		$defoto=basename($_FILES['defoto']['name']);
		$defoto1=$_POST["defoto1"];
		if (empty($defoto)==true) {
			$uploadfile = $defoto1;
		} Else {
			$uploadfile = $uploaddir . basename($_FILES['defoto']['name']);
		}

		// upload do arquivo da foto
		move_uploaded_file($_FILES['defoto']['tmp_name'], $uploadfile);

		switch (get_post_action('edita','apaga')) {
	    case 'edita':
			$demens = "Atualização efetuada com sucesso!";

			//HOSPEDES
			//campos da tabela
			$aNomes=array();
			$aNomes[]= "dehosp";
			$aNomes[]= "demail";
			$aNomes[]= "defoto";
			$aNomes[]= "dtulti";
			$aNomes[]= "vlulti";
			$aNomes[]= "flativ";

			$vlulti = str_replace(".","",$vlulti);
			$vlulti = str_replace(",",".",$vlulti);

			// armazena em array
			$aDados=array();
			$aDados[]= $dehosp; 
			$aDados[]= $demail;
			$aDados[]= $uploadfile; 
			$aDados[]= $dtulti; 
			$aDados[]= $vlulti;
	        $aDados[]=substr($_POST ["flativ"],0,1);

			GravarDados("hospedes",$aDados,$aNomes,"A","cdhosp",$chave,$cdclie);

			// ENDERECOS
	        $aNomes=array();
	        $aNomes[]="deende";
	        $aNomes[]="nrende";
	        $aNomes[]="decomp";
	        $aNomes[]="debair";
	        $aNomes[]="decida";
	        $aNomes[]="cdesta";
	        $aNomes[]="nrcep";
	        $aNomes[]="flativ";

	        $aDados=array();
	        $aDados[]=$_POST["deende"];
	        $aDados[]=$_POST["nrende"];
	        $aDados[]=$_POST["decomp"];
	        $aDados[]=$_POST["debair"];
	        $aDados[]=$_POST["decida"];
	        $aDados[]=strtoupper($_POST["cdesta"]);
	        $aDados[]=$_POST["nrcep"];
	        $aDados[]="A";

	        GravarDados("enderecos",$aDados,$aNomes,"A","cdende",$chave);

			// TELEFONES
	        $aNomes=array();
	        $aNomes[]="nrdddf";
	        $aNomes[]="nrdddc";
	        $aNomes[]="nrfixo";
	        $aNomes[]="nrcelu";
	        $aNomes[]="flativ";

	        $aDados=array();
	        $aDados[]=$_POST["nrdddf"];
	        $aDados[]=$_POST["nrdddc"];
	        $aDados[]=$_POST["nrfixo"];
	        $aDados[]=$_POST["nrcelu"];
	        $aDados[]="A";

	        GravarDados("telefones",$aDados,$aNomes,"A","cdtele",$chave);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Hóspedes:".$chave;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("hospedes", "cdhosp", $chave, false, $cdclie);
			ExcluirDados("enderecos", "cdende", $chave, false);
			ExcluirDados("telefones", "cdtele", $chave, false);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Hóspedes:".$chave;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Hóspedes";
		$devolt = "hospedes.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
