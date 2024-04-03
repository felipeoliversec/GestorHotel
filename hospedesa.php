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

    $aHospedes= ConsultarDados("hospedes", "cdhosp", $chave, false);
    $aEnderecos= ConsultarDados("enderecos", "cdende", $chave, false);
    $aTelefones= ConsultarDados("telefones", "cdtele", $chave, false);

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
                                                    class="fa fa-user-plus"></i> Cadastro de Hóspedes - <small><?php echo $titulo; ?></small>
                        </button>
                    </div>
                    <div class="ibox-content">
                        <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="meusdadosg.php"-->

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Dados Pessoais</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-home"></i> Endereço</a></li>
                                <li class=""><a data-toggle="tab" href="#tab-3"><i class="fa fa-phone"></i> Telefones</a></li>
                            </ul>

                            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="hospedesaa.php">

                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                    <br>
                                    <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="meusdadosg.php"-->
                                        <div class="row">
                                            <div class="col-lg-4 text-center">
                                                <h2><?php echo $aHospedes[0]["dehosp"]; ?></h2>
                                                <input type="hidden" name="defoto1" value = "<?php echo $aHospedes[0]["defoto"]; ?>">
                                                <div class="m-b-sm">
                                                    <img alt="image" class="img-circle" src="<?php echo $aHospedes[0]["defoto"]; ?>"
                                                         style="width: 82px">
                                                </div>
                                                <div class="m-b-sm">
                                                    <?php if (trim($acao) == "edita") {?>
                                                        <span><small><strong>Atenção:</strong> A nova foto somente será apresentada após a gravação!</small></span>
                                                    <?php }?>
                                                </div>
                                                <div class="m-b-sm">
                                                    <input type=hidden name="defoto" value="<?php echo $aHospedes[0]["defoto"]; ?>">
                                                    <label title="Incluir Foto" for="defoto" class="btn btn-success">
                                                        <input type="file" accept="img/*" name="defoto" id="defoto" class="hide">
                                                        Incluir foto
                                                    </label>
                                                    <!--label>"<?php echo $defoto; ?>"</label--> 
                                                    <!--input id="defoto"  value="<?php echo $defoto; ?>" name="defoto" class="input-file" type="file"-->
                                                    <!--span>"<?php echo $defoto; ?>"</span-->
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Cpf/Cnpj</label>
                                                    <div class="col-md-6 ">
                                                        <?php if (strlen($aHospedes[0]["cdhosp"]) < 15 ) { ?>
                                                            <input id="cdhosp" name="cdhosp" type="text" value="<?php echo formatar($aHospedes[0]["cdhosp"],"cpf"); ?>" placeholder="" class="form-control" maxlength = "20" autofocus readonly="">
                                                        <?php } Else {?>
                                                            <input id="cdhosp" name="cdhosp" type="text" value="<?php echo formatar($aHospedes[0]["cdhosp"],"cnpj"); ?>" placeholder="" class="form-control" maxlength = "20" autofocus readonly="">
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Nome</label>
                                                    <div class="col-md-10">
                                                        <input id="dehosp" name="dehosp" value="<?php echo $aHospedes[0]["dehosp"]; ?>" type="text" placeholder="" class="form-control" maxlength = "100" required="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">E-Mail</label>
                                                    <div class="col-md-10">
                                                        <input id="demail" name="demail" value="<?php echo $aHospedes[0]["demail"]; ?>" type="e-mail" placeholder="seuemail@provedor.com" class="form-control" maxlength = "255">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Última Estadia</label>
                                                    <div class="col-md-5">
                                                        <input id="dtulti" name="dtulti" value="<?php echo $aHospedes[0]["dtulti"]; ?>" type="date" placeholder="" class="form-control" maxlength = "10">
                                                    </div>
                                                </div>
                                                <?php $vlulti = number_format($aHospedes[0]["vlulti"],2,',','.') ;?>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="textinput">Último Pagamento</label>
                                                    <div class="col-md-4">
                                                        <input id="vlulti" name="vlulti" value="<?php echo $vlulti; ?>" type="text" placeholder="999,99" class="form-control" maxlength = "10">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="cdclie" value = "<?php echo $cdclie; ?>">

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="textinput">Ativo?</label>
                                                    <div class="col-md-6">
                                                        <?php if ($aHospedes[0]["flativ"] == "S") {?>
                                                            <select name="flativ" id="flativ">
                                                                <option selected= "selected">Sim</option>
                                                                <option>Não</option>
                                                            </select>
                                                        <?php } Else {?>
                                                            <select name="flativ" id="flativ">
                                                                <option>Sim</option>
                                                                <option selected= "selected">Não</option>
                                                            </select>
                                                        <?php }?>
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
                                                <input id="deende" name="deende" value="<?php echo $aEnderecos[0]["deende"]; ?>" type="text" placeholder="" class="form-control" maxlength = "100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Número</label>
                                            <div class="col-md-1">
                                                <input id="nrende" name="nrende" value="<?php echo $aEnderecos[0]["nrende"]; ?>" type="text" placeholder="Ex: 187" class="form-control" maxlength = "6">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Complemento</label>
                                            <div class="col-md-6">
                                                <input id="decomp" name="decomp" value="<?php echo $aEnderecos[0]["decomp"]; ?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Bairro</label>
                                            <div class="col-md-6">
                                                <input id="debair" name="debair" value="<?php echo $aEnderecos[0]["debair"]; ?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Cidade</label>
                                            <div class="col-md-6">
                                                <input id="decida" name="decida" value="<?php echo $aEnderecos[0]["decida"]; ?>" type="text" placeholder="São Paulo" class="form-control" maxlength = "50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Estado</label>
                                            <div class="col-md-1">
                                                <input id="cdesta" name="cdesta" value="<?php echo $aEnderecos[0]["cdesta"]; ?>" type="text" placeholder="SP" class="form-control" maxlength = "02">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Cep</label>
                                            <div class="col-md-2">
                                                <input id="nrcep" name="nrcep" value="<?php echo $aEnderecos[0]["nrcep"]; ?>" type="text" placeholder="67876020" class="form-control" maxlength = "08">
                                            </div>
                                        </div>

                                    </div>
                                    <div id="tab-3" class="tab-pane">
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Telefone</label>
                                            <div class="col-md-1">
                                                <input id="nrdddf" name="nrdddf" value="<?php echo $aTelefones[0]["nrdddf"]; ?>" type="text" placeholder="12" class="form-control" maxlength = "04">
                                            </div>
                                            <div class="col-md-4">
                                                <input id="nrfixo" name="nrfixo" value="<?php echo $aTelefones[0]["nrfixo"]; ?>" type="text" placeholder="12345678" class="form-control" maxlength = "08">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="textinput">Celular</label>
                                            <div class="col-md-1">
                                                <input id="nrdddc" name="nrdddc" value="<?php echo $aTelefones[0]["nrdddc"]; ?>" type="text" placeholder="12" class="form-control" maxlength = "04">
                                            </div>
                                            <div class="col-md-4">
                                                <input id="nrcelu" name="nrcelu" value="<?php echo $aTelefones[0]["nrcelu"]; ?>" type="text" placeholder="123456789" class="form-control" maxlength = "09">
                                            </div>
                                        </div>
                                    </div>
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