<?php
    // incluindo bibliotecas de apoio
    include "banco.php";
    include "util.php";

    $cdusua="";
    $deusua="";
    $cdclie="";
    $defoto="";
    $fladmi="";
    $demail="";

    //codigo 
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    // nome do usuario
    if (isset($_COOKIE['deusua'])) {
        $deusua = $_COOKIE['deusua'];
    }

    // email
    if (isset($_COOKIE['demail'])) {
        $demail = $_COOKIE['demail'];
    }

    //codigo do cliente
    if (isset($_COOKIE['cdclie'])) {
        $cdclie = $_COOKIE['cdclie'];
    }

    //localização da foto
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

    $aClie= ConsultarDados("clientes", "cdclie", $cdclie, false);
    $declie= trim($aClie[0]["declie"]);

    // reduzir o tamanho do nome do usuario
    $deusua1= $deusua;
    $deusua = substr($deusua, 0,15);

    //reduzir o tamanho do nome do cliente
    $declie = substr($declie, 0, 50);

    //buscar quantidade de mensagens
    $qtentra= BuscarQuantidadeEntradas($cdusua, $cdclie);

    //buscar quantidade de alertas
    $qtsaida= BuscarQuantidadeSaidas($cdusua, $cdclie);

    // usuarios
    //$aUsua= ConsultarDados("usuarios", "cdusua", $cdusua, false);

    // endereços
    $aEnde= ConsultarDados("enderecos", "cdende", $cdclie, false);
    if (count($aEnde) == 0) {
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
        $aDados[]=$cdclie;
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="A";

        GravarDados("enderecos",$aDados,$aNomes,"I","cdende",$cdclie);
        $aEnde= ConsultarDados("enderecos", "cdende", $cdclie, false);

    }

    // telefones
    $aTele= ConsultarDados("telefones", "cdtele", $cdclie, false);
    if (count($aTele) == 0) {
        $aNomes=array();
        $aNomes[]="cdtele";
        $aNomes[]="nrdddf";
        $aNomes[]="nrfixo";
        $aNomes[]="nrdddc";
        $aNomes[]="nrcelu";
        $aNomes[]="flativ";

        $aDados=array();
        $aDados[]=$cdclie;
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="";
        $aDados[]="A";

        GravarDados("telefones",$aDados,$aNomes,"I","cdtele",$cdclie);
        $aTele= ConsultarDados("telefones", "cdtele", $cdclie, false);

    }

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Weber Hotel&copy; | Principal </title>

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
                        Weber Hotel&copy;
                    </div>
                </li>
                <li class="active">
                    <?php if ($fladmi == "A") {?>
                        <a href="index1a.php"><i class="fa fa-home"></i> <span class="nav-label">Principal</span></a>
                    <?php } Else {?>
                        <a href="index1c.php"><i class="fa fa-home"></i> <span class="nav-label">Principal</span></a>
                    <?php }?>
                </li>
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
                    <span class="m-r-sm text-muted welcome-message">Bem Vindo ao Weber<strong>Hotel</strong></span>
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
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <!--h4>Meus Dados - <small>Atualização</small></h4-->
                        <button type="button" class="btn btn-primary btn-lg btn-block"><i
                                                    class="fa fa-user"></i> Dados do Hotel/Pousada - <small>Atualização</small>
                        </button>
                    </div>
                    <div class="ibox-content">
                        <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="meusdadosg.php"-->

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Dados Pessoais</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-home"></i> Endereço</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-3"><i class="fa fa-phone"></i> Telefones</a></li>
                            </ul>

                            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="dadoshotelg.php">

                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                    <br>
                                    <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="meusdadosg.php"-->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input type="hidden" name="demail1" id="demail1" value="<?php echo $aClie[0]["demail"]; ?>">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Cnpj</label>
                                                    <div class="col-md-6 ">
                                                        <input id="cdclie" name="cdclie" type="text" value="<?php echo formatar($cdclie,"cnpj"); ?>" placeholder="" class="form-control" maxlength = "20" readonly="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Razão Social</label>
                                                    <div class="col-md-10">
                                                        <input id="declie" name="declie" value="<?php echo $aClie[0]["declie"]; ?>" type="text" placeholder="" class="form-control" maxlength = "100" autofocus required="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">E-Mail</label>
                                                    <div class="col-md-10">
                                                        <input id="demail" name="demail" value="<?php echo $aClie[0]["demail"]; ?>" type="e-mail" placeholder="" class="form-control" maxlength = "255">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">WebSite</label>
                                                    <div class="col-md-10">
                                                        <input id="desite" name="desite" value="<?php echo $aClie[0]["desite"]; ?>" type="url" placeholder="Ex: http://www.meuhotel.com.br" class="form-control" maxlength = "500">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Contato</label>
                                                    <div class="col-md-10">
                                                        <input id="decont" name="decont" value="<?php echo $aClie[0]["decont"]; ?>" type="text" placeholder="" class="form-control" maxlength = "100">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Ativo?</label>
                                                    <div class="col-md-6">
                                                        <?php If ($aClie[0]["flativ"] == "S") { ?>
                                                            <select name="flativ" id="flativ">
                                                                <option selected= "selected">Sim</option>
                                                                <option>Não</option>
                                                            </select>
                                                        <?php } Else { ?>
                                                            <select name="flativ" id="flativ">
                                                                <option>Sim</option>
                                                                <option selected= "selected">Não</option>
                                                            </select>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Pendências?</label>
                                                    <div class="col-md-2">
                                                        <input id="flpago" name="flpago" value="<?php echo $aClie[0]["flpago"]; ?>" type="text" placeholder="" class="form-control" readonly = "" maxlength = "01">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!--/form-->
                                    </div>
                                    <div id="tab-2" class="tab-pane">
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Logradouro</label>
                                            <div class="col-md-6">
                                                <input id="deende" name="deende" value="<?php echo $aEnde[0]["deende"]; ?>" type="text" placeholder="" class="form-control" maxlength = "100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Número</label>
                                            <div class="col-md-1">
                                                <input id="nrende" name="nrende" value="<?php echo $aEnde[0]["nrende"]; ?>" type="text" placeholder="" class="form-control" maxlength = "6">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Complemento</label>
                                            <div class="col-md-6">
                                                <input id="decomp" name="decomp" value="<?php echo $aEnde[0]["decomp"]; ?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Bairro</label>
                                            <div class="col-md-6">
                                                <input id="debair" name="debair" value="<?php echo $aEnde[0]["debair"]; ?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Cidade</label>
                                            <div class="col-md-6">
                                                <input id="decida" name="decida" value="<?php echo $aEnde[0]["decida"]; ?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Estado</label>
                                            <div class="col-md-1">
                                                <input id="cdesta" name="cdesta" value="<?php echo $aEnde[0]["cdesta"]; ?>" type="text" placeholder="" class="form-control" maxlength = "02">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Cep</label>
                                            <div class="col-md-2">
                                                <input id="nrcep" name="nrcep" value="<?php echo $aEnde[0]["nrcep"]; ?>" type="text" placeholder="" class="form-control" maxlength = "08">
                                            </div>
                                        </div>

                                    </div>
                                    <div id="tab-3" class="tab-pane">
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Telefone</label>
                                            <div class="col-md-1">
                                                <input id="nrdddf" name="nrdddf" value="<?php echo $aTele[0]["nrdddf"]; ?>" type="text" placeholder="" class="form-control" maxlength = "04">
                                            </div>
                                            <div class="col-md-4">
                                                <input id="nrfixo" name="nrfixo" value="<?php echo $aTele[0]["nrfixo"]; ?>" type="text" placeholder="" class="form-control" maxlength = "08">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Celular</label>
                                            <div class="col-md-1">
                                                <input id="nrdddc" name="nrdddc" value="<?php echo $aTele[0]["nrdddc"]; ?>" type="text" placeholder="" class="form-control" maxlength = "04">
                                            </div>
                                            <div class="col-md-4">
                                                <input id="nrcelu" name="nrcelu" value="<?php echo $aTele[0]["nrcelu"]; ?>" type="text" placeholder="" class="form-control" maxlength = "09">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <center>
                                        <button class="btn btn-sm btn-warning " type="button" onClick="history.go(-1)"><strong>Cancelar</strong></button>
                                        <button class="btn btn-sm btn-primary " type="submit"><strong>Confirmar</strong></button>
                                    </center>
                                </div>

                            </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
                Weber<strong>Hotel</strong>
            </div>
            <div>
                <strong>Copyright&copy;</strong> Weber Hotel 2018
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
