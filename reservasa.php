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

    $acao = $_GET["acao"];
    $chave = trim($_GET["chave"]);

    switch ($acao) {
    case 'ver':
        $titulo = "Consulta";
        break;
    case 'edita':
        $titulo = "Alteração";
        break;
    case 'apaga':
        $titulo = "Exclusão";
        break;
    case 'pagar':
        $titulo = "Fechar";
        break;
    default:
        header('Location: reservas.php');
    }

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

    $aReservas= ConsultarDados("reservas", "cdrese", $chave, false, false, $cdclie);

    $cdhosp=$aReservas[0]["cdhosp"];
    $aHospedes= ConsultarDados("hospedes", "cdhosp", $cdhosp, false, false, $cdclie);

    $cdloca=$aReservas[0]["cdloca"];
    $aLocais= ConsultarDados("locais", "cdloca", $cdloca, false, false, $cdclie);

    $aDados= ConsultarDados("clientes", "cdclie", $cdclie, false);
    $declie= trim($aDados[0]["declie"]);

    // reduzir o tamanho do nome do usuario
    $deusua1= $deusua;
    $deusua = substr($deusua, 0,15);

    //reduzir o tamanho do nome do cliente
    $declie = substr($declie, 0, 50);

    //buscar quantidade de mensagens
    $qtentra= BuscarQuantidadeEntradas($cdusua, $cdclie);

    //buscar quantidade de alertas
    $qtsaida= BuscarQuantidadeSaidas($cdusua, $cdclie);

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
                    <span class="m-r-sm text-muted welcome-message">Bem Vindo ao Weber Hotel&copy;</span>
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
                                                    class="fa fa-hotel"></i> Reservas - <small><?php echo $titulo; ?></small>
                        </button>
                    </div>
                    <div class="ibox-content">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-hotel"></i> Principal</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-file-archive-o"></i> Acompanhantes</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-3"><i class="fa fa-file-pdf-o"></i> Observações</a></li>
                            </ul>

                            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="reservasaa.php">

                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                        <br>
                                        <div class="row">

                                            <input type="hidden" name="cdrese" id="cdrese" value="<?php echo $chave ;?>">
                                            <input type="hidden" name="dtentp1" id="dtentp1" value="<?php echo $aReservas[0]["dtentp"] ;?>">
                                            <input type="hidden" name="dtsaip1" id="dtsaip1" value="<?php echo $aReservas[0]["dtsaip"] ;?>">

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Hóspede</label>  
                                                <div class="col-md-6">
                                                    <input id="cdhosp" name="cdhosp" value="<?php echo $aHospedes[0]["cdhosp"]." - ".$aHospedes[0]["dehosp"] ;?>" type="text" placeholder="" class="form-control" readonly="" maxlength = "60">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Acomodação</label>  
                                                <div class="col-md-4">
                                                    <input id="cdloca" name="cdloca" value="<?php echo $aLocais[0]["cdloca"]." - ".$aLocais[0]["deloca"] ;?>" type="text" placeholder="" class="form-control" readonly="" maxlength = "60">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Quantidade Hóspedes</label>
                                                <div class="col-md-1">
                                                    <input id="qthosp" name="qthosp" value="<?php echo $aReservas[0]["qthosp"];?>" type="number" placeholder="" class="form-control" maxlength = "2">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php $dtrese = traduz_data_para_exibir($aReservas[0]["dtrese"]);?>
                                                <label class="col-md-2 control-label" for="textinput">Data da Reserva</label>
                                                <div class="col-md-3">
                                                    <input id="dtrese" name="dtrese" value="<?php echo $dtrese ;?>" type="text" placeholder="" class="form-control" maxlength = "15" readonly="">
                                                </div>
                                                <label class="col-md-2 control-label" for="textinput">Data da Confirmação</label>
                                                <div class="col-md-3">
                                                    <input id="dtconf" name="dtconf" value="<?php echo $aReservas[0]["dtconf"];?>" type="date" placeholder="" class="form-control" maxlength = "15">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Entrada Prevista</label>
                                                <div class="col-md-3">
                                                    <input id="dtentp" name="dtentp" value="<?php echo $aReservas[0]["dtentp"];?>" type="date" placeholder="" class="form-control" maxlength = "10">
                                                </div>
                                                <label class="col-md-2 control-label" for="textinput">Saída Prevista</label>
                                                <div class="col-md-3">
                                                    <input id="dtsaip" name="dtsaip" value="<?php echo $aReservas[0]["dtsaip"];?>" type="date" placeholder="" class="form-control" maxlength = "10">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Entrada Efetiva</label>
                                                <div class="col-md-3">
                                                    <input id="dtentr" name="dtentr" value="<?php echo $aReservas[0]["dtentr"];?>" type="date" placeholder="" class="form-control" maxlength = "15">
                                                </div>
                                                <label class="col-md-2 control-label" for="textinput">Saída Efetiva</label>
                                                <div class="col-md-3">
                                                    <input id="dtsaid" name="dtsaid" value="<?php echo $aReservas[0]["dtsaid"];?>" type="date" placeholder="" class="form-control" maxlength = "15">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Valor da Reserva R$</label>
                                                <?php $vlrese = number_format($aReservas[0]["vlrese"],2,',','.') ; ?>
                                                <div class="col-md-3">
                                                    <input id="vlrese" name="vlrese" value="<?php echo $vlrese;?>" type="text" placeholder="0,00" class="form-control" maxlength = "18">
                                                </div>
                                                <label class="col-md-2 control-label" for="textinput">Valor do Consumo R$</label>
                                                <?php $vlcons = number_format($aReservas[0]["vlcons"],2,',','.') ; ?>
                                                <div class="col-md-3">
                                                    <input id="vlcons" name="vlcons" value="<?php echo $vlcons;?>" type="text" placeholder="0,00" class="form-control" maxlength = "18">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Antecipação de R$</label>
                                                <?php $vlante = number_format($aReservas[0]["vlante"],2,',','.') ; ?>
                                                <div class="col-md-2">
                                                    <input id="vlante" name="vlante" value="<?php echo $vlante;?>" type="text" placeholder="0,00" class="form-control" maxlength = "18">
                                                </div>
                                                <label class="col-md-3 control-label" for="textinput">Valor Pago R$</label>
                                                <?php if (is_null($aReservas[0]["vlpago"]) == true or $aReservas[0]["vlpago"] < 1.00) {?>
                                                    <?php $vlpago = 0.00; ?>
                                                    <?php $vlpago = number_format($vlpago,2,',','.') ; ?>
                                                <?php } Else {?>
                                                    <?php $vlpago = number_format($aReservas[0]["vlpago"],2,',','.') ; ?>                                                
                                                <?php }?>
                                                <div class="col-md-3">
                                                    <input id="vlpago" name="vlpago" value="<?php echo $vlpago;?>" type="text" placeholder="0,00" class="form-control" readonly = "" maxlength = "18">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tab-2" class="tab-pane">
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Acompanhantes</label>  
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="dehosp" wrap="physical" cols=50 rows=10 name="dehosp" placeholder=""><?php echo $aReservas[0]["dehosp"];?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tab-3" class="tab-pane">
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="textinput">Observações</label>  
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="deobse" wrap="physical" cols=50 rows=10 name="deobse" placeholder=""><?php echo $aReservas[0]["deobse"];?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>    


                                <div>
                                    <center>
                                        <?php $termo = "termoreservap.php?chave=".$chave ; ?>

                                        <button class="btn btn-sm btn-warning " type="button" onClick="history.go(-1)"><strong>Retornar</strong></button>

                                        <?php if($acao == "edita") {?>
                                            <button class="btn btn-sm btn-primary" name = "edita" type="submit"><strong>Alterar</strong></button>
                                        <?php }?>

                                        <?php if($acao == "apaga") {?>
                                            <button class="btn btn-sm btn-danger" name = "apaga" type="submit"><strong>Apagar</strong></button>
                                        <?php }?>

                                        <button class="btn btn-sm btn-success " type="button" onclick="window.open('<?php echo $termo;?>','_blank')"><strong>Contrato</strong></button>
                                    </center>
                                </div>

                            </form>
                    </div>
                </div>
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
        $(document).ready(prodtion() {
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

            prodtion gd(year, month, day) {
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
                        normalizeprodtion: 'polynomial'
                    }]
                },
            });
        });
    </script>
</body>
</html>