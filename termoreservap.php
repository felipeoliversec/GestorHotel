<?php

    // identificando dispositivo
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

    $eMovel="N";
    if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
        $eMovel="S";
    }

    // incluindo bibliotecas de apoio
    include "banco.php";
    include "util.php";

    //codigo do cliente
    $cdclie="61526678000105";
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    $chave=$_GET["chave"];

    $aReservas= ConsultarDados("reservas", "cdrese", $chave, false, false, $cdclie);

    $cdhosp=$aReservas[0]["cdhosp"];
    $aHospedes= ConsultarDados("hospedes", "cdhosp", $cdhosp, false, false, $cdclie);

    $cdloca=$aReservas[0]["cdloca"];
    $aLocais= ConsultarDados("locais", "cdloca", $cdloca, false, false, $cdclie);

    $aDados=array();
    $aDados[]=$aHospedes[0]["cdhosp"]." - ".$aHospedes[0]["dehosp"];
    $aDados[]=$aLocais[0]["cdloca"]." - ".$aLocais[0]["deloca"];
    $aDados[]=$chave;
    $aDados[]=$aReservas[0]["qthosp"];
    $aDados[]=$aReservas[0]["dehosp"];
    $aDados[]=strtotime($aReservas[0]["dtrese"]);
    $aDados[]=strtotime($aReservas[0]["dtentp"]);
    $aDados[]=strtotime($aReservas[0]["dtsaip"]);
    $aDados[]=strtotime($aReservas[0]["dtentr"]);
    $aDados[]=strtotime($aReservas[0]["dtsaid"]);
    $aDados[]=$aReservas[0]["vlrese"];
    $aDados[]=$aReservas[0]["vlcons"];
    $aDados[]=$aReservas[0]["vlante"];
    $aDados[]=$aReservas[0]["deobse"];

    $cdhosp = trim($aHospedes[0]["cdhosp"]);
    $aEnde= ConsultarDados("enderecos", "cdende", $cdhosp, false, false);
    $aTele= ConsultarDados("telefones", "cdtele", $cdhosp, false, false);

    $aEndeC= ConsultarDados("enderecos", "cdende", $cdclie, false, false);
    $aTeleC= ConsultarDados("telefones", "cdtele", $cdclie, false, false);

    $aClie= ConsultarDados("clientes", "cdclie", $cdclie, false);
    $declie= trim($aClie[0]["declie"]);

    if (count($aEnde) < 1) {
        echo "Nao e possivel imprimir o contrato porque nao foram encontrados os dados de endereco do hospede.";
        die();
    }

    if (count($aEndeC) < 1) {
        echo "Nao e possivel imprimir o contrato porque nao foram encontrados os dados de endereco do estabelecimento.";
        die();
    }

    if (count($aTeleC) < 1) {
        echo "Nao e possivel imprimir o contrato porque nao foram encontrados telefones do estabelecimento.";
        die();
    }

    if (count($aTele) < 1) {
        echo "Nao e possivel imprimir o contrato porque nao foram encontrados telefones do hospede.";
        die();
    }

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Weber Hotel&copy; | Termo de Reserva/Hospedagem Print</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="white-bg">
                <div class="wrapper wrapper-content p-xl">
                    <div class="ibox-content p-xl">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5><?php echo 'Hóspede: '.$aDados[0] ;?></h5>
                                    <address>
                                        <strong>Endereço:</strong><br>
                                        <?php echo $aEnde[0]["deende"].", ".$aEnde[0]["nrende"]." - ".$aEnde[0]["decomp"];?><br>
                                        <?php echo $aEnde[0]["debair"]." - ".$aEnde[0]["decida"]." - ".$aEnde[0]["cdesta"]." - ".$aEnde[0]["nrcep"];?><br>
                                        <abbr title="<?php echo $aTele[0]["nrdddf"]; ?>"></abbr> <?php echo $aTele[0]["nrfixo"] ;?>
                                    </address>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <h4>Reserva No.</h4>
                                    <h4 class="text-navy"><?php echo $aDados[2];?></h4>
                                    <span>Estabelecimento</span>
                                    <address>
                                        <strong><?php echo $cdclie." - ".$declie ;?></strong><br>
                                        <?php echo $aEndeC[0]["deende"].", ".$aEndeC[0]["nrende"]." - ".$aEndeC[0]["decomp"];?><br>
                                        <?php echo $aEndeC[0]["debair"]." - ".$aEndeC[0]["decida"]." - ".$aEndeC[0]["cdesta"]." - ".$aEnde[0]["nrcep"];?><br>
                                        <abbr title="<?php echo $aTeleC[0]["nrdddf"]; ?>"></abbr> <?php echo $aTeleC[0]["nrfixo"] ;?>
                                    </address>
                                    <div class="table-responsive m-t">
                                        <table class="table invoice-table">
                                            <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Descrição</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                            <tr>
                                                <td class="left"><strong>Acomodação</strong></td>
                                                <td><strong><?php echo $aDados[1];?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Quantidade Hóspedes</strong></td>
                                                <td><strong><?php echo $aDados[3];?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Data da Reserva</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[5]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Data Prevista de Entrada</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[6]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Data Prevista de Saída</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[7]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Data Efetiva de Entrada</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[8]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Data Efetiva de Saída</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[9]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Valor da Reserva</strong></td>
                                                <td><strong><?php echo date('d-m-Y', $aDados[10]);?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Valor do Consumo</strong></td>
                                                <td><strong><?php echo $aDados[10];?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="left"><strong>Valor da Antecipação</strong></td>
                                                <td><strong><?php echo $aDados[11];?></strong></td>
                                            </tr>

                                           <tr>
                                                <td class="left"><strong>Detalhe Hóspedes</strong></td>
                                                <td><strong><?php echo $aDados[4];?></strong></td>
                                            </tr>
                                           <tr>
                                                <td class="right"><strong>Observações</strong></td>
                                                <td><strong><?php echo $aDados[13];?></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Contrato de Prestação de Serviço de Hospedagem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><small>Pelo presente termo de declaração pública e aderente à legislação vigente, as partes supra-identificadas, dão ciência a quem interessar possa e ao público em geral, das condições negociais que estabelecem para a contratação de serviços de hospedagem, condições estas a que se submetem, obrigando-se a cumpri-las e fazê-las boas, as quais, devidamente divulgadas e cientificadas, sendo que assinando, ou confirmando via internet a reserva, automaticamente estarão as partes confirmando e aceitando as condições abaixo expressas.</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>1 - </strong><small>As reservas serão consideradas a partir das 12h da data da chegada até às 12h da data da saída; qualquer tolerância dependerá da disponibilidade que deverá ser expressamente autorizada pelo estabelecimento; </small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>2 - </strong><small>As reservas somente serão confirmadas após o pagamento do sinal ou do total conforme a opção definida na reserva;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>3 - </strong><small>Em caso de cancelamento com até 7 dias de antecedência: o pagamento efetuado poderá ser integralmente restituído com até 10 dias após a data do recebimento de cada parcela, poderá ser  transferido para uma reserva futura ou para outra pessoa. Havendo diferença de tarifas conforme o período escolhido e número de pessoas, esta será cobrada no check-out;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>4 - </strong><small>Em caso de cancelamento com até 1 dia de antecedência, o pagamento efetuado poderá ser transferido para outro período, conforme acima definido, estabelecendo as partes uma multa correspondente ao valor de uma diária ou a critério do cliente, ser devolvido seguindo o mesmo critério da clausula 3º, descontando-se do valor, nesse caso, a multa por descumprimento contratual e a taxa operacional de 30% do valor da reserva, destinada a ressarcir os custos de remessas, ficais e operacionais do estabelecimento;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>5 - </strong><small>Se não houver solicitação formalizada de cancelamento, dentro dos prazos estabelecidos, para liberação do apartamento o total já pago fica como no-show da hospedagem, e o apartamento ficará bloqueado até o fechamento do valor pago;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>6 - </strong><small>Reservas confirmadas mediante pagamento de sinal, o apartamento ficará bloqueado até às 10h do dia seguinte da data programada para a chegada;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>7 - </strong><small>Em caso de pacote especial para feriados ou eventos especificados, fica assegurado ao estabelecimento o pagamento de no-show de 100% do pacote no Check-in, independente do tempo de permanência do cliente;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>8 - </strong><small>Na hospedagem está incluso o café da manhã ou outros serviços previamente especificados na reserva;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>9 - </strong><small>Fica acordado que este contrato será regido pelo código civil brasileiro, sendo instituído o foro desta comarca para dirimir quaisquer dúvidas</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>10 - </strong><small>Fica ciente que o hóspede deve respeitar as normas e regulamentos do estabelecimento a qual será entregue junto com esse termo ou disponibilizada em local de fácil visualização nas dependências do estabelecimento;</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>11 - </strong><small>Assim sendo, para que cumpra seus efeitos legais, é o presente assinado pelo representante legal das empresas supra identificadas</small></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                <p>
                                    <small>________________, ____ de __________________de ______</small>
                                </p>
                                <br>
                                <p>
                                    <small>_____________________________________________________</small><br>   
                                    <span><small>CONTRATANTE</small></span>
                                </p>
                                <br>
                                <p>
                                    <small>_____________________________________________________</small><br>
                                    <span><small>CONTRATADO</small></span>
                                </p>

                            </div><!-- /table-responsive -->
                        </div>

    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>

    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>
