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

    $aReservas= TrazReservas($cdclie);

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
                        Weber<strong>Hotel</strong>
                    </div>
                </li>

                <?php if ($fladmi == "A") {?>
                    <li class="active">
                        <a href="index1a.php"><i class="fa fa-home"></i> <span class="nav-label">Principal</span></a>
                    </li>
                <?php } Else {?>
                    <li class="active">
                        <a href="index1c.php"><i class="fa fa-home"></i> <span class="nav-label">Principal</span></a>
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

            <!--div class="col-lg-12"-->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <!--h4>Meus Dados - <small>Atualização</small></h4-->
                        <button type="button" class="btn btn-primary btn-lg btn-block"><i
                                                    class="fa fa-hotel"></i> Hóspede - Registrar Consumo
                        </button>
                    </div>
                    <div class="ibox-content">
                        <!--form class="form-horizontal" method="POST" enctype="multipart/form-data" action="usuariosa.php"-->
                            <!--div class="pull-left"-->
                                <!--a onclick="#" href="consumoi.php" class="btn btn-primary ">Incluir</a-->
                            <!--/div-->
                            <!--div class="pull-right"-->
                                <!--span class="label label-primary">Sai Hoje. Pagamento Efetuado.&nbsp;</span-->
                                <!--span class="label label-danger">Sai Hoje. Pagamento Pendente.</span-->
                                <!--span class="label label-warning">Sai outro dia.</span-->
                                <!--span class="label label-success">Não está hospedado.</span-->
                            <!--/div-->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th>Código da Reserva</th>
                                            <th>Hóspede</th>
                                            <th>Quarto/Suíte</th>
                                            <th>Serviços</th>
                                            <th>Produtos</th>
                                            <th>Total</th>
                                            <th class="text-right" data-sort-ignore="true">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($f =0; $f <= (count($aReservas)-1); $f++) { ?>
                                            <tr class="gradeX">

                                                <?php $dataP = strtotime($aReservas[$f]["dtentp"]) ;?>
                                               
                                                <?php $aHosp= ConsultarDados("hospedes", "cdhosp", $aReservas[$f]["cdhosp"], false, false,$cdclie); ?>
                                                <?php If (count($aHosp) > 0) {?>
                                                    <?php $dehosp = substr($aHosp[0]["dehosp"], 0,25); ?>
                                                <?php } Else {?>
                                                    <?php $dehosp = "Nome Desconhecido" ;?>
                                                <?php }?>

                                                <?php $coluna1 = $aReservas[$f]["cdrese"]; ?>
                                                <?php $coluna2 = $aReservas[$f]["cdhosp"]." - ".$dehosp; ?>
                                                <?php $coluna3 = $aReservas[$f]["cdloca"]; ?>
                                                <?php $coluna4 = number_format($aReservas[$f]["vlrese"],2,',','.'); ?>
                                                <?php $coluna5 = number_format($aReservas[$f]["vlcons"],2,',','.'); ?>
                                                <?php $coluna6 = number_format($aReservas[$f]["vlrese"]+$aReservas[$f]["vlcons"],2,',','.'); ?>

                                                <?php $ver = "reservasa.php?acao=ver&chave=".$coluna1; ?>
                                                <?php $incluir = "consumoi.php?acao=incluir&chave=".$coluna1; ?>
                                                <?php $apaga = "reservasa.php?acao=apaga&chave=".$coluna1; ?>
                                                <?php $cupom = "consumop.php?acao=cupom&chave=".$coluna1; ?>

                                                <td><?php print $coluna1; ?></td>
                                                <td><?php print $coluna2; ?></td>
                                                <td><?php print $coluna3; ?></td>
                                                <td class="center"><?php print $coluna4; ?></td>
                                                <td class="center"><?php print $coluna5; ?></td>
                                                <td class="center"><?php print $coluna6; ?></td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <!--button class="fa fa-eye btn-white btn btn-xs" name="ver" type="button" onclick="window.open('<?php echo $ver;?>','_parent')"></button-->

                                                        <?php if ($aReservas[$f]["dtentr"] !== "0000-00-00" and $aReservas[$f]["vlpago"] < 1.00) {?>
                                                            <button class="fa fa-shopping-cart btn-white btn btn-xs" name="edita" type="button" onclick="window.open('<?php echo $incluir;?>','_parent')">Consumir</button>
                                                        <?php }?>
                                                        <button class="fa fa-list btn-white btn btn-xs" name="cupom" type="button" onclick="window.open('<?php echo $cupom;?>','_parent')">Cupom</button>

                                                        <!--button class="fa fa-trash btn-white btn btn-xs" name="apaga" type="button" onclick="window.open('<?php echo $apaga;?>','_parent')"></button-->
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }; ?>    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Código da Reserva</th>
                                            <th>Hóspede</th>
                                            <th>Quarto/Suíte</th>
                                            <th>Serviços</th>
                                            <th>Produtos</th>
                                            <th>Total</th>
                                            <th class="text-right" data-sort-ignore="true">Ação</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <caption><strong>* AJUDA</strong></caption>
                                    <thead>
                                        <tr>
                                            <th width=5>Comando</th>
                                            <th width=100>Descrição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td >Show</td>
                                            <td >Controla a quantidade de linhas a serem apresentadas na tabela.</td>
                                        </tr>
                                        <tr>
                                            <td >entriesSearch</td>
                                            <td >É a pesquisa. Apresenta os dados filtrados conforme o conteúdo digitado.</td>
                                        </tr>
                                        <tr>
                                            <td >Copy</td>
                                            <td >Copia o conteúdo da tabela para a memória (clipboard).</td>
                                        </tr>
                                        <tr>
                                            <td >CSV</td>
                                            <td >Exporta os dados da tabela para um arquivo no formato CSV (arquivo texto com as informações separadas por vírgula).</td>
                                        </tr>
                                        <tr>
                                            <td >Excel</td>
                                            <td >Exporta os dados da tabela para um arquivo no formato EXCEL.</td>
                                        </tr>
                                        <tr>
                                            <td >PDF</td>
                                            <td >Exporta os dados da tabela para um arquivo no formato PDF.</td>
                                        </tr>
                                        <tr>
                                            <td >Print</td>
                                            <td >Imprime os dados da tabela.</td>
                                        </tr>
                                        <tr>
                                            <td >Previous</td>
                                            <td >Retorna uma página da tabela.</td>
                                        </tr>
                                        <tr>
                                            <td >Next</td>
                                            <td >Avança uma página da tabela.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <!--/form-->
                    </div>                    
               </div>
            <!--/div-->
        </div>
        <div class="footer">
            <div class="pull-right">
                Weber<strong>Hotel</strong>
            </div>
            <div>
                <strong>Copyright</strong> Weber Hotel&copy; 2018
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="js/plugins/jeditable/jquery.jeditable.js"></script>
    <script src="js/plugins/dataTables/datatables.min.js"></script>

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

        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

            /* Init DataTables */
            var oTable = $('#editable').DataTable();

            /* Apply the jEditable handlers to the table */
            oTable.$('td').editable( '../example_ajax.php', {
                "callback": function( sValue, y ) {
                    var aPos = oTable.fnGetPosition( this );
                    oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                },
                "submitdata": function ( value, settings ) {
                    return {
                        "row_id": this.parentNode.getAttribute('id'),
                        "column": oTable.fnGetPosition( this )[2]
                    };
                },

                "width": "90%",
                "height": "100%"
            } );


        });

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
