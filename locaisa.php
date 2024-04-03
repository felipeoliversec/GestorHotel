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
    default:
        header('Location: usuarios.php');
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

    $aDados= ConsultarDados("clientes", "cdclie", $cdclie, false);
    $declie= trim($aDados[0]["declie"]);

    $alocais= ConsultarDados("locais", "cdloca", $chave, false, false,$cdclie);

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
                                                    class="fa fa-hotel"></i> Cadastro de Acomodações - <small><?php echo $titulo; ?></small>
                        </button>
                    </div>
                    <div class="ibox-content">
                        <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="meusdadosg.php"-->
                            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="locaisaa.php">
                                <div class="row">
                                    <!--div class="col-lg-6"-->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Código</label>
                                            <div class="col-md-1">
                                                <input id="cdloca" name="cdloca" type="text" value="<?php echo $alocais[0]["cdloca"] ;?>" placeholder="" class="form-control" maxlength = "03" autofocus required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Descrição</label>
                                            <div class="col-md-8">
                                                <input id="deloca" name="deloca" value="<?php echo $alocais[0]["deloca"] ;?>" type="text" placeholder="" class="form-control" maxlength = "50" required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Andar</label>
                                            <div class="col-md-1">
                                                <input id="nranda" name="nranda" value="<?php echo $alocais[0]["nranda"] ;?>" type="text" placeholder="" class="form-control" maxlength = "02">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Ramal</label>
                                            <div class="col-md-2">
                                                <input id="nrrama" name="nrrama" value="<?php echo $alocais[0]["nrrama"] ;?>" type="text" placeholder="" class="form-control" maxlength = "20">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Capacidade</label>
                                            <div class="col-md-1">
                                                <input id="qthosp" name="qthosp" value="<?php echo $alocais[0]["qthosp"] ;?>" type="text" placeholder="" class="form-control" maxlength = "02">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Tipo</label>
                                            <div class="col-md-6">
                                                <?php if ($alocais[0]["cdtipo"] == "C") {?>
                                                    <select name="cdtipo" id="cdtipo">
                                                        <option selected= "selected">Casal</option>
                                                        <option>Solteiro</option>
                                                    </select>
                                                <?php } Else {?>
                                                    <select name="cdtipo" id="cdtipo">
                                                        <option>Casal</option>
                                                        <option selected= "selected">Solteiro</option>
                                                    </select>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textarea">Observações</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="deobse" wrap="physical" cols=50 rows=3 name="deobse" placeholder=""><?php echo $alocais[0]["deobse"] ;?></textarea>
                                            </div>
                                        </div>

                                    <!--/div-->
                                </div>


                                <div>
                                    <center>
                                        <button class="btn btn-sm btn-warning " type="button" onClick="history.go(-1)"><strong>Retornar</strong></button>
                                        <?php if($acao == "edita") {?>
                                            <button class="btn btn-sm btn-primary" name = "edita" type="submit"><strong>Alterar</strong></button>
                                        <?php }?>
                                        <?php if($acao == "apaga") {?>
                                            <button class="btn btn-sm btn-danger" name = "apaga" type="submit"><strong>Apagar</strong></button>
                                        <?php }?>
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