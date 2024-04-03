<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// dados iniciais de cadastro
	$cdcodi= $_POST ["cdcodi"];
	$decodi= $_POST ["decodi"];
	$demail= $_POST ["demail"];
	$desenh= $_POST ["desenh"];
	$flterm= $_POST ["chktermo"];
	$cdtipo= strtoupper($_POST ["optradio"]);

	$Flag=true;
    $datahoje=date('Y-m-d');

	If (validaCNPJ($cdcodi) == false){
		$demens = "CNPJ informado é inválido!";
		$detitu = "Weber Hotel&copy; | Registro";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	if (!isset($flterm)) {
		$demens = "Confirme que leu e concorda com o Termo de Adesão!";
		$detitu = "Weber Hotel&copy; | Registro";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	}

	// verificando se existe CNPJ cadastrado
	$aClie=ConsultarDados("clientes", "cdclie", $cdcodi, false,false);

	if (count($aClie) > 0 ){
		$demens = "CNPJ já cadastrado! ";
		$detitu = "Weber Hotel&copy; | Registro";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		$Flag=false;
	} Else {

		// verificando se existe e-mail cadatrado
		$aClie=ConsultarDados("clientes", "demail", $demail, false,false);
		$aUsua=ConsultarDados("usuarios", "demail", $demail, false,false);

		if (count($aClie) > 0 ){
			$demens = "E-Mail já cadastrado!";
			$detitu = "Weber Hotel&copy; | Registro";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}

		if (count($aUsua) > 0 ){
			$demens = "E-Mail já cadastrado! Utilize outro. ";
			$detitu = "Weber Hotel&copy; | Registro";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
			$Flag=false;
		}
	}

	//preenchendo com 14 posições
    //$cdcodi = str_pad($cdcodi,14,"0",STR_PAD_LEFT);

	if ($Flag == true) {

		// envia para o e-mail
		$paraquem= $demail;
		$dequem = "suporte@everdeeninformatica.com.br";
		$assunto = "Weber Hotel&copy; : Confirmação de Cadastro";

		//$corpo = "cpf: ".$_POST["cdusua"]." - email: ".$_POST["demail"]." - ".$datahoje;
		//$corpo="Olá, segue sua nova senha conforme solicitado: ".$desenh;
		$corpo=preparar_corpo_email_c();
		EnviarEmail($paraquem, $dequem, $assunto, $corpo);

		// criptografa a nova senha
		$desenh= md5($desenh);

		// armazena em array
		$aDados=array();
		$aDados[]= $cdcodi; //cdusua;
		$aDados[]= $decodi; //deusua;
		$aDados[]= $demail; //demail;
		$aDados[]= $desenh; //desenh;
		$aDados[]= "img/semfoto.jpg"; //decami;
		$aDados[]= $cdcodi; //cdclie;
		$aDados[]= $datahoje; //dtcada;
		$aDados[]= "A"; // indica que é um administrador 
		$aDados[]= "S"; // indica ativo

		GravarDados("usuarios",$aDados);

    	$aDadosJ=array();
		$aDadosJ[]= $cdcodi; //cdclie
		$aDadosJ[]= $decodi; //declie
		$aDadosJ[]= $demail; //demail
		$aDadosJ[]= ""; //desite
		$aDadosJ[]= ""; //decont
		$aDadosJ[]= $datahoje; //dtcada
		$aDadosJ[]= "S"; //flativ
		$aDadosJ[]= "N"; //flpago
		GravarDados("clientes",$aDadosJ);

		// apresenta mensagem enviada
		$demens = "Uma confirmação do cadastro será enviada para o e-mail informado!";
		$detitu = "Weber Hotel&copy; | Cadastro efetuado com sucesso";
		$devolt = "index.html";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);

	}

?>
