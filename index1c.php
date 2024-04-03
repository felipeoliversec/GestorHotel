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

    //codigo do condomino
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    // nome do condomino
    if (isset($_COOKIE['deusua'])) {
        $deusua = $_COOKIE['deusua'];
    }

    //codigo do condominio (cliente)
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    //localização da foto (condomino)
    if (isset($_COOKIE['defoto'])) {
        $defoto = $_COOKIE['defoto'];
    }

    //tipo de usuario
    if (isset($_COOKIE['fladmi'])) {
        $fladmi = $_COOKIE['fladmi'];
    }

    $detipo="Usuário";
    if ($fladmi == "A") {
        $detipo="Administrador";
    }

    $aDados= ConsultarDados("clientes", "cdclie", $cdclie, false);
    $declie= trim($aDados[0]["declie"]);

    // reduzir o tamanho do nome do usuario
    $deusua = substr($deusua, 0,15);

    //reduzir o tamanho do nome do cliente
    $declie = substr($declie, 0, 50);

    //buscar quantidade de mensagens
    $qtentra= BuscarQuantidadeEntradas($cdclie);

    //buscar quantidade de alertas
    $qtsaida= BuscarQuantidadeSaidas($cdclie);

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WeberHotel | Principal </title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="foto" width="80" height="80" class="img-circle" src="<?php echo $defoto; ?>" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $deusua; ?></strong>
                             </span> <span class="text-muted text-xs block"><?php echo $detipo; ?><b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="meusdados.php">Atualizar Meus Dados</a></li>
                            <li><a href="minhasenha.php">Alterar Minha Senha</a></li>
                            <?php if ($fladmi == "A") {?>
                                <li><a href="dadoshotel.php">Alterar Dados do Hotel</a></li>
                            <?php }?>
                            <li class="divider"></li>
                            <li><a href="index.html">Sair</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        Weber<strong>Hotel</strong>
                    </div>
                </li>
                <li class="active">
                    <a href="index1a.php"><i class="fa fa-home"></i> <span class="nav-label">Principal</span></a>
                </li>
                <?php if ($fladmi == "A") {?>
                    <li>
                        <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Cadastros Auxiliares</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li class="active"><a href="usuarios.php">Usuários</a></li>
                            <li><a href="funcionarios.php">Funcionários</a></li>
                            <li><a href="produtos.php">Produtos</a></li>
                            <li><a href="servicos.php">Serviços</a></li>
                            <li><a href="locais.php">Acomodações</a></li>
                            <li><a href="historicos.php">Históricos</a></li>
                        </ul>
                    </li>
                <?php }?>
                <li>
                    <a href="hospedes.php"><i class="fa fa-user-plus"></i> <span class="nav-label">Cadastrar Hóspedes</span></a>
                </li>
                <li>
                    <a href="reservas.php"><i class="fa fa-hotel"></i> <span class="nav-label">Reservar/Hospedar</span></a>
                </li>
                <li>
                    <a href="consumo.php"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Registrar Consumo</span></a>
                </li>
                <?php if ($fladmi == "A") {?>
                    <li>
                        <a href="receitas.php"><i class="fa fa-money"></i> <span class="nav-label">Receitas</span></a>
                    </li>
                    <li>
                        <a href="estoques.php"><i class="fa fa-list-alt"></i> <span class="nav-label">Registrar Estoques</span></a>
                    </li>
                <?php }?>
            </ul>
        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            </div>
            <ul class="nav navbar-top-links navbar-left">
                <br>
                <li>
                    <span><?php echo  formatar($cdclie,"cnpj")." - ";?></span>
                </li>
                <li>
                    <span><?php echo  $declie;?></span>
                </li>
            </ul>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Bem vindo ao Weber<strong>Hotel</strong></span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-info"><?php echo $qtentra;?></span>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary"><?php echo $qtsaida;?></span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fa fa-sign-out"></i> Sair
                    </a>
                </li>
            </ul>
        </nav>
        </div>
        <div class="wrapper wrapper-content">
        <?php

            $alocal=ConsultarDados("locais", "cdloca", null, false,true);
            $qtlinha=1;

        ?>

        <div class = "row">
            <div class="col-lg-4">
                <span class="label label-success">Confirmado</span>
                <span class="label label-primary">Disponível</span>
                <span class="label label-warning">Reservado</span>
                <span class="label label-danger">Ocupado</span>
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
                <span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <i class = "fa fa-bell"><span class="label label-info pull-left">Entradas de Hoje</span></i>
                <i class = "fa fa-bell"><span class="label label-primary pull-left">Saidas de Hoje</span></i> 
            </div>
        </div>
        <br>
        <div class="row">
        <?php if (count($alocal) < 1) {?>
            <div class="ibox float-e-margins">
                <div class="ibox-title">

                    <button type="button" class="btn btn-primary btn-lg btn-block"><iclass="fa fa-hotel"></i> ATENÇÃO
                    </button>

                </div>
                <center><label>Olá. Tudo bem? </label></center>
                <center><label>Esse é o seu primeiro acesso.</label></center>
                <center><label>Parabéns. Você escolheu o mais simples e descomplicado sistema para gestão de seu estabelecimento.</label></center>
                <center><label>O primeiro passo é você completar seu cadastro de cliente.</label></center>
                <center><label>O segundo passo é você cadastrar as informações de apoio como acomodações, produtos, serviços.</label></center>
                <center><label>Para facilitar, o sistema criou produtos padrão (água, refrigerante, cerveja, vinho) e um serviço padrão (diária) de forma automática e você não pode esquecer de alterar o valor desses itens antes de iniciar os trabalhos.</label></center>
                <center><label>Para facilitar, o sistema permite que você crie "as acomodações" de forma automática. Basta acessar a funcionalidade acomodações em cadastros auxiliares e escolher "gerar automatico". Você informa a quantidade de quartos e o sistema gerá para você.</label></center>
                <center><label>Agradecemos novamente o interesse e ficamos a disposição para ajudar.</label></center>
                <center><label>Anote nossos contatos:</label></center>
                <center><label>74 9-8836-3477</label></center>
                <center><label>suporte@everdeeninformatica.com.br</label></center>
        </div>
        <?php }?>

        <?php for ($i =0 ; $i < count($alocal) ; $i++ ) { ?>


                <?php $cdloca = $alocal[$i]["cdloca"];?>
                <?php $deloca = $alocal[$i]["deloca"];?>
                <?php $nranda = $alocal[$i]["nranda"];?>
                <?php $nrrama = $alocal[$i]["nrrama"];?>
                <?php $qthosp = $alocal[$i]["qthosp"];?>
                <?php $cdtipo = $alocal[$i]["cdtipo"];?>
                <?php $fllibe = $alocal[$i]["fllibe"];?>

                <?php $flsitu = "C"?>
                <?php if ($cdtipo == "C") {?>
                    <?php $cdtipo = "Casal";?>
                <?php } Else {?>
                    <?php $cdtipo = "Solteiro";?>
                <?php }?>

                <?php If ($qtlinha == 1 ){?>
                      <div class="row">
                <?php }?>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">

                        <?php  

                        $aRes= TrazReservadoDia($cdclie, $cdloca);

                        if (count($aRes) > 0) {
                            // sempre considera o última reserva do dia - casos serão raros
                            $x = count($aRes)-1;

                            // reservas    
                            $cdrese = $aRes[$x]["cdrese"];
                            $dtconf = $aRes[$x]["dtconf"];
                            $dtentp = $aRes[$x]["dtentp"];
                            $dtsaip = $aRes[$x]["dtsaip"];
                            $dtentr = $aRes[$x]["dtentr"];
                            $dtsaid = $aRes[$x]["dtsaid"];
                            $observacao= $aRes[$x]["deobse"];
                            $vlpago = $aRes[$x]["vlpago"];

                            //auxiliares
                            $botao = "btn btn-primary";

                            //ocupado se a data de entrada estiver preenchida
                            //ocupado
                            if ($dtentr !== "0000-00-00"){
                                $botao = "btn btn-danger";
                                $entrada = traduz_data_para_exibir($dtentr);
                                $saida = traduz_data_para_exibir($dtsaid);
                                if ($fllibe == "N") {
                                    $qtdias= date("d",strtotime($dtsaid)-strtotime($dtentr));
                                } Else {
                                    $qtdias="";
                                }
                             }

                            //confirmado se a data de confirmação estiver preenchida e a data de entrada não
                            //confirmado
                            if ($dtconf !== "0000-00-00" and $dtentr == "0000-00-00"){
                                $botao = "btn btn-success";
                                $entrada = traduz_data_para_exibir($dtentp);
                                $saida = traduz_data_para_exibir($dtsaip);
                                $qtdias= date("d",strtotime($dtsaip)-strtotime($dtentp));
                            }

                            //confirmado se a data de entrada prevista preenchida e a data de confirmação e a data de entrada não preenchida
                            //reservado
                            if ($dtentp !== "0000-00-00" and $dtconf == "0000-00-00" and $dtentr == "0000-00-00"){
                                $botao = "btn btn-warning";
                                $entrada = traduz_data_para_exibir($dtentp);
                                $saida = traduz_data_para_exibir($dtsaip);
                                $qtdias= date("d",strtotime($dtsaip)-strtotime($dtentp));
                            }

                            if ($vlpago > 1.00 and $fllibe == "S" and $dtentr !== "0000-00-00") {
                                $botao = "btn btn-primary";
                                $entrada="";
                                $saida="";
                            }

                        } Else {

                            $botao = "btn btn-primary";

                            $cdrese="";
                            $vlpago="";
                            $entrada="";
                            $saida="";
                            $observacao= "";
                            $vlpago ="";
                            $qtdias="";

                        }   

                        ?>

                        <div class="ibox-title">
                            <a onclick="#" href="<?php echo 'reservasq.php?chave='.$cdloca; ?>" class="<?php echo $botao;?>"><?php echo $deloca." ".$cdloca?></a>
                        </div>

                        <div class="ibox-content">
                            <table>
                                <tr>
                                    <td><strong>Reserva No.</strong></td>
                                    <td>: <?php echo $cdrese ;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo</strong></td>
                                    <td><small>: <?php echo $cdtipo; ?> </small></td>
                                </tr>
                                <tr>
                                    <td><strong>Acomoda</strong></td>
                                    <td><small>: <?php echo $qthosp; ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>Andar</strong></td>
                                    <td><small>: <?php echo $nranda; ?></small></td>
                                </tr>
                                <!--<tr>
                                    <td><strong>Ramal</strong></td>
                                    <td><small>: <?php echo $nrrama; ?></small></td>
                                </tr>-->
                                <tr>
                                    <td><strong>Entrada</strong></td>
                                    <td><small>: <?php echo $entrada; ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>Saída</strong></td>
                                    <td><small>: <?php echo $saida; ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>Quantidade Dias</strong></td>
                                    <td><small>: <?php echo trim($qtdias); ?></small></td>
                                </tr>
                            </table>
                            <br>

                            <?php if ($fllibe == "N") {?>
                                <?php if ($botao == "btn btn-danger") {?>

                                    <?php If ($vlpago > 1.00){?>
                                        <center><span class="label label-primary">PAGAMENTO EFETUADO</span></center>
                                    <?php } Else {?>
                                        <center><span class="label label-danger">PAGAMENTO PENDENTE</span></center>
                                    <?php }?>
                                    <br>    
                                    <?php If (empty($observacao) == false){?>
                                        <center><span class="label label-info">Existem Observações</span></center>
                                    <?php } Else {?>
                                        <br>
                                    <?php }?>
                                    <br>
                                    <?php $botao = "liberarquarto.php?cdrese=".$cdrese."&cdloca=".$cdloca; ?>

                                    <center><a onclick="#" href="<?php echo $botao; ?>" class="btn btn-sm btn-primary">Liberar acomodação</a></center>

                                <?php } Else {?>
                                        <br>
                                <?php }?>

                            <?php }?>

                            <?php if ($botao == "btn btn-primary") {?>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                            <?php }?>
                            <?php if ($botao == "btn btn-success") {?>
                                    
                                <?php If (empty($observacao) == false){?>
                                    <center><span class="label label-info">Existem Observações para essa reserva</span></center>
                                <?php } Else {?>
                                    <br>
                                <?php }?>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>

                            <?php }?>

                            <?php if ($botao == "btn btn-warning") {?>
                                    
                                <?php If (empty($observacao) == false){?>
                                    <center><span class="label label-info">Existem Observações para essa reserva</span></center>
                                <?php } Else {?>
                                    <br>
                                <?php }?>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>

                            <?php }?>
                       </div>
                    </div>
                </div>
                <?php If ($qtlinha == 4 ) {?>
                    </div>
                    <?php $qtlinha=1; ?>
                <?php } Else {?>
                    <?php $qtlinha=$qtlinha+1; ?>
                <?php }?>
        <?php }?>
        <br>
        <div class="footer">
            <div class="pull-right">
                Weber<strong>Hotel</strong>
            </div>
            <div>
                <strong>Copyright</strong> Weber Hotel &copy; 2018
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- EayPIE -->
    <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>

    <script>
        $(document).ready(function() {
            $('.chart').easyPieChart({
                barColor: '#f8ac59',
//                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            $('.chart2').easyPieChart({
                barColor: '#1c84c6',
//                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            var data2 = [
                [gd(2016, 1, 1), 400], [gd(2016, 2, 1), 300], [gd(2016, 3, 1), 180], [gd(2016, 4, 1), 150],
                [gd(2016, 5, 1), 88], [gd(2016, 6, 1), 455], [gd(2016, 7, 1), 93]
            ];

            var data3 = [
                [gd(2016, 1, 1), 800], [gd(2016, 2, 1), 500], [gd(2016, 3, 1), 600], [gd(2016, 4, 1), 700],
                [gd(2016, 5, 1), 178], [gd(2016, 6, 1), 555], [gd(2016, 7, 1), 993]
            ];

            var dataset = [
                {
                    label: "Receita Prevista",
                    data: data3,
                    color: "#1ab394",
                    bars: {
                        show: true,
                        align: "center",
                        barWidth: 24 * 60 * 60 * 600,
                        lineWidth:0
                    }

                }, {
                    label: "Receita Realizada",
                    data: data2,
                    yaxis: 2,
                    color: "#1C84C6",
                    lines: {
                        lineWidth:1,
                            show: true,
                            fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.2
                            }, {
                                opacity: 0.4
                            }]
                        }
                    },
                    splines: {
                        show: false,
                        tension: 0.6,
                        lineWidth: 1,
                        fill: 0.1
                    },
                }
            ];


            var options = {
                xaxis: {
                    mode: "time",
                    tickSize: [3, "month"],
                    tickLength: 0,
                    axisLabel: "Date",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 10,
                    color: "#d5d5d5"
                },
                yaxes: [{
                    position: "left",
                    max: 1070,
                    color: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 3
                }, {
                    position: "right",
                    clolor: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: ' Arial',
                    axisLabelPadding: 67
                }
                ],
                legend: {
                    noColumns: 1,
                    labelBoxBorderColor: "#000000",
                    position: "nw"
                },
                grid: {
                    hoverable: false,
                    borderWidth: 0
                }
            };

            function gd(year, month, day) {
                return new Date(year, month - 1, day).getTime();
            }

            var previousPoint = null, previousLabel = null;

            $.plot($("#flot-dashboard-chart"), dataset, options);

            var mapData = {
                "US": 298,
                "SA": 200,
                "DE": 220,
                "FR": 540,
                "CN": 120,
                "AU": 760,
                "BR": 550,
                "IN": 200,
                "GB": 120,
            };

            $('#world-map').vectorMap({
                map: 'world_mill_en',
                backgroundColor: "transparent",
                regionStyle: {
                    initial: {
                        fill: '#e4e4e4',
                        "fill-opacity": 0.9,
                        stroke: 'none',
                        "stroke-width": 0,
                        "stroke-opacity": 0
                    }
                },

                series: {
                    regions: [{
                        values: mapData,
                        scale: ["#1ab394", "#22d6b1"],
                        normalizeFunction: 'polynomial'
                    }]
                },
            });
        });
    </script>
</body>
</html>