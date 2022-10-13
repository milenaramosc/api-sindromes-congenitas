<?php
header('Content-type: text/html; charset=UTF-8');
$requisicao  = json_decode(base64_decode(end(explode('?', $_SERVER['REQUEST_URI']))));
$nome        = $requisicao->nome;
$cpfcnpj     = $requisicao->cpfcnpj;
$email       = $requisicao->email;
$nascimento  = $requisicao->nascimento;
$cep         = $requisicao->cep;
$endereco    = $requisicao->endereco;
$numendereco = $requisicao->numendereco;
$complemento = $requisicao->complemento;
$bairro      = $requisicao->bairro;
$cidade      = $requisicao->cidade;
$uf          = $requisicao->uf;
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=yes">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->
    <style type="text/css">

            body {
                font-family: Helvetica, Arial, serif;
                font-size: 15px;
                padding: 0;
                align-self: center;
                max-width: 700px;
                margin: auto;
            }
            .td3 {
                padding: 30px 0px 30px 0px;
            }
            .h2 {
                font-size: 25px;
            }
            .td {
                padding: 10px 0px 10px 0px;
            }

    </style>

</head>
<body class="body" align="center">

     <table width="700">
            <tr>
                <td align="center" class="td3">
                    <div class="h2"><b>Termo de Adesão</b></div>
                </td>
            </tr>
            <tr>
                <td class="td">
                    Eu <?php echo $nome; ?>, CPF <?php echo $cpfcnpj; ?>, nascido em <?php echo $nascimento; ?>,                 
                </td>
            </tr>
            <tr>
                <td class="td">
                    domiciliado à <?php echo $endereco; ?> <?php echo $numendereco; ?> <?php echo $complemento; ?>, 
                </td>
            </tr>
            <tr>
                <td class="td">
                    bairro <?php echo $bairro; ?>, CEP <?php echo $cep;?>, cidade <?php echo $cidade;?>, estado <?php echo $uf;?>.
                </td>
            </tr>
        </table>

</body>
</html>
