<?php
header('Content-type: text/html; charset=UTF-8');
//require_once '../configs/dominio.php';
$dirLogo = $GLOBALS['rootDir'] . $GLOBALS['dirImg'] . '/logo/logo_' . $GLOBALS['file_ext'] . '.png';
$dirFundo = $GLOBALS['rootDir'] . $GLOBALS['dirImg'] . '/logo/imagem-fundo-antecipa.png';
?>
<html>

<head>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!------ Include the above in your HEAD tag ---------->
    <style type="text/css">
        input {
            text-transform: uppercase !important;
        }

        body {
            background-image: url(<?php echo $dirFundo; ?>);
            background-size: cover;
            background-position: center;
        }

        .password-container {
            position: relative;
            width: 800px;
            margin: 120px auto;
            padding: 40px 40px 40px;
            text-align: center;
            background: #fff;
            border: 1px solid #ccc;
        }

        #output {
            position: absolute;
            width: 400px;
            bottom: 75px;
            left: 200px;
            color: #fff;
        }

        #output.alert-success {
            background: rgb(25, 204, 25);
        }

        #output.alert-danger {
            background: rgb(228, 105, 105);
        }


        .password-container::before,
        .password-container::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 5px;
            left: 0;
            background: #fff;
            z-index: -1;
            -webkit-transform: rotateZ(4deg);
            -moz-transform: rotateZ(4deg);
            -ms-transform: rotateZ(4deg);
            border: 1px solid #ccc;

        }

        .password-container::after {
            top: 5px;
            z-index: -2;
            -webkit-transform: rotateZ(-2deg);
            -moz-transform: rotateZ(-2deg);
            -ms-transform: rotateZ(-2deg);

        }


        .form-box input {
            padding: 10px;
            height: 40px;
            border: 1px solid #ccc;
            ;
            background: #fafafa;
            transition: 0.2s ease-in-out;
            margin-bottom: 10px;
        }

        .form-box input:focus {
            outline: 0;
            background: #eee;
        }

        .form-box input[type="text"] {
            border-radius: 5px 5px 5px 5px;
        }

        .form-box input[type="password"] {
            border-radius: 5px 5px 5px 5px;

        }

        .verificar input.password {
            margin-top: 15px;
            padding: 10px 20px;
            width: 120px;
            background-color: #087c9e;
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

        .hide {
            display: none;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="password-container">
                    <img src="<?php echo $dirLogo; ?>" height="40" width="150"><br>
                    <div id="output"></div>
                    <h3>Seja bem-vindo(a) <?php echo (!empty($requisicao['nomevendedor']) ? $requisicao['nomevendedor'] : 'vendedor'); ?>, convidado(a) <br> <?php echo (!empty($requisicao['razaosocial']) ? 'pela empresa ' . $requisicao['razaosocial'] : 'pelo(a) ' . $requisicao['nome']); ?> </h3>
                    <h4>Preencha os campos abaixo para realizar o cadastro!</h4><br>
                    <div class="form-box">
                        <form action="" method="POST" name="form1">
                            <label style="font-size: 17px" for="nome">Nome: </label>
                            <input type="text" name="nome" value="<?php echo (!empty($requisicao['nomevendedor']) ? $requisicao['nomevendedor'] : ' '); ?>" id="nome" placeholder="Nome" style="width: 89%; margin-left: 1%" required><br>

                            <label style="font-size: 17px" for="email">E-mail: </label>
                            <input type="text" id="email" value="<?php echo (!empty($requisicao['emailvendedor']) ? $requisicao['emailvendedor'] : ' '); ?>" placeholder="E-mail" style="width: 89%; margin-left: 1%" required disabled><br>

                            <label style="font-size: 17px" for="senha">Senha: </label>
                            <input id="senha" name="password" type="password" maxlength="6" placeholder="Apenas números" onkeypress="return SomenteNumero(event)" style="width: 35.5%; margin-left: 1%" required>

                            <label style="font-size: 17px; margin-left: 2%" for="senha2">Repetir senha: </label>
                            <input id="senha2" name="password2" type="password" maxlength="6" placeholder="Repetir" onkeypress="return SomenteNumero(event)" style="width: 34%;" required><br>

                            <label style="font-size: 17;" for="cpf">CPF: </label>
                            <input type="text" id="cpf" name="cpf" placeholder="CPF" maxlength="14" onkeydown="javascript: fMasc( this, mCPF );" style="width: 35.5%; margin-left: 3.5%" required>

                            <label style="font-size: 17px; margin-left: 13.5%" for="cep">Cep: </label>
                            <input type="radio" name="esconder" id="esconde" style="display: none;">
                            <input type="text" name="zip" id="cep" placeholder="Cep" onkeyup="$(this).mask('00.000-000')" onblur="validacep();" style="width: 34%;" required><br>
                            <div id="abrirform" class="hide">
                                <label style="font-size: 17px" for="cidade">Cidade: </label>
                                <input type="text" id="cidade" placeholder="Cidade" style="width: 35.5%; margin-left: 0.5%" required>

                                <label style="font-size: 17px; margin-left: 11%" for="bairro">Bairro: </label>
                                <input type="text" id="bairro" placeholder="Bairro" style="width: 34%;" required><br>

                                <label style="font-size: 17px;" for="uf">UF: </label>
                                <input type="text" id="uf" placeholder="Estado" style="width: 35.5%; margin-left: 5%" required>

                                <label style="font-size: 17px; margin-left: 9%" for="numero">Número: </label>
                                <input type="text" id="numero" placeholder="Número" style="width: 34%;" required><br>

                                <label style="font-size: 17px" for="rua">Rua: </label>
                                <input type="text" id="rua" placeholder="Rua" style="width: 89%; margin-left: 3.5%" required><br>

                                <label style="font-size: 17px" for="complemento">Complemento:</label>
                                <input type="text" id="complemento" placeholder="Complemento" style="width: 81%;">
                            </div>
                    </div>
                    <div class="verificar">
                        <div align="center">
                            <input type="checkbox" id="check" required>
                            <label id="termos" for="check">
                                Concordo com os <a href="">termos e condições</a>
                            </label>
                            <p for="check">
                                Você deve concordar antes de se cadastrar.
                            </p>
                            <input class="btn btn-info btn-block password" id="confirma" type="submit" value="Confirmar"></input>
                            <input id='id' type='hidden'>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function validacep() {
            var zip = form1.zip.value;
            var element = document.getElementById("cep");
            var mostra = document.getElementById("abrirform");

            if (zip.length > 9) {

                mostra.classList.remove("hide");

                var texto = document.getElementById("cep").value;
                var er = /\^|~|\?|,|\*|\.|\-/g;
                var cepfinal = texto.replace(er, "");

                var url = "<?php echo $GLOBALS['projectRoot']; ?>";
                $.getJSON(url + 'cep/buscar?CEP=' + cepfinal, function(dados) {
                    $("#rua").val(dados.rua);
                    $("#bairro").val(dados.bairro);
                    $("#cidade").val(dados.cidade);
                    $("#uf").val(dados.uf);
                    $("#numero").focus();
                });

            } else {
                mostra.classList.add("hide");
            }
        }


        function fMasc(objeto, mascara) {
            obj = objeto
            masc = mascara
            setTimeout("fMascEx()", 1)
        }

        function fMascEx() {
            obj.value = masc(obj.value)
        }

        function mCPF(cpf) {
            cpf = cpf.replace(/\D/g, "")
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
            return cpf
        }


        $(document).ready(function() {
            $('#cep').val('');
        });

        $(function() {

            var cpfusuario = "<?php echo $requisicao['cpfcnpj']; ?>";

            $('#confirma').click(function(e) {
                e.preventDefault();
                var nome = $('#nome').val();
                var email = $('#email').val();
                var novasenha = $('#senha').val();
                var novasenha2 = $('#senha2').val();
                var cpf = $('#cpf').val();
                var tamanhoCPF = $("#cpf").val().length
                var cep = $('#cep').val();
                var cidade = $('#cidade').val();
                var bairro = $('#bairro').val();
                var uf = $('#uf').val();
                var numero = $('#numero').val();
                var rua = $('#rua').val();
                var complemento = $('#complemento').val();
                var tamanhosenha = form1.password.value;

                function TestaCPF(cpf) {

                    var cpf = $('#cpf').val();
                    var cpf = cpf.replace(".", "");
                    var cpf = cpf.replace(".", "");
                    var cpf = cpf.replace("-", "");

                    var Soma;
                    var Resto;
                    Soma = 0;
                    if (cpf == "00000000000") return false;

                    for (i = 1; i <= 9; i++) Soma = Soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);
                    Resto = (Soma * 10) % 11;

                    if ((Resto == 10) || (Resto == 11)) Resto = 0;
                    if (Resto != parseInt(cpf.substring(9, 10))) return false;

                    Soma = 0;
                    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);
                    Resto = (Soma * 10) % 11;

                    if ((Resto == 10) || (Resto == 11)) Resto = 0;
                    if (Resto != parseInt(cpf.substring(10, 11))) return false;
                    return true;
                }

                if (tamanhosenha.length != 6) {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("A senha deve possuir 6 dígitos.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (novasenha != novasenha2) {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("As senhas não conferem.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (nome == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha seu nome.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (cpf == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha seu CPF.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (!TestaCPF(cpf)) {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha um CPF válido.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (cep == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha seu CEP.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (cidade == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha sua cidade.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (bairro == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha seu bairro.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (uf == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha seu estado.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (numero == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha o número do seu endereço.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (rua == '') {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Preencha a sua rua.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                if (!$("#check").is(':checked')) {
                    $("#output").addClass("alert alert-danger animated fadeInUp").html("Por favor, aceite os termos e condições.</span>");
                    $("#output").css("display", "block");
                    $("#output").fadeOut(4000);
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: '<?php echo $GLOBALS["projectRoot"]; ?>vendedor/inserir/',
                    dataType: 'json',
                    data: JSON.stringify({
                        NOME: nome,
                        EMAIL: email,
                        SENHA: novasenha,
                        CPFCNPJVENDEDOR: cpf,
                        CEP: cep,
                        CIDADE: cidade,
                        BAIRRO: bairro,
                        UF: uf,
                        NUMERO: numero,
                        ENDERECO: rua,
                        COMPLEMENTO: complemento,
                        CPFCNPJ: cpfusuario,
                        HASH: '<?php echo $requisicao['hash']; ?>'
                    })
                }).done(function(data) {
                    //data = JSON.parse(JSON.stringify(data));

                    console.log(data);
                    if (data.retorno == 'sucesso') {
                        $("#output").removeClass("alert alert-danger animated fadeInUp");
                        $("#output").addClass("alert alert-success animated fadeInUp").html(data.mensagem);
                        $("#output").css("display", "block");
                        $("#output").fadeOut(4000);

                    } else if (data.retorno == 'erro') {
                        $("#output").addClass("alert alert-danger animated fadeInUp").html(data.mensagem);
                        $("#output").css("display", "block");
                        $("#output").fadeOut(4000);

                    }
                });
            });
        });

        function SomenteNumero(e) {
            var tecla = (window.event) ? event.keyCode : e.which;
            if ((tecla > 47 && tecla < 58)) return true;
            else {
                if (tecla == 8 || tecla == 0) return true;
                else return false;
            }
        }
    </script>
</body>

</html>
