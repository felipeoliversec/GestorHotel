<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// receber as variaveis usuario (e-mail) e senha
    $datahoje=date('Y-m-d');
	//$cdclie = trim(substr($_POST ["cdclie"], 0, strpos($_POST ["cdclie"], '-')));
	$cdclie = $_POST["cdclie"];
	$demail = $_POST["demail"];
    $demail1 = $_POST['demail1'];
	$fladmi = "C";
	
    if (isset($_COOKIE['fladmi'])) {
        $fladmi = $_COOKIE['fladmi'];
    }

	// verificando se existe e-mail cadastrado
    // email

	$Flag=true;

	if (trim($demail) !== trim($demail1)) {

		$aClie=ConsultarDados("clientes", "demail", $demail, false,false);

		if (count($aClie) > 0 ){
			$demens = "E-Mail já cadastrado!";
			$detitu = "Weber Hotel&copy; | Dados do Hotel/Pousada";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

	}

	if ($Flag == true) {

		$cdclie=RetirarMascara($cdclie,"cnpj");

		if (empty($demail) == true ) {
			$demens = "É obrigatório informar o e-mail!";
			$detitu = "Weber Hotel&copy; | Dados do Hotel/Pousada";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		} Else {

			// CLIENTES

	        $aNomes=array();
	        $aNomes[]="declie";
	        $aNomes[]="demail";
	        $aNomes[]="desite";
	        $aNomes[]="decont";
	        $aNomes[]="flativ";
	        $aNomes[]="flpago";

	        $aDados=array();
	        $aDados[]=$_POST["declie"];
	        $aDados[]=$_POST["demail"];
	        $aDados[]=$_POST["desite"];
	        $aDados[]=$_POST["decont"];
	        $aDados[]=substr($_POST ["flativ"],0,1);
	        $aDados[]=$_POST ["flpago"];

	        GravarDados("clientes",$aDados,$aNomes,"A","cdclie",$cdclie);

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

	        GravarDados("enderecos",$aDados,$aNomes,"A","cdende",$cdclie);

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

	        GravarDados("telefones",$aDados,$aNomes,"A","cdtele",$cdclie);

			$demens = "Cadastro atualizado com sucesso!";
			$detitu = "Weber Hotel&copy; | Dados do Hotel/Pousada";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "index1a.php";
			}
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
		}
	}



?>