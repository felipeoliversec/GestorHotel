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

    // verifica se existe ao menos um serviço
    $aServ = ConsultarDados("servicos", "cdclie", $cdclie, false);
    if (count($aServ) < 1){
        $demens = "Não existe nenhum serviço cadastrado. Cadastre para ser possivel calcular as receitas!";
        $detitu = "Weber Hotel&copy; | Receitas";
        $devolt = "index1c.php";
        if ($fladmi == "A") {
            $devolt = "index1a.php";
        }
        header('Location: mensagem.php?demens='.$demens.'&detitu='.$detitu.'&devolt='.$devolt);
        die();
    }

    // reduzir o tamanho do nome do usuario
    $deusua = substr($deusua, 0,15);

    //reduzir o tamanho do nome do cliente
    $declie = substr($declie, 0, 50);

    //buscar quantidade de mensagens
    $qtentra= BuscarQuantidadeEntradas($cdusua, $cdclie);

    //buscar quantidade de alertas
    $qtsaida= BuscarQuantidadeSaidas($cdusua, $cdclie);

    //quantidade de hospedes hoje 
    $qthospedes = BuscarQuantidadeHospedes($cdclie);

    //quantidade de quartos ocupados
    $qtquartoso = BuscarQuantidadeQuartos($cdclie,"N");

    //quantidade de quartos vazios
    $qtquartosv = BuscarQuantidadeQuartos($cdclie,"S");

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
 
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!--h4>Meus Dados - <small>Atualização</small></h4-->
                                <button type="button" class="btn btn-primary btn-lg btn-block"><i
                                                            class="fa fa-money"></i> Receitas
                                </button>
                            </div>
                            <br>
                            <div class="col-md-4">
                                <div class="widget style1 navy-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-user fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Quantidade Hospedes </br> <strong>Hoje</strong> </span>
                                            <h2 class="font-bold"><?php echo $qthospedes;?></h2>
                                        </div>
                                    </div>
                                </div>                              
                            </div>        

                            <div class="col-md-4">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-bed fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Acomodações Ocupadas </br><strong>Hoje</strong></span>
                                            <h2 class="font-bold"><?php echo $qtquartoso;?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget style1 yellow-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-home fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Acomodações Vagas </br><strong>Hoje</strong></span>
                                            <h2 class="font-bold"><?php echo $qtquartosv;?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Prevista - Dia</h3>
                                    </div>
                                    <?php $vlp=LotacaoPrevistaV($cdclie,"D") ;?>
                                    <?php $vlq=LotacaoPrevistaQ($cdclie,"D") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Prevista - Mês</h3>
                                    </div>
                                    <?php $vlp=LotacaoPrevistaV($cdclie,"M") ;?>
                                    <?php $vlq=LotacaoPrevistaQ($cdclie,"M") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Prevista - Ano</h3>
                                    </div>
                                    <?php $vlp=LotacaoPrevistaV($cdclie,"A") ;?>
                                    <?php $vlq=LotacaoPrevistaQ($cdclie,"A") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Realizada - Dia</h3>
                                    </div>
                                    <?php $vlp=LotacaoRealizadaV($cdclie,"D") ;?>
                                    <?php $vlq=LotacaoRealizadaQ($cdclie,"D") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Realizada - Mês</h3>
                                    </div>
                                    <?php $vlp=LotacaoRealizadaV($cdclie,"M") ;?>
                                    <?php $vlq=LotacaoRealizadaQ($cdclie,"M") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3>Lotação Realizada - Ano</h3>
                                    </div>
                                    <?php $vlp=LotacaoRealizadaV($cdclie,"A") ;?>
                                    <?php $vlq=LotacaoRealizadaQ($cdclie,"A") ;?>
                                    <div class="ibox-content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quantidade</th>
                                                    <th>Valor R$</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo number_format($vlq,0,',','.'); ?></td>
                                                    <td><?php echo number_format($vlp,2,',','.'); ?></td>
                                                </tr>
                                            </tbody>    
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="ibox-content">
                                    <?php $vlp = LotacaoPrevista($cdclie, 1); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 1); ?>
                                    <span class="label label-primary">Previsto</span>
                                    <span class="label label-success">Realizado</span>
                                    <br>
                                    <br>
                                    <div>
                                        <div>
                                            <span><strong>Janeiro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-primary"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 2); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 2); ?>
                                    <div>
                                        <div>
                                            <span><strong>Fevereiro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-primary"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 3); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 3); ?>
                                    <div>
                                        <div>
                                            <span><strong>Março</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-primary"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 4); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 4); ?>
                                    <div>
                                        <div>
                                            <span><strong>Abril</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-primary"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ibox-content">
                                    <?php $vlp = LotacaoPrevista($cdclie, 5); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 5); ?>
                                    <span class="label label-warning">Previsto</span>
                                    <span class="label label-info">Realizado</span>
                                    <br>
                                    <br>
                                    <div>
                                        <div>
                                            <span><strong>Maio</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-warning"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 6); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 6); ?>
                                    <div>
                                        <div>
                                            <span><strong>Junho</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-warning"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 7); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 7); ?>
                                    <div>
                                        <div>
                                            <span><strong>Julho</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-warning"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 8); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 8); ?>
                                    <div>
                                        <div>
                                            <span><strong>Agosto</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-warning"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ibox-content">
                                    <?php $vlp = LotacaoPrevista($cdclie, 9); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 9); ?>
                                    <span class="label label-success">Previsto</span>
                                    <span class="label label-info">Realizado</span>
                                    <br>
                                    <br>
                                    <div>
                                        <div>
                                            <span><strong>Setembro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 10); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 10); ?>
                                    <div>
                                        <div>
                                            <span><strong>Outubro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 11); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 11); ?>
                                    <div>
                                        <div>
                                            <span><strong>Novembro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>
                                    <?php $vlp = LotacaoPrevista($cdclie, 12); ?>
                                    <?php $vlr = LotacaoRealizada($cdclie, 12); ?>
                                    <div>
                                        <div>
                                            <span><strong>Dezembro</strong></span>
                                            <small class="pull-right">R$</small>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-success"><strong><?php echo number_format($vlp,2,',','.'); ?></strong></div>
                                        </div>
                                        <div class="progress progress-medium">
                                            <div style="width: 100%;" class="progress-bar progress-bar-info"><strong><?php echo number_format($vlr,2,',','.'); ?></strong></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                     
                    </div>                  
                </div>    
                <br>
                <div class="footer">
                <div class="pull-right">
                Weber<strong>Hotel</strong>
                </div>
                <div>
                    <strong>Copyright</strong> Weber Hotel&copy; 2018
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

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>
    <script src="js/demo/chartjs-demo.js"></script>

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
