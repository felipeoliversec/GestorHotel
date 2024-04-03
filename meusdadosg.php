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
	$nrtele = $_POST["nrtele"];
	$delogr = $_POST["delogr"];
	$fladmi = "C";


	// verificando se existe e-mail cadastrado
    // email
    if (isset($_COOKIE['demail'])) {
        $demail1 = $_COOKIE['demail'];
    }

	$Flag=true;

	if (trim($demail) !== trim($demail1)) {

		$aClie=ConsultarDados("clientes", "demail", $demail, false,false);
		$aUsua=ConsultarDados("usuarios", "demail", $demail, false,false);

		if (count($aClie) > 0 ){
			$demens = "E-Mail já cadastrado!";
			$detitu = "Weber Hotel&copy; | Meus Dados";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

		if (count($aUsua) > 0 ){
			$demens = "E-Mail já cadastrado! Utilize outro. ";
			$detitu = "Weber Hotel&copy; | Meus Dados";
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
		$uploadfile = $uploaddir . basename($_FILES['defoto']['name']);

		// upload do arquivo da foto
		move_uploaded_file($_FILES['defoto']['tmp_name'], $uploadfile);

		if (empty($demail) == true ) {
			$demens = "É obrigatório informar o e-mail!";
			$detitu = "Weber Hotel&copy; | Meus Dados";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		} Else {

			//USUARIOS

			//campos da tabela
			$aNomes=array();
			$aNomes[]= "deusua";
			$aNomes[]= "demail";
			$aNomes[]= "defoto";

			// armazena em array
			$aDados=array();
			$aDados[]= $_POST["deusua"]; //deusua;
			$aDados[]= $_POST["demail"]; //demail;
			$defoto1=basename($_FILES['defoto']['name']);
			if (empty($defoto1) == true) {
				$aDados[]= $defoto; 	 //defoto;
			} Else {
				$aDados[]= $uploadfile; 	 //defoto;
				$defoto= $uploadfile;
			}

			GravarDados("usuarios",$aDados,$aNomes,"A","cdusua",$cdusua);

			setcookie("deusua",$deusua);
			setcookie("defoto",$defoto);
			setcookie("demail",$demail);

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

	        GravarDados("enderecos",$aDados,$aNomes,"A","cdende",$cdusua);

			// TELEFONES
	        $aNomes=array();
	        $aNomes[]="nrdddf";
	        $aNomes[]="nrfixo";
	        $aNomes[]="nrdddc";
	        $aNomes[]="nrcelu";
	        $aNomes[]="flativ";

	        $aDados=array();
	        $aDados[]=$_POST["nrdddf"];
	        $aDados[]=$_POST["nrfixo"];
	        $aDados[]=$_POST["nrdddc"];
	        $aDados[]=$_POST["nrcelu"];
	        $aDados[]="A";

	        GravarDados("telefones",$aDados,$aNomes,"A","cdtele",$cdusua);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Meus Dados";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "index1a.php";
			}
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
		}
	}



?>