<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// criando array para guardar os dados
	$aDados=array();
	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	
    //código de usuario
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    $chave = $_POST["cdusua"];
    $demail1=$_POST["demail1"];
    $desenh1=$_POST["desenh1"];
    $demail=$_POST["demail"];
    $desenh=$_POST["desenh"];
    $cdclie=$_POST["cdclie"];

	$chave=RetirarMascara($chave,"cpf");

	$Flag = true;

	if (trim($demail) !== trim($demail1)) {

		if (empty($demail) == true ) {
			$demens = "É obrigatório informar o e-mail!";
			$detitu = "Weber Hotel&copy; | Cadastro de Usuários";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		} Else {
			$aClie=ConsultarDados("clientes", "demail", $demail, false,false);
			$aUsua=ConsultarDados("usuarios", "demail", $demail, false,false);

			if (count($aClie) > 0 ){
				$demens = "E-Mail já cadastrado! Utilize outro. ";
				$detitu = "Weber Hotel&copy; | Cadastro de Usuários";
				header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
				$Flag=false;
			}

			if (count($aUsua) > 0 ){
				$demens = "E-Mail já cadastrado! Utilize outro. ";
				$detitu = "Weber Hotel&copy; | Cadastro de Usuários";
				header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
				$Flag=false;
			}
		}

	}

	if ($desenh !== $desenh1) {
		$desenh = md5($desenh);
	}

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

	if ($Flag == true) {

		switch (get_post_action('edita','apaga')) {
	    case 'edita':
			$demens = "Atualização efetuada com sucesso!";

			//USUARIOS
			//campos da tabela
			$aNomes=array();
			$aNomes[]= "deusua";
			$aNomes[]= "demail";
			$aNomes[]= "desenh";
			$aNomes[]= "defoto";
			$aNomes[]= "cdclie";
			$aNomes[]= "dtcada";
			$aNomes[]= "fladmi";
			$aNomes[]= "flativ";

			// armazena em array
			$aDados=array();
			$aDados[]= $_POST["deusua"]; 
			$aDados[]= $_POST["demail"]; 
			$aDados[]= md5($_POST["desenh"]); 
			$aDados[]= $uploadfile; 
			$aDados[]= $cdclie; 
			$aDados[]= $datahoje;
	        $aDados[]=substr($_POST ["fladmi"],0,1);
	        $aDados[]=substr($_POST ["flativ"],0,1);

			GravarDados("usuarios",$aDados,$aNomes,"A","cdusua",$chave);

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
			$aDados[]="Cadastro de Usuários:".$chave;
			$aDados[]="Alteração";
			$aDados[]=$ip;

			GravarIPLog($aDados);
	    	break;

	    case 'apaga':
			$demens = "Exclusão efetuada com sucesso!";

			ExcluirDados("usuarios", "cdusua", $chave, false);
			ExcluirDados("enderecos", "cdende", $chave, false);
			ExcluirDados("telefones", "cdtele", $chave, false);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Usuários:".$chave;
			$aDados[]="Exclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

	    	break;

	    default:
			$demens = "Ocorreu um problema na atualização/exclusão. Se persistir contate o suporte@sofcom.com.br!";
		}
		$detitu = "Weber Hotel&copy; | Cadastro de Usuários";
		$devolt = "usuarios.php";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
	}

?>
