<?php

    $detitu=trim($_GET['detitu']);
    $demens=trim($_GET['demens']);
    $devolt="";
    if (isset($_GET['devolt'])) {
       $devolt=trim($_GET['devolt']);
       $devolt="window.open('"."{$devolt}"."','_parent')";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Weber Hotel&copy; | Mensagem</title>

    <link href="css/normalize.css" rel="stylesheet" >

    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link href="email_templates/styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr align = center>
                        <td class="alert alert-good">
                            <strong><?php echo $detitu; ?></strong>
                        </td>
                    </tr>
                </table>
                <div>
                    <p>
                        <center> <strong>Atenção : </strong><?php echo $demens; ?> </center>     
                    </p>                    
                </div>
                <div>

                    <?php if (empty($devolt) == true) {?>
                        <center>  <button id="button2id" name="button2id" class="btn btn-warning" type="button"  onClick="history.go(-1)">Retornar </center> 
                    <?php } Else {?>
                       <center>  <button id="button2id" name="button2id" class="btn btn-warning" type="button"  onClick="<?php echo $devolt; ?>">Retornar </center> 
                    <?php }?>
                </div>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block"> Weber Hotel agradece por escolher Weber Hotel&copy; </td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
