<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// receber as variaveis usuario (e-mail) e senha
    $datahoje=date('Y-m-d');
	//$cdclie = trim(substr($_POST ["cdclie"], 0, strpos($_POST ["cdclie"], '-')));
	$cdusua = $_POST["cdusua"];
	$demail = $_POST["demail"];
	$deusua = $_POST["deusua"];
	$defoto = $_POST["defoto"];
	$nrdddf = $_POST["nrdddf"];
	$ip=getIp();
	$datahoje=date('Y-m-d H:i:s');
	$fladmi = "C";

    //codigo
    $cdusua1="00000000000";
    if (isset($_COOKIE['cdusua'])) {
        $cdusua1 = $_COOKIE['cdusua'];
    }

    //codigo do cliente
	$cdclie="";
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

	// verificando se existe e-mail cadastrado
    // email
    if (isset($_COOKIE['demail'])) {
        $demail1 = $_COOKIE['demail'];
    }

	$Flag=true;

	If (validaCPF($cdusua) == false){
		$demens = "CPF informado é inválido!";
		$detitu = "Weber Hotel&copy; | Inclusão de Usuários";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if (trim($demail) !== trim($demail1)) {

		$aClie=ConsultarDados("clientes", "demail", $demail, false,false);
		$aUsua=ConsultarDados("usuarios", "demail", $demail, false,false);

		if (count($aClie) > 0 ){
			$demens = "E-Mail já cadastrado!";
			$detitu = "Weber Hotel&copy; | Inclusão de Usuários";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

		if (count($aUsua) > 0 ){
			$demens = "E-Mail já cadastrado! Utilize outro. ";
			$detitu = "Weber Hotel&copy; | Inclusão de Usuários";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	if ($Flag == true) {
	    //tipo de usuario
	    if (isset($_COOKIE['fladmi'])) {
	        $fladmi = $_COOKIE['fladmi'];
	    }

		if (strlen($cdusua) < 15 ) {
			$cdusua=RetirarMascara($cdusua,"cpf");
		} Else {
			$cdusua=RetirarMascara($cdusua,"cnpj");
		}

		// tratando o upload da foto
		$uploaddir = 'img/'.$cdusua;
		$defoto=basename($_FILES['defoto']['name']);
		if (empty($defoto)==true) {
			$uploadfile = "img/semfoto.jpg";
		} Else {
			$uploadfile = $uploaddir . basename($_FILES['defoto']['name']);
		}

		// upload do arquivo da foto
		move_uploaded_file($_FILES['defoto']['tmp_name'], $uploadfile);

		if (empty($demail) == true ) {
			$demens = "É obrigatório informar o e-mail!";
			$detitu = "Weber Hotel&copy; | Inclusão de Usuários";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		} Else {

			//USUARIOS

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "cdusua";
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
			$aDados[]= $_POST["cdusua"]; 
			$aDados[]= $_POST["deusua"]; 
			$aDados[]= $_POST["demail"]; 
			$aDados[]= md5($_POST["desenh"]); 
			$aDados[]= $uploadfile; 
			$aDados[]= $cdclie; 
			$aDados[]= $datahoje;
	        $aDados[]=substr($_POST ["fladmi"],0,1);
	        $aDados[]=substr($_POST ["flativ"],0,1);

			GravarDados("usuarios",$aDados,$aNomes,"I","cdusua",$cdusua);

			// ENDERECOS
	        $aNomes=array();
	        $aNomes[]="cdende";
	        $aNomes[]="deende";
	        $aNomes[]="nrende";
	        $aNomes[]="decomp";
	        $aNomes[]="debair";
	        $aNomes[]="decida";
	        $aNomes[]="cdesta";
	        $aNomes[]="nrcep";
	        $aNomes[]="flativ";

	        $aDados=array();
	        $aDados[]=$cdusua;
	        $aDados[]=$_POST["deende"];
	        $aDados[]=$_POST["nrende"];
	        $aDados[]=$_POST["decomp"];
	        $aDados[]=$_POST["debair"];
	        $aDados[]=$_POST["decida"];
	        $aDados[]=strtoupper($_POST["cdesta"]);
	        $aDados[]=$_POST["nrcep"];
	        $aDados[]="A";

	        GravarDados("enderecos",$aDados,$aNomes,"I","cdende",$cdusua);

			// TELEFONES
	        $aNomes=array();
	        $aNomes[]="cdtele";
	        $aNomes[]="nrdddf";
	        $aNomes[]="nrdddc";
	        $aNomes[]="nrfixo";
	        $aNomes[]="nrcelu";
	        $aNomes[]="flativ";

	        $aDados=array();
	        $aDados[]=$cdusua;
	        $aDados[]=$_POST["nrdddf"];
	        $aDados[]=$_POST["nrdddc"];
	        $aDados[]=$_POST["nrfixo"];
	        $aDados[]=$_POST["nrcelu"];
	        $aDados[]="A";

	        GravarDados("telefones",$aDados,$aNomes,"I","cdtele",$cdusua);

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Cadastro de Usuários:".$cdusua;
			$aDados[]="Inclusão";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Inclusão de Usuários";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "usuarios.php";
			}

			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
		}
	}



?>