<?php 	
	
	// incluindo bibliotecas de apoio
	include "banco.php";
	include "util.php";

	// receber as variaveis usuario (e-mail) e senha
	//$cdusua = str_pad($_POST["cdusua"], 14, "0", STR_PAD_LEFT);
	$cdusua = $_POST["cdusua"];
	$desenh = md5($_POST["desenh"]);

    $datahoje=date('Y-m-d H:i:s');
    $ip=getIp();


	// verificar se usuario e senha conferem com o cadastro
	$aDados= ConfirmarSenha($cdusua, $desenh,false);

	if (count($aDados) > 0 ) {

		// dados ok
		$cdusua=$aDados[0]["cdusua"];
		$deusua=$aDados[0]["deusua"];
		$fladmi=$aDados[0]["fladmi"];
		$cdclie=$aDados[0]["cdclie"];
		$defoto=$aDados[0]["defoto"];
		$demail=$aDados[0]["demail"];

		setcookie("cdusua",$cdusua);
		setcookie("deusua",$deusua);
		setcookie("fladmi",$fladmi);
		setcookie("cdclie",$cdclie);
		setcookie("defoto",$defoto);
		setcookie("demail",$demail);

		// inativo que precisa de ativação do administrador

		if ($fladmi == "X") {
			$demens = "Seu acesso não está autorizado. Aguarde confirmação da administração de seu hotel. Se preferir fale pessoalmente com o administrador/gerente!";
			$detitu = "Weber Hotel&copy; | Acesso";
			header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
		} Else {

			// logar historico
			$aDados=array();
			$aDados[]=$datahoje;
			$aDados[]=$cdusua;
			$aDados[]="Tela de Login";
			$aDados[]="Acessar";
			$aDados[]=$ip;

			GravarIPLog($aDados);

			// cria um serviço padrão - diária caso não exista
		    $aServ = ConsultarDados("servicos", "cdclie", $cdclie, false);
		    if (count($aServ) < 1){
				$aNomes=array();
				$aNomes[]= "cdclie";
				$aNomes[]= "cdserv";
				$aNomes[]= "deserv";
				$aNomes[]= "vlserv";
				$aNomes[]= "qtserv";
				$aNomes[]= "dtcada";
				$aNomes[]= "flativ";

				// armazena em array
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "001";
				$aDados[]= "Diária Padrão Por Pessoa";
				$aDados[]= 43.99;
				$aDados[]= 1;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("servicos",$aDados,$aNomes,"I","cdserv",$cdserv);

			}

			// cria um produto padrão caso não exista
		    $aProd = ConsultarDados("produtos", "cdclie", $cdclie, false);
		    if (count($aProd) < 1){
				$aNomes=array();
				$aNomes[]= "cdclie";
				$aNomes[]= "cdprod";
				$aNomes[]= "deprod";
				$aNomes[]= "vlprod";
				$aNomes[]= "qtprod";
				$aNomes[]= "dtcada";
				$aNomes[]= "flativ";

				// Agua sem gas
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "1";
				$aDados[]= "Água Sem Gás";
				$aDados[]= 1;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

				// Agua com gas
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "2";
				$aDados[]= "Água Com Gás";
				$aDados[]= 1.50;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

				// refrigerante 
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "3";
				$aDados[]= "Refrigerante";
				$aDados[]= 3.50;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

				// sucos 
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "4";
				$aDados[]= "Sucos";
				$aDados[]= 3.50;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

				// cerveja
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "5";
				$aDados[]= "Cerveja";
				$aDados[]= 4.50;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

				// vinho
				$aDados=array();
				$aDados[]= $cdclie;
				$aDados[]= "6";
				$aDados[]= "Vinho";
				$aDados[]= 18.50;
				$aDados[]= 100;
				$aDados[]= $datahoje;
				$aDados[]= "S";

				GravarDados("produtos",$aDados,$aNomes,"I","cdprod",$cdprod);

			}

			if ($fladmi == "A") {
				//perfil administrador
				header('Location: index1a.php');
			} Else {
				//perfil usuario comum
				header('Location: index1c.php');
			}
		}
	} 
	else {
		// dados NÃO conferem
		$demens = "Usuário não existe ou a senha está errada!";
		$detitu = "Weber Hotel&copy; | Acesso";
		header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu);
	}

?>