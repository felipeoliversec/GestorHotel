<?php

	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// receber as variaveis usuario (e-mail) e senha
    $datahoje=date('Y-m-d');
	$cdusua  = $_POST["cdusua"];
	$desenh  = $_POST["desenh"];
	$desenh1 = $_POST["desenh1"];

    //tipo de usuario
    $fladmi="C";
    if (isset($_COOKIE['fladmi'])) {
        $fladmi = $_COOKIE['fladmi'];
    }

    //echo "1 - ".$cdusua."<br />";

	if (strlen($cdusua) < 15 ) {
		$cdusua=RetirarMascara($cdusua,"cpf");
	} Else {
		$cdusua=RetirarMascara($cdusua,"cnpj");
	}

	if (empty($desenh) == true ) {
		$demens = "É obrigatório informar a nova senha!";
		$detitu = "Weber Hotel&copy; | Alterar Senha";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
	} Else {

		if ($desenh !== $desenh1) {
			$demens = "As senhas informadas estão diferentes! Favor corrigir.";
			$detitu = "Weber Hotel&copy; | Alterar Senha";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "index1a.php";
			}
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
		} Else {
			//campos da tabela
			$aNomes=array();
			$aNomes[]= "desenh";

			// armazena em array
			$aDados=array();
			$aDados[]= md5($desenh); //desenh;

			GravarDados("usuarios",$aDados, $aNomes,"A","cdusua",$cdusua);

			$demens = "Senha atualizada com sucesso!";
			$detitu = "Weber Hotel&copy; | Alterar Senha";
			$devolt = "index1c.php";
			if ($fladmi == "A") {
				$devolt = "index1a.php";
			}
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
		}
	}

?>