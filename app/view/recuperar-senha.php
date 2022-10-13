<?php
//require_once '../configs/dominio.php';
$requisicao = json_decode(base64_decode(end(explode('?', $_SERVER['REQUEST_URI']))));
$chave = $requisicao->chave;
$dirLogo = $GLOBALS['rootDir'] . $GLOBALS['dirImg'] . '/logo/logo_principal_' . $GLOBALS['file_ext'] . '.png';


?>
<html>

<head>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!------ Include the above in your HEAD tag ---------->
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Roboto:300);

        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            z-index: 1;
            background: #7b98fe21;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }

        .form button:hover,
        .form button:active,
        .form button:focus {
            background: #43A047;
        }

        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }

        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form .register-form {
            display: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        .container:before,
        .container:after {
            content: "";
            display: block;
            clear: both;
        }

        .container .info {
            margin: 50px auto;
            text-align: center;
        }

        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }

        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }

        .container .info span a {
            color: #000000;
            text-decoration: none;
        }

        .container .info span .fa {
            color: #EF3B3A;
        }

        body {
            background: #303349;
            /* fallback for old browsers */

            background: linear-gradient(to left, #303545, #303545);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        #confirma {
            background: #FF557E;
        }

        #output {
            color: #fff;
            display: flex !important;

        }

        #output svg {
            width: 50px;
            margin-right: 14px;
        }

        #output.alert-success {
            background: rgb(30 217 30 / 59%);
        }

        #output.alert-danger {
            background: rgb(228, 105, 105);
        }

        .animated {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes dash {
            0% {
                stroke-dashoffset: 1000;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @-webkit-keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
        }

        @keyframes dash-check {
            0% {
                stroke-dashoffset: -100;
            }

            100% {
                stroke-dashoffset: 900;
            }
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

        #senha {
            margin-top: -10px;
        }

        #senha2,
        #confirma {
            margin-top: 15px;
        }

        .logo {
            max-width: 200px;
            max-height: 110px;

        }
    </style>

</head>

<body>
    <div class="login-page">
        <div class="form">
            <div id="output"></div>
            <img style="margin-bottom: 15%;" src="<?php echo $dirLogo; ?>" class="logo"><br>
            <form class="login-form">
                <input id='senha' name="password" type="password" placeholder="Nova senha (Somente números)" maxlength="4" inputmode="numeric" onkeypress="return SomenteNumero(event)" />
                <input id='senha2' name="password" type="password" placeholder="Repetir senha" maxlength="4" inputmode="numeric" onkeypress="return SomenteNumero(event)" />
                <button class="btn btn-info btn-block password" id="confirma" type="button">Confirmar</button>
                <input id='id' type='hidden'>
            </form>
        </div>
    </div>
    <script>
        $(function() {
            var CHAVE = '<?php echo $chave; ?>';
            var cpfcnpj = null;
            $.ajax({
                type: "POST",
                url: '<?php echo $GLOBALS["projectRoot"]; ?>/usuario/chave/cpfcnpj/',
                dataType: 'json',
                data: JSON.stringify({
                    CHAVE: CHAVE
                })
            }).done(function(data) {
                data = JSON.parse(JSON.stringify(data));
                cpfcnpj = data.USUARIO;
                if (data) {
                    $('#id').val(data.USUARIO);
                    $("#avatar").attr("src", "<?php echo $GLOBALS['raiz']; ?>/fly/?img=" + data.IMGPERFIL + "&w=100&h=100&c=1&p=" + $("#id").val());
                }
            });

            $('.password').click(function(e) {
                e.preventDefault();
                var novasenha = $('#senha').val();
                var novasenha2 = $('#senha2').val();
                if ((novasenha != '') && (novasenha == novasenha2)) {
                    $.ajax({
                        type: "PUT",
                        url: '<?php echo $GLOBALS["projectRoot"]; ?>usuario/link/recuperar/senha/',
                        dataType: 'json',
                        data: JSON.stringify({
                            novasenha: novasenha,
                            CPFCNPJ: cpfcnpj,
                            CHAVE: CHAVE
                        })
                    }).done(function(data) {
                        data = JSON.parse(JSON.stringify(data));
                        if (data.retorno == 'sucesso') {
                            $('.form-box').hide(500);
                            $("#output").removeClass("alert-danger");
                            $("#output").addClass("alert alert-success animated fadeInUp").html(
                                "<div><svg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130.2 130.2'>\
                            <circle class='path circle' fill='none' stroke='#73AF55' stroke-width='6' stroke-miterlimit='10' cx='65.1' cy='65.1' r='62.1'/>\
                            <polyline class='path check' fill='none' stroke='#73AF55' stroke-width='6' stroke-linecap='round' stroke-miterlimit='10' points='100.2,40.2 51.5,88.8 29.8,67.5 '/>\
                        </svg></div>" + data.mensagem + "</span>"
                            );
                            $("#output").css("display", "block");
                            $("#output").fadeOut(4000);
                            
                        } else {
                            $('.form-box').hide(500);

                            $("#output").addClass("alert alert-danger animated fadeInUp").html(
                                "<div>\
                            <svg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130.2 130.2'>\
                                <circle class='path circle' fill='none' stroke='#D06079' stroke-width='6' stroke-miterlimit='10' cx='65.1' cy='65.1' r='62.1'/>\
                                <line class='path line' fill='none' stroke='#D06079' stroke-width='6' stroke-linecap='round' stroke-miterlimit='10' x1='34.4' y1='37.9' x2='95.8' y2='92.3'/>\
                                <line class='path line' fill='none' stroke='#D06079' stroke-width='6' stroke-linecap='round' stroke-miterlimit='10' x1='95.8' y1='38' x2='34.4' y2='92.2'/>\
                            </svg>\
                        </div>" + data.mensagem + "</span>"
                            );
                            $("#output").css("display", "block");
                            $("#output").fadeOut(4000);                           
                        }
                    });
                } else {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html(
                        "<div '>\
                </div>\
                Verifique as senhas inseridas.</span>"
                    );
                    $("#output").css("display", "block");
                    $("#output").fadeOut(400);
                }
            });
        });

        function SomenteNumero(e) {
            const tecla = (window.event) ? event.keyCode : e.which;
            if (tecla > 47 && tecla < 58) return true;
            if (tecla == 8 || tecla == 0) return true;
            return false;
        }
    </script>
</body>

</html>
