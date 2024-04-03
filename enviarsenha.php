<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	$demail= $_POST ["demail"];	// email para o qual será enviada a nova senha
	$desenh= GerarSenha(10, true, true, true); // gera automaticamente uma nova senha

	// envia para o e-mail
	$paraquem= $demail;
	$dequem = "suporte@everdeeninformatica.com.br";
	$assunto = "Weber Hotel&copy; : Sua nova senha";
	//$corpo = "cpf: ".$_POST["cdusua"]." - email: ".$_POST["demail"]." - ".$datahoje;
	$corpo="Olá, segue sua nova senha conforme solicitado: ".$desenh;
	
	EnviarEmail($paraquem, $dequem, $assunto, $corpo);

	// grava a nova senha
	$desenh= md5($desenh);
	GravarNovaSenha($demail,$desenh);

	// apresenta mensagem enviada
	$demens = "Uma nova senha será enviada para o e-mail informado!";
	$detitu = "Weber Hotel&copy;+ | Nova Senha";
	$devolt = "index.html";
	header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
?>
