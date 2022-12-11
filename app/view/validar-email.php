<?php
header('Content-type: text/html; charset=UTF-8');
$requisicao = json_decode(utf8_decode(base64_decode(end(explode('?', $_SERVER['REQUEST_URI'])))));
$cpfcnpj    = $requisicao->cpfcnpj;
$email      = $requisicao->email;
$nome       = $requisicao->nome;
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
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .td1 {
            padding: 10px 30px 10px 30px;
        }

        .td {
            padding: 10px 0px 10px 0px;
            width: 100%;
        }

        .td2 {
            padding: 10px 0px 20px 0px;
        }

        .td3 {
            padding: 20px 30px 10px 30px;
        }

        .h2 {
            font-size: 20px;
        }

        .img {
                display: block;
        }

        .footer {
            padding: 30px 91px 30px 90px;
            font-size: 13px;
            background-color: #D1D3D4;
            text-align: center;
        }

        .login-container{
            position: relative;
            width: 300px;
            margin: 0px auto;
            padding: 20px 40px 40px;
            text-align: center;
            background: #fff;
        }

        .login-container2{
            position: relative;
            width: 300px;
            margin: 0px auto;
            padding: 20px 40px 0px;
            text-align: center;
            background: #fff;
        }

        #output{
            position: absolute;
            width: 300px;
            top: 0px;
            left: 0;
            color: #fff;
        }

        #output.alert-success{
            background: rgb(25, 204, 25);
        }

        #output.alert-danger{
            background: rgb(228, 105, 105);
        }

        .animated {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        @-webkit-keyframes fadeInUp {
            0% {
                opacity: 0;
                -webkit-transform: translateY(20px);
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                -webkit-transform: translateY(20px);
                -ms-transform: translateY(20px);
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                -webkit-transform: translateY(0);
                -ms-transform: translateY(0);
                transform: translateY(0);
            }
        }

        .fadeInUp {
            -webkit-animation-name: fadeInUp;
            animation-name: fadeInUp;
        }

        .logo {
            max-width: 150px;
            max-height: 40px;
        }

    </style>

</head>
<body>


    <div class="container">
        <div class="row align-items-center">
            <div class='col-12'>
                <div id="login" class="login-container">
                    <div id="output"></div>

                </div>
            </div>
        </div>
    </div>


     <table align="center" width="30%">
            <tr>
                <td align="center" class="td3">
                    <div class="h2">Olá, <b><?php echo $nome;?></b></div>
                    <div class="h2">Bem vindo(a) ao <?php echo $GLOBALS['nomeDoProjeto']; ?>. Você está apto(a) a entrar no universo dos cartões.</div>

                </td>
            </tr>
            <tr>
                <td align="center" class="td1">
                    <img src="<?php echo $GLOBALS['raiz'] . $GLOBALS['dirImg']; ?>/logo/logo_principal_<?php echo $GLOBALS['file_ext']; ?>.png" class="img logo" />
                </td>
            </tr>
            <tr>
                <td class="td">
                    <table>
                        <td style="width: 15%; padding-right: 20px">
                            <img src="<?php echo $GLOBALS['raiz'] . $GLOBALS['dirImg']; ?>/logo/icon3.png" alt="" width="60" height="60" class="img" />
                        </td>
                        <td style="width: 85%; padding-bottom: 15px;">  
                            <h2 class="h2">Facilidade e velocidade</h2>
                            Em poucos passos você paga seus boletos e gerencia seu dinheiro.
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>Aproveite ao máximo a <?php echo $GLOBALS['nomeDoProjeto']; ?>!
                        <br><br>Dúvidas? Entre em contato com a gente pelo e-mail <b><a href="#">contato<?php echo $GLOBALS['arrobaEmailDoProjeto']; ?></a></b>.
                    </p><?php echo $GLOBALS['nomeDoProjeto'] . " " . date('Y');?> 
                </td>
            </tr>
        </table>

<script>
    $(function(){
        $.ajax({
            type: "POST",
            url: '<?php echo $GLOBALS["projectRoot"]; ?>usuario/email/verificacao/validar/',
            dataType: 'json',
            data: JSON.stringify({
                EMAIL: '<?php echo $email; ?>',
                CPFCNPJ: '<?php echo $cpfcnpj; ?>'
            })
            }).done(function(data) {
                data = JSON.parse(JSON.stringify(data));
                if(data){
                    $("#output").addClass("alert alert-success animated fadeInUp").html(data.mensagem+"</span>");
                    $("#output").fadeOut(4000);
                    document.getElementById('login').className = 'login-container2';
                }else{
                    $("#output").addClass("alert alert-danger animated fadeInUp").html(data.mensagem+"</span>");
                    $("#output").fadeOut(4000);
                    document.getElementById('login').className = 'login-container2';
                }
        }).fail( ({ responseText }) => {
            var response = JSON.parse(responseText)
            $("#output").addClass("alert alert-danger animated fadeInUp").html(response.mensagem+"</span>");
            $("#output").fadeOut(4000);
        });


    });

</script>

</body>
</html>
