<?php

function ExcluirDados($tabela, $campo, $chave, $like=false, $cdclie="0") {
    include "conexao.php";

    $sql = "delete from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'";
    if ($like == true) {
        $sql = "delete from "."{$tabela}"." where "."{$campo}"." like "."'{$chave}%'";
    } 
    if ($cdclie !== "0") {
        $sql = "delete from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'"." and cdclie = "."'{$cdclie}'";
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function ConsultarDados($tabela, $campo, $chave, $like=false,$tudo=false, $cdclie="0") {
    include "conexao.php";

    $sql = "select * from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'";
    if ($like == true) {
        $sql = "select * from "."{$tabela}"." where "."{$campo}"." like "."'{$chave}%'";
    }
    if ($tudo == true) {
        $sql = "select * from "."{$tabela}"." order by "."{$campo}";
    }
    if ($cdclie !== "0") {
        if (empty($chave) == true) {
            $sql = "select * from "."{$tabela}"." where cdclie = "."'{$cdclie}'";
        } Else {
            $sql = "select * from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'"." and cdclie = "."'{$cdclie}'";
        }
    }

    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function ConsultarDadosQ($tabela, $campo, $chave, $like=false,$tudo=false, $cdclie="0") {
    include "conexao.php";

    $sql = "select * from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'";
    if ($like == true) {
        $sql = "select * from "."{$tabela}"." where "."{$campo}"." like "."'{$chave}%'";
    }
    if ($tudo == true) {
        $sql = "select * from "."{$tabela}"." order by "."{$campo}";
    }
    if ($cdclie !== "0") {
        $sql = "select * from "."{$tabela}"." where "."{$campo}"." = "."'{$chave}'"." and cdclie = "."'{$cdclie}'"." Order by dtentp";
    }

    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function GravarDados($tabela, $dados, $nomes=null, $tipo="G",$campo=null,$chave=null, $cdclie="0") {
    include "conexao.php";

    $sql="insert into "."{$tabela}"." values (";
    $campos="";
    $total=count($dados)-1;

    if ($tipo == "A") {       
        $sql="update "."{$tabela}"." set ";
    }

    for ($i =0 ; $i < count($dados) ; $i++ ) {

        if ($tipo == "A") {
            $campos=$campos.$nomes[$i]." = '".$dados[$i]."'";
        } Else {
            $campos=$campos."'".$dados[$i]."'";
        }
    
        if ($i < $total) {
            $campos=$campos.", ";
        }

    }
    
    if ($tipo == "A") {
        //$campos=$campos;
        if ($cdclie !== "0") {
            $sql=$sql.$campos." where  ".$campo." = "."'{$chave}'"." and cdclie = "."'{$cdclie}'";
        } Else {
            $sql=$sql.$campos." where  ".$campo." = "."'{$chave}'";
        }
    } Else {
        $campos=$campos." )";
        $sql=$sql.$campos;
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function ConfirmarSenha($cdusua, $desenh, $tudo="true") {
    include "conexao.php";

    // Flativ = "A" == perfil de administrador
    // Flativ = "C" == perfil de condômino
    // Flativ = "N" == perfil inativo

    $sql="select * from usuarios where cdusua = "."'{$cdusua}'"." and desenh = "."'{$desenh}'"." and flativ = 'S'";

    if ($tudo == true) {
        // traz independente de estar ativo ou não quando necessário
        $sql="select * from usuarios where cdusua = "."'{$cdusua}'"." and desenh = "."'{$desenh}'";
    }
    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function GravarNovaSenha($demail,$desenh) {
    include "conexao.php";

    $sql="update usuarios set desenh = "."'{$desenh}'"." where demail = "."'{$demail}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function AtualizaEstoques($cdclie, $cdprod, $qtprod, $cdtipo) {
    include "conexao.php";

    if ($cdtipo == 'E') {
        $sql="update produtos set qtprod = qtprod + "."'{$qtprod}'"." where cdclie = "."'{$cdclie}'"." and cdprod = "."'{$cdprod}'";
    } Else {
        $sql="update produtos set qtprod = qtprod - "."'{$qtprod}'"." where cdclie = "."'{$cdclie}'"." and cdprod = "."'{$cdprod}'";
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function GravarReservas($aDados) {
    include "conexao.php";

    $sql = "
        INSERT INTO reservas
        ( cdclie, cdhosp, cdloca, qthosp, dehosp, dtrese, dtentp, dtsaip, vlante, deobse, cdusua )
        VALUES
        (
            '{$aDados[0]}',
            '{$aDados[1]}',
            '{$aDados[2]}',
            '{$aDados[3]}',
            '{$aDados[4]}',
            '{$aDados[5]}',
            '{$aDados[6]}',
            '{$aDados[7]}',
            '{$aDados[8]}',
            '{$aDados[9]}',
            '{$aDados[10]}'
        )
    ";

    mysqli_query($conexao, $sql);

    $cdclie=$aDados[0];
    $cdhosp=$aDados[1];
    $cdloca=$aDados[2];
    $cdusua=$aDados[10];
    $qthosp=$aDados[3];  
    $qtcons=date("d",strtotime($aDados[7])-strtotime($aDados[6]));
    $cdrese=0;
    $datahoje=date('Y-m-d H:i:s');

    $sql = "select cdrese from reservas where cdclie = "."'{$cdclie}'"." and cdhosp = "."'{$cdhosp}'"." and cdloca = "."'{$cdloca}'"." and cdusua = "."'{$cdusua}'";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $cdrese=$linha["cdrese"]; 
        }
    }
    mysqli_close($conexao);

    // na inclusão da reserva cria-se um consumo de serviço de diárias computando o periodo que ficará hospedado
    $aServ=ConsultarDados("servicos", "cdserv", "001", false, false, $cdclie);
    $preco = $aServ[0]["vlserv"];
    $vlprec = $qthosp * $preco;
    $vlprec = $vlprec * $qtcons;

    //campos da tabela
    $aNomes=array();
    $aNomes[]= "flcons";
    $aNomes[]= "cdclie";
    $aNomes[]= "cdrese";
    $aNomes[]= "cdcons";
    $aNomes[]= "dtcons";
    $aNomes[]= "qtcons";
    $aNomes[]= "vlcons";
    $aNomes[]= "vlprec";
    $aNomes[]= "flativ";

    // armazena em array
    $aDados=array();
    $aDados[]= "S";
    $aDados[]= $cdclie;
    $aDados[]= $cdrese;
    $aDados[]= "001";
    $aDados[]= $datahoje;
    $aDados[]= $qtcons;
    $aDados[]= $vlprec;
    $aDados[]= $preco;
    $aDados[]= "S";

    GravarConsumo($aDados);
    AtualizarConsumoReserva($cdrese, $cdclie);

    return ($cdrese);
}

function VerificaReserva($cdclie, $cdloca, $dtentp) {
    include "conexao.php";

    $flag="";

    $sql = "select cdrese from reservas where cdclie = "."'{$cdclie}'"." and cdloca = "."'{$cdloca}'"." and "."'{$dtentp}'"." between dtentp and dtsaip";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $flag=$linha["cdrese"];
        }
    }

    mysqli_close($conexao);
    return ($flag);
}


function AtualizarReservas($aDados) {
    include "conexao.php";

    $sql = "Update reservas Set
            cdclie='{$aDados[1]}',
            cdhosp='{$aDados[2]}',
            cdloca='{$aDados[3]}',
            qthosp='{$aDados[4]}',
            dehosp='{$aDados[5]}',
            dtentp='{$aDados[6]}',
            dtsaip='{$aDados[7]}',
            dtconf='{$aDados[8]}',
            dtentr='{$aDados[9]}',
            dtsaid='{$aDados[10]}',
            vlrese='{$aDados[11]}',
            vlcons='{$aDados[12]}',
            vlante='{$aDados[13]}',
            cdusua='{$aDados[14]}',
            deobse='{$aDados[15]}',
            vlpago='{$aDados[16]}'
                    Where cdrese = '{$aDados[0]}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function TrazReservadoDia($cdclie, $cdloca) {
    include "conexao.php";
    $datahoje=date('Y-m-d');

    $sql = "select * from reservas where cdclie = "."'{$cdclie}'"." and cdloca = "."'{$cdloca}'". " and "."'{$datahoje}'"." between dtentp and dtsaip";
    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function VerificaPagamento($cdclie, $cdrese) {
    include "conexao.php";

    $sql = "select * from reservas where cdclie = "."'{$cdclie}'"." and cdrese = "."'{$cdrese}'";
    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function LiberarQuarto($cdclie, $cdloca) {
    include "conexao.php";

    $sql = "update locais set fllibe = 'S' where cdloca = "."'{$cdloca}'"." and cdclie = "."'{$cdclie}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return;
}

function OcuparQuarto($cdclie, $cdloca) {
    include "conexao.php";

    $sql = "Update locais Set fllibe = 'N' Where cdloca = "."'{$cdloca}'"." and cdclie = "."'{$cdclie}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return;
}

function TrazReservas($cdclie) {
    include "conexao.php";

    $sql = "select * from reservas where cdclie = "."'{$cdclie}'"." and dtentr <> '0000-00-00'";
    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return ($aDados);
}

function BuscarQuantidadeEntradas($cdclie) {
    include "conexao.php";
    $datahoje=date('Y-m-d');
    $qtde = 0;
    $sql = "select count(*) qtde from reservas where cdclie = "."'{$cdclie}'"." and dtentr = "."'{$datahoje}'"." and vlpago < 1";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $qtde=$linha["qtde"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($qtde);

}

function BuscarQuantidadeSaidas($cdclie) {
    include "conexao.php";
    $datahoje=date('Y-m-d');
    $qtde = 0;
    $sql = "select count(*) qtde from reservas where cdclie = "."'{$cdclie}'"." and dtsaid = "."'{$datahoje}'"." and vlpago < 1";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $qtde=$linha["qtde"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($qtde);

}

function GravarConsumo($aDados) {
    include "conexao.php";

    $sql = "
        INSERT INTO consumo
        ( flcons,cdclie, cdrese, cdcons, dtcons, qtcons, vlcons, vlprec, flativ  )
        VALUES
        (
            '{$aDados[0]}',
            '{$aDados[1]}',
            '{$aDados[2]}',
            '{$aDados[3]}',
            '{$aDados[4]}',
            '{$aDados[5]}',
            '{$aDados[6]}',
            '{$aDados[7]}',
            '{$aDados[8]}'
        )
    ";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}

function AtualizarConsumo($aDados) {
    include "conexao.php";

    $sql = "Update consumo Set
            qtcons='{$aDados[3]}',
            vlcons='{$aDados[4]}',
            vlprec='{$aDados[5]}'
                    Where nrsequ = '{$aDados[0]}'"." and cdclie ="."'{$aDados[1]}'"." and cdrese = "."'{$aDados[2]}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}


function AtualizarConsumoReserva($cdrese, $cdclie) {
    include "conexao.php";
    $vlprod=0.00;
    $vlserv=0.00;

    // produtos
    $sql = "select sum(vlcons) vlprod from consumo where cdclie = "."'{$cdclie}'"." and cdrese = "."'{$cdrese}'"." and flcons = 'P'";
    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vlprod=$linha["vlprod"];
        }
    }

    // serviços
    $sql = "select sum(vlcons) vlserv from consumo where cdclie = "."'{$cdclie}'"." and cdrese = "."'{$cdrese}'"." and flcons = 'S'";
    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vlserv=$linha["vlserv"];
        }
    }

    //atualiza reserva
    $sql = "Update reservas Set vlrese = "."'{$vlserv}'".", vlcons = "."'{$vlprod}'"." Where cdrese = "."'{$cdrese}'"." and cdclie = "."'{$cdclie}'";
    mysqli_query($conexao, $sql);

    // fecha as conexões
    mysqli_close($conexao);
    return;

}

function BuscarQuantidadeHospedes($cdclie) {
    include "conexao.php";
    $datahoje=date('Y-m-d');
    $qtde = 0;
    $sql = "select sum(qthosp) qtde from reservas where cdclie = "."'{$cdclie}'"." and dtentr = "."'{$datahoje}'";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $qtde=$linha["qtde"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($qtde);

}

function BuscarQuantidadeQuartos($cdclie, $cdtipo) {
    $datahoje=date('Y-m-d');
    $qtde = 0;
    $alocal=ConsultarDados("locais", "cdclie", $cdclie, false, false);

    for ($i =0 ; $i < count($alocal) ; $i++ ) {
        if ($alocal[$i]["fllibe"] == $cdtipo) {
            $qtde++;
        }
    }
    return ($qtde);
}

function LotacaoPrevistaV($cdclie,$cdtipo) {
    include "conexao.php";
    if ($cdtipo == "D") {
        $sql = "select day(dtentp), sum(vlrese+vlcons+vlante) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and day(dtentp) = day(CURRENT_DATE) GROUP BY day(dtentp)";
    }

    if ($cdtipo == "M") {
        $sql = "select month(dtentp), sum(vlrese+vlcons+vlante) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and month(dtentp) = month(CURRENT_DATE) GROUP BY month(dtentp)";
    }

    if ($cdtipo == "A") {
        $sql = "select year(dtentp), sum(vlrese+vlcons+vlante) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and year(dtentp) = year(CURRENT_DATE) GROUP BY year(dtentp)";
    }

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}

function LotacaoRealizadaV($cdclie,$cdtipo) {
    include "conexao.php";
    if ($cdtipo == "D") {
        $sql = "select day(dtentr), sum(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and day(dtentr) = day(CURRENT_DATE) GROUP BY day(dtentr)";
    }

    if ($cdtipo == "M") {
        $sql = "select month(dtentr), sum(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and month(dtentr) = month(CURRENT_DATE) GROUP BY month(dtentr)";
    }

    if ($cdtipo == "A") {
        $sql = "select year(dtentr), sum(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and year(dtentr) = year(CURRENT_DATE) GROUP BY year(dtentr)";
    }

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}


function LotacaoPrevistaQ($cdclie,$cdtipo) {
    include "conexao.php";
    if ($cdtipo == "D") {
        $sql = "select day(dtentp), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and day(dtentp) = day(CURRENT_DATE) GROUP BY day(dtentp)";
    }

    if ($cdtipo == "M") {
        $sql = "select month(dtentp), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and month(dtentp) = month(CURRENT_DATE) GROUP BY month(dtentp)";
    }

    if ($cdtipo == "A") {
        $sql = "select year(dtentp), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and year(dtentp) = year(CURRENT_DATE) GROUP BY year(dtentp)";
    }

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}

function LotacaoRealizadaQ($cdclie,$cdtipo) {
    include "conexao.php";
    if ($cdtipo == "D") {
        $sql = "select day(dtentr), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and day(dtentr) = day(CURRENT_DATE) GROUP BY day(dtentr)";
    }

    if ($cdtipo == "M") {
        $sql = "select month(dtentr), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and month(dtentr) = month(CURRENT_DATE) GROUP BY month(dtentr)";
    }

    if ($cdtipo == "A") {
        $sql = "select year(dtentr), count(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and year(dtentr) = year(CURRENT_DATE) GROUP BY year(dtentr)";
    }

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}

function VerificaQuarto($cdclie, $cdloca) {
    include "conexao.php";
    $livre = "N";
    $datahoje=date('Y-m-d');
    $sql = "select cdloca from locais where cdclie = "."'{$cdclie}'"." and cdloca = "."'{$cdloca}'"." and fllibe = 'S'";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $livre="S";
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return($livre);
}

function VerificaReservaEntrou($cdclie, $cdloca, $dtentp) {
    include "conexao.php";

    $flag="N";

    $sql = "select cdrese from reservas where cdclie = "."'{$cdclie}'"." and cdloca = "."'{$cdloca}'"." and "."'{$dtentp}'"." between dtentp and dtsaip and dtentr is not null and dtentr != '0000-00-00'";

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $flag="S";
        }
    }

    mysqli_close($conexao);
    return ($flag);
}

function LotacaoRealizada($cdclie, $cdmes) {
    include "conexao.php";
    $sql = "select month(dtentr), sum(vlpago) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentr is not null and dtentr != '0000-00-00' and month(dtentr) = "."'{$cdmes}'"." and year(dtentr) = year(CURRENT_DATE) GROUP BY month(dtentr)";

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}

function LotacaoPrevista($cdclie, $cdmes) {
    include "conexao.php";
    $sql = "select month(dtentp), sum(vlante + vlcons + vlrese) vl from reservas where cdclie = "."'{$cdclie}'"." and dtentp is not null and dtentp != '0000-00-00' and month(dtentp) = "."'{$cdmes}'"." and year(dtentp) = year(CURRENT_DATE) GROUP BY month(dtentp)";

    $vl=0.00;

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $vl=$linha["vl"];
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($vl);

}

function AtualizarHospedesH($aDados) {
    include "conexao.php";
    $aDados[9]=date("Y-m-d");

    $sql = "Update hospedes Set
            dtulti='{$aDados[9]}',
            vlulti='{$aDados[16]}'
                    Where cdclie = '{$aDados[1]}'"." and cdhosp = "."'{$aDados[2]}'";

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);
    return;
}


function ConsultarLocal ($cdclie, $deini, $defim) {
    include "conexao.php";
    
    $sql = "select * from locais where cdclie = "."'{$cdclie}'"." and CAST(cdloca AS UNSIGNED) BETWEEN "."{$deini}"." and "."{$defim}";

    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($aDados);
}

function ConsultarReservasAgenda($cdclie="") {
    include "conexao.php";
    
    $sql = "select a.cdrese, a.cdloca, a.cdhosp, a.dtentp, a.dtsaip, b.deusua, c.deloca from reservas a, usuarios b, locais c where a.cdloca = c.cdloca and a.cdhosp = b.cdusua order by a.dtentp, a.cdloca, a.cdhosp";

    $aDados=array();

    $resultado=mysqli_query($conexao, $sql);
    if ($resultado) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $aDados[]=$linha;
        }
    }

    mysqli_query($conexao, $sql);
    mysqli_close($conexao);

    return ($aDados);
}


?>