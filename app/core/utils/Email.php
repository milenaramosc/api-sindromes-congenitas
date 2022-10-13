<?php

namespace App\Core\Utils;

require_once 'class.phpmailer.php';

class Email
{
    private const DEFAULT_EVERYTHING = '* {
        color: black !important;
    }';

    private const DEFAULT_BODY = '
        body {
            font-family: Helvetica, Arial, serif;
            font-size: 15px;
            padding: 0;
            align-self: center;
            max-width: 700px;
            margin: auto;
        }';

    private const DEFAULT_BUTTON = '
         .button {
                background-color: #4CAF50;
                border: none;
                border-radius: 10px;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 15px;
                margin: 4px 2px;
                cursor: pointer;
         }';

    private const DEFAULT_H2 = '
        .h2 {
            font-size: 32px;
        }';

    private const DEFAULT_H3 = '
        .h3 {
            font-size: 18px;
        }';

    private const DEFAULT_P = '
        p {
            color: black !important;
        }';

    private const DEFAULT_BOAS_VINDAS = '
        .boasvindas {
            width: 365px;
            padding: 0px 0px 0px 0px;
        }';

    private const DEFAULT_CORPO = '
        .corpo {
            padding: 25px 0px 10px 0px;
            margin-bottom: 0px;
            text-align: center;
        }';

    private const DEFAULT_LINK_REDIRECIONAMENTO = '
        .linkdirecionamento {
            padding: 10px 30px 30px 30px;
            text-align: center;
            height: 60;
        }';

    private const DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR = '
        .linkdirecionamento a {
            color: #FFF !important;
            text-decoration: none !important;
        }';

    private const DEFAULT_IMG = '
        .img {
            display: block;
        }';

    private const DEFAULT_FOOTER = '
        .footer {
            padding: 30px 30px 30px 30px;
            font-size: 12px;
            background-color: #D1D3D4;
            text-align: center;
        }';

    private const DEFAULT_RODAPE = '
        .rodape {
            padding: 10px 0 10px 0;
            font-size: 12px;
            background-color: #D1D3D4;
            text-align: center;
            width: 700px;
        }';

    private const DEFAULT_TABLE = '{
        border-collapse: collapse
    }';

    private const DEFAULT_TABLE_TD_TABLE_TH = '{
        padding: .50rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6
    }';

    private const DEFAULT_TABLE_THEAD_TH = '{
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6
    }';

    private const DEFAULT_TABLE_TBODY_T_BODY = '{
        border-top: 2px solid #dee2e6
    }';

    private const DEFAULT_DATA = '
        #data {
            color: #000;
        }';

    private const DEFAULT_VALOR = '
        #valor {
            margin-bottom: -15px !important;
            margin-top: 20px;
        }';

    public static function padraoEmail($nome, $app, $assunto, $email, $style, $corpo)
    {
        try {
            if ($email == "") {
                return "Email de destino vazio";
            }

            $mail = new PHPMailer(true); //New instance, with exceptions enabled

            $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>' . $GLOBALS['nomeDoProjeto'] . '</title>
                                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                                <meta charset="utf-8">
                                <style>
                                    ' . $style . '
                                    label {
                                        color: gray !important;
                                    }
                                    .nome {
                                        color:  #FF557E !important;
                                    }
				                    .h2, .h3 {
                 			            color: #000000 !important;
            			            }
                                </style>
                            </head>
                            <body class="body" align="center">
                                <table width="700" align="center">
                                    <tr style = "background-color:rgba(255, 85, 126, 0.2);">
                                        <td align="center" style = "padding:20px;">
                                            <img src="' . $GLOBALS['rootDir'] . $GLOBALS['dirImg'] . '/logo/logo_principal_' . '/' . $GLOBALS['file_ext'] . '.png" width="240px"/>
                                        </td>
                                    </tr>
                                    ' . $corpo . '
                                    <tr>
                                        <td align="center">
                                            <label>Ficou com dúvidas? Entre em contato conosco pelo <b><a style="color: #FF557E !important;" href="' . $GLOBALS['siteDoProjeto'] . '">site.</a></b>.</label><br><br>
                                            <label class="nome">Squid Conta</label>
                                        </td>
                                    </tr>
                                </table>
                            </body>
                        </html>';

            // $mensagem         = preg_replace('/\\\\/', '', utf8_decode($msg)); //Strip backslashes
            $email            = strtolower($email);
            $mensagem         = $msg; //Strip backslashes

            $mail->IsSMTP();                           // tell the class to use SMTP
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;                    // set the SMTP server port
            $mail->Host       = EMAIL_HOST; // SMTP server
            $mail->Username   = EMAIL_USERNAME;     // SMTP server username
            $mail->Password   = EMAIL_PASSWORD;            // SMTP server password
            $mail->CharSet    = 'UTF-8';
            $sender = EMAIL_SENDER;
            $senderName = $GLOBALS['nomeApp'];
            $mail->setFrom($sender, $senderName);
            //$mail->IsSendmail();  // tell the class to use Sendmail
            $de = $sender;
            $nome = $GLOBALS['nomeApp'];

            $mail->AddReplyTo($de, $nome);

            // $mail->From       = "cdc@rbmws.com.br";
            // $mail->FromName   = $GLOBALS['nomeApp'];

            //$mail->AddAddress("andremendes@gmail.com");
            $vemail = explode(";", $email);

            //$email = $vemail[0];

            foreach ($vemail as $email) {
                $email = trim(strtolower($email));
                if ($email != "") {
                    $mail->AddAddress($email);
                }
            }

            //$mail->AddAddress($email);

            $mail->Subject  = $assunto;
            $mail->WordWrap   = 80; // set word wrap

            $mail->MsgHTML($mensagem);
            $mail->IsHTML(true); // send as HTML

            if ($mail->Send()) {
                return true;
            } else {
                return false;
            }
            //echo 'Message has been sent.';
        } catch (\Exception $e) {
            return false;
            //return $e->errorMessage();
        }
    }

    public static function enviaEmailRecuperarSenha($nome, $app, $link, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b></div><br><br>
                            <div class="h3">Caso não tenha solicitado alteração de senha, favor entrar em contato conosco.</div><br>
                            <div class="h3">Por segurança, este link irá expirar em 12 horas.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Recuperar Senha</a>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaEmailAlterarSenha($nome, $app, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá, <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">Sua solicitação para alteração de senha foi processada com sucesso.</div><br><br>
                        </td>
                    </tr>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaEmailCadastroTerceiros($nome, $senha, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá, <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">Seu cadastro foi realizado com sucesso por ' . $GLOBALS['nomeDoProjeto'] . ' </div><br><br>
                            <div class="h3">Sua senha: ' . $senha . ' </div><br><br>
                        </td>
                    </tr>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $GLOBALS['nomeApp'], "Cadastro conta digital", $email, $style, $corpo);
    }

    public static function enviaEmailAtivacaoAntecipa($nome, $app, $link, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td>
                            <div class="h2" align="center">Olá <b>' . $nome . '</b>, tudo bem?</div><br><br>
                            <div class="h3" align="center">Nós, do <b>' . $GLOBALS['nomeDoProjeto'] . '</b> estamos muito felizes com o seu contato.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>Em breve você receberá informações sobre o seu cadastro.</p>
                    <p>Por hora, você só precisa confirmar o seu e-mail clicando no botão abaixo.</p>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Confirmar e-mail *</a>
                    <p style="font-size: 11px !important; margin-bottom: 0px; ">* Para confirmar o email, <br>acesse por qualquer navegador!</p>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaEmailAtivacao($nome, $app, $link, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>, tudo bem?</div><br><br>
                            <div class="h3">Nós, do <b>' . $GLOBALS['nomeDoProjeto'] . '</b> estamos muito felizes com o seu cadastro.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>Você está poucos passos de entrar no universo dos cartões.</p>
                    <p>Para isso acontecer, você só precisa confirmar o seu e-mail clicando no botão abaixo.</p>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Confirmar e-mail *</a>
                    <p style="font-size: 11px !important; margin-bottom: 0px; ">* Para confirmar o email, <br>acesse por qualquer navegador!</p>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function emailConfirmarTransacao($nome, $nomeBeneficiario, $valor, $email)
    {

        $app = "";
        $assunto = "Confirmação de Transferência";

        $data = date('Y-m-d H:i:s');
        $data = Email::formatandoDataPraEmail($data);

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P . self::DEFAULT_DATA;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">A transferência para <b>' . $nomeBeneficiario . '</b> foi realizada com sucesso.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>Valor Enviado:</p>
                    <p><b>R$ ' . number_format($valor, 2, ',', '.') . '</b></p>
                    <p id="data">' . $data . '</p><br>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function emailConfirmarTransacaoRecebedor($nome, $nomeBeneficiario, $valor, $email)
    {

        $app = "";
        $assunto = "Confirmação de Transferência";

        $data = date('Y-m-d H:i:s');
        $data = Email::formatandoDataPraEmail($data);

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P . self::DEFAULT_DATA;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nomeBeneficiario . '</b>,</div><br><br>
                            <div class="h3"><b>' . $nome . '</b> realizou uma transferência em sua conta.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
            <td align="center">
                    <p>Valor Enviado:</p>
                    <p><b>R$ ' . number_format($valor, 2, ',', '.') . '</b></p>
                    <p id="data">' . $data . '</p><br>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function emailConfirmarSaque($nome, $valor, $email)
    {

        $app = "";
        $assunto = "Confirmação de Transferência";

        $data = date('Y-m-d H:i:s');
        $data = Email::formatandoDataPraEmail($data);

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P . self::DEFAULT_DATA . self::DEFAULT_VALOR;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>!</div><br><br>
                            <div class="h3">Sua transferência foi realizada com sucesso.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr align="center">
                <td class="linkdirecionamento">
                    <div class="h3" id="valor">Valor Enviado <b>R$ ' . number_format($valor, 2, ',', '.') . '</b></div>
                </td>
            </tr>
            <tr align="center">
                <td>
                    <div id="data">' . $data . '</div>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function emailBemvindo($dados, $link)
    {

        $app = "";
        $assunto = "Bem Vindo ao Conta Digital";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                        <tr>
                            <td class="boasvindas" align="center">
                                <div class="h2"><b>Olá ' . $dados['NOME'] . ' !</b></div><br><br>
                                <div class="h3"><b>Bem-vindo(a) ao Squid Conta!</b>!</div><br>
                            </td>
                        </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>Estamos muito felizes com a sua chegada.</p>
                    <p> Agora você tem todo o controle da sua vida financeira na palma das mãos.</p>
                </td>
            </tr>
           ';

        return Email::padraoEmail($dados['NOME'], $app, $assunto, $dados['EMAIL'], $style, $corpo);
    }

    public static function recrutadorVendedor($email, $nomevendedor, $nome, $link)
    {

        $app = "";
        $assunto = "Convite de vendedor";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nomevendedor . '</b>,</div><br><br>
                            <div class="h3">Que bom que você aceitou fazer parte do meu time de vendas.</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>No Conta Digital você poderá realizar vendas via Cartão, Boleto ou<br><br>enviar um link para o e-mail do seu cliente.</p><br>
                    <p>O aplicativo é bem simples. Não se esqueça, estamos juntos neste trabalho.</p>
                    <p>Clique no botão abaixo e cadastre-se!</p>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Cadastrar</a></a><br>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function formatandoDataPraEmail($data)
    {

        $mesDesc = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
        $manipulador = explode(' ', $data);

        $data = $manipulador[0];
        $hora = $manipulador[1];

        $manipulador = explode('-', $data);

        $dia = $manipulador[2];
        $mes = $manipulador[1];
        $ano = $manipulador[0];

        return $dia . ' de ' . $mesDesc[$mes - 1] . ' ' . $ano . ' às ' . $hora;
    }

    public static function enviaEmailMudancaSecret($nome, $app, $link, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b></div><br><br>
                            <div class="h3">Caso não tenha solicitado alteração de autenticação, favor entrar em contato conosco.</div><br>
                            <div class="h3">Esse link expira em 2 horas. Caso ocorra, por gentileza, realizar o pedido novamente</div><br>
                        </td>
                    </tr>
                </td>
            </tr>

            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Alterar autenticação</a>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaBoletoEmail($email, $nome, $link)
    {

        $app = "";
        $assunto = "Boleto " . $GLOBALS['nomeDoProjeto'];

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P . self::DEFAULT_DATA;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">para acessar o seu boleto, clique no botão abaixo!</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr align="center">
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button" target="_blank">Visualizar Boleto</a>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaEmailVenda($nome, $link, $email)
    {

        $app = "";
        $assunto = "Email de Venda";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>, tudo bem?</div><br><br>
                            <div class="h3">Chegou a hora de realizar a sua compra. Vamos, lá</b>!</div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>Basta você clicar no botão abaixo e preencher com os seus dados.</p>
                    <p>Não precisa se preocupar, seus dados estarão seguros com a gente.</p>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Iniciar compra</a></a><br>
                    <p style="font-size: 13px;">Ao final você receberá um e-mail com seu comprovante!</p>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviaEmailConfirmacaoVenda($nome, $email, $ArrayVendas, $qntdCartoes)
    {
        // var_dump($ArrayVendas);exit;
        $app = "";
        $assunto = "Email de Venda";

        $arrayQntCartao = array('Segundo Cartão', 'Terceiro Cartão', 'Quarto Cartão', 'Quinto Cartão', 'Sexto Cartão', 'Sétimo Cartão', 'Oitavo Cartão', 'Nono Cartão', 'Décimo Cartão');

        $style = self::DEFAULT_EVERYTHING . self::DEFAULT_BODY . self::DEFAULT_IMG . self::DEFAULT_RODAPE . self::DEFAULT_TABLE . self::DEFAULT_TABLE_TD_TABLE_TH . self::DEFAULT_TABLE_THEAD_TH . self::DEFAULT_TABLE_TBODY_T_BODY;

        $corpo = '</table>
                <table class="table table-striped" width="700" align="center">
                    <tr>
                        <td>Valor Total</td>
                        <th>R$ ' . number_format($ArrayVendas['VALOR'], 2, ',', '.') . '</th>
                    </tr>
                   
                    <tr style="background-color:rgba(0,0,0,.05) !important;">
                        <td>' . (!empty($ArrayVendas['DIGITOSCARTAO2']) ? 'Forma de pagamento do Primeiro Cartão' : 'Forma de pagamento') . '</td>
                        <th>' . $ArrayVendas['FORMAPAGAMENTOCARTAO1'] . '</th>
                    </tr>
                    <tr>
                        <td>' . (!empty($ArrayVendas['DIGITOSCARTAO2']) ? '4 Últimos Dígitos do Primeiro Cartão' : '4 Últimos Dígitos do Cartão') . '</td>
                        <th>' . $ArrayVendas['DIGITOSCARTAO1'] . '</th>
                    </tr>
                    <tr>
                        <td></td>
                        <th></th>
                    </tr>';

        if ($qntdCartoes > 1) {
            for ($i = 1; $i < $qntdCartoes; $i++) {
                foreach ($arrayQntCartao as $index => $card) {
                    $index = ++$i;
                    if (!empty($ArrayVendas['DIGITOSCARTAO' . $index])) {
                        $corpo .= '<tr>
                                <td>TID' . $index . '</td>
                                <th>' . '</th>
                            </tr>
                            <tr style="background-color:rgba(0,0,0,.05) !important;">
                                <td>Dígitos do' . $card . '</td>
                                <th>' . $ArrayVendas['DIGITOSCARTAO' . $index] . '</th>
                            </tr>
                            <tr>
                                <td>Forma de pagamento' . $card . '</td>
                                <th>' . $ArrayVendas['FORMAPAGAMENTOCARTAO' . $index] . '</th>
                            </tr>
                            <tr style="background-color:rgba(0,0,0,.05) !important;">
                                <td>Total do' . $card . '</td>
                                <th> R$ ' . number_format($ArrayVendas['VALORCARTAO' . $index], 2, ',', '.') . '</th>
                            </tr>
                            <tr>
                                <td>Data e Hora</td>
                                <th>' . $ArrayVendas['DATA'] . '</th>
                            </tr>';
                    }
                }
            }

            // $corpo.= '<tr>
            //             <td>TID</td>
            //             <th>'.'</th>
            //         </tr>
            //         <tr style="background-color:rgba(0,0,0,.05) !important;">
            //             <td>Dígitos do Segundo Cartão</td>
            //             <th>'.$ArrayVendas['DIGITOSSEGUNDOCARTAO'].'</th>
            //         </tr>
            //         <tr>
            //             <td>Forma de pagamento do Segundo Cartão</td>
            //             <th>'.$ArrayVendas['FORMAPAGAMENTOSEGUNDOCARTAO'].'</th>
            //         </tr>
            //         <tr style="background-color:rgba(0,0,0,.05) !important;">
            //             <td>Total do Segundo Cartão</td>
            //             <th>'.$ArrayVendas['VALORCARTAO2'].'</th>
            //         </tr>
            //         <tr>
            //             <td>Data e Hora</td>
            //             <th>'.$ArrayVendas['DATA'].'</th>
            //         </tr>';
        } else {
            $corpo .= '<tr>
                        <td>Data e Hora</td>
                        <th>' . $ArrayVendas['DATA'] . '</th>
                    </tr>';
        }

        $corpo .= '
                </table>
                
                <table width="700" align="center">';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function bemVindoVendedor($nomevendedor, $email)
    {

        $app = "";
        $assunto = "Bem vindo vendedor";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá, <b>' . $nomevendedor . '</b>,</div>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>O seu cadastro como vendedor foi realizado com sucesso.</p>
                    <p>Agora é sé começar a utilizar o aplicativo.</p>
                    <p>Tudo certo para começar?</p><br>
                </td>
            </tr>';

        return Email::padraoEmail($nomevendedor, $app, $assunto, $email, $style, $corpo);
    }

    public static function aprovacaoAntecipacao($nome, $email, $valor)
    {

        $app = "";
        $assunto = "Antecipação Aprovada";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>A sua antecipação no valor de:</p>
                    <p><b>R$ ' . number_format($valor, 2, ',', '.') . '</b> no dia ' . date('d/m/Y') . '</p>
                    <p>foi aprovada!</p><br>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function reprovacaoAntecipacao($nome, $email, $valor, $motivoRecusa)
    {

        $app = "";
        $assunto = "Antecipação Reprovada";

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER . self::DEFAULT_P;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>A sua antecipação no valor de:</p>
                    <p><b>R$ ' . number_format($valor, 2, ',', '.') . '</b> no dia ' . date('d/m/Y') . '</p>
                    <p>foi recusada pelo motivo: <b> ' . $motivoRecusa . '</b></p><br>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function antecipacaoRealizada($nome, $app, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">Sua antecipação foi processada com sucesso.</div><br><br>
                        </td>
                    </tr>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function conviteVendedores($nome, $app, $link, $token, $assunto, $email)
    {

        $style = self::DEFAULT_BODY . self::DEFAULT_BUTTON . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_LINK_REDIRECIONAMENTO . self::DEFAULT_LINK_REDIRECIONAMENTO_ANCHOR . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">Venha fazer parte do meu time de vendas.</div><br>
                            <div class="h3">Baixe o app e insira o código a seguir: <b>' . $token . '</b> </div><br>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p>No Conta Digital você poderá realizar vendas via Cartão, Boleto ou<br><br>enviar um link para o e-mail do seu cliente.</p><br>
                    <p>O aplicativo é bem simples. Não se esqueça, estamos juntos neste trabalho.</p>
                    <p>Clique no botão abaixo e cadastre-se!</p>
                </td>
            </tr>
            <tr>
                <td class="linkdirecionamento">
                    <a href="' . $link . '" class="button">Cadastrar</a>
                </td>
            </tr>';

        return Email::padraoEmail($nome, $app, $assunto, $email, $style, $corpo);
    }

    public static function enviarCodigoVerificacaoAlteracaoDadosUsuario($nome, $email, $token)
    {
        $style = self::DEFAULT_BODY . self::DEFAULT_H2 . self::DEFAULT_H3 . self::DEFAULT_BOAS_VINDAS . self::DEFAULT_CORPO . self::DEFAULT_IMG . self::DEFAULT_FOOTER;

        $corpo = '<tr>
                <td class="corpo">
                    <tr>
                        <td class="boasvindas" align="center">
                            <div class="h2">Olá <b>' . $nome . '</b>,</div><br><br>
                            <div class="h3">Seu código de verificação ' . $GLOBALS['nomeApp'] . ' é: <b>' . $token . '</b></div><br><br>
                        </td>
                    </tr>
                </td>
            </tr>';

        return self::padraoEmail($nome, $GLOBALS['nomeApp'], 'Alteração de dados cadastrais', $email, $style, $corpo);
    }

    public static function padraoEmailPJ(
        $nome,
        $app,
        $assunto,
        $email,
        $style,
        $corpo,
        $anexo = "",
        $jsonConfigEmail = ""
    ) {

        if ($email == "") {
            return "Email de destino vazio";
        }

        $mail = new PHPMailer(true); //New instance, with exceptions enabled

        $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        
                           ' . $corpo . '
                                 
                        ';

        $confiEmail = json_decode($jsonConfigEmail);
        // $mensagem         = preg_replace('/\\\\/', '', utf8_decode($msg)); //Strip backslashes
        $email            = strtolower($email);
        $mensagem         = utf8_decode($msg); //Strip backslashes
        $mail->IsSMTP();                           // tell the class to use SMTP
        $mail->SMTPAuth   = true;                  // enable SMTP authentication

        //$mail->Port       = 587;                    // set the SMTP server port
        //$mail->Host       = "200.169.96.21"; // SMTP server
        $mail->Host       = $confiEmail[0]->SERVIDORSMTP; //"smtp.gmail.com"; // SMTP server
        $mail->Port       = $confiEmail[0]->PORTAEMAIL; //465;

        $mail->Username   = $confiEmail[0]->CONTAEMAIL; //"STEPHANE.RODRIGUES.SILVA@GMAIL.COM";
        $mail->Password   = $confiEmail[0]->SENHAEMAIL; //"EXMMTTZAMLJRSSVG";
        //$mail->Username   = "app@andremendes.com.br";     // SMTP server username
        //$mail->Password   = "Pagplay@123";            // SMTP server password
        $mail->SMTPSecure = $confiEmail[0]->SEGURANCAEMAIL; //'ssl';
        //$mail->IsSendmail();  // tell the class to use Sendmail

        //$de = "app@andremendes.com.br";
        $de = $confiEmail[0]->CONTAEMAIL; //"stephane.rodrigues.silva@gmail.com";
        //$nome = $GLOBALS['nomeApp'];
        $nome = "WEB PJ";

        $mail->AddReplyTo($de, $nome);

        // $mail->From       = "app@andremendes.com.br";
        $mail->From       = $confiEmail[0]->CONTAEMAIL; //"stephane.rodrigues.silva@gmail.com";

        $mail->FromName   =  $nome = $confiEmail[0]->RAZAOSOCIAL; //"WEB PJ"; //$GLOBALS['nomeApp'];

        //$mail->AddAddress("andremendes@gmail.com");
        $vemail = explode(";", $email);

        //$email = $vemail[0];

        foreach ($vemail as $email) {
            $email = trim(strtolower($email));
            if ($email != "") {
                $mail->AddAddress($email);
            }
        }

        //$mail->AddAddress($email);
        //adicionando anexo no email
        if (!empty($anexo)) {
            $mail->AddStringAttachment(base64_decode($anexo), str_replace(" ", "_", $nome) . '.pdf');
        }
        $mail->Subject  = utf8_decode($assunto);
        $mail->WordWrap   = 80; // set word wrap

        $mail->MsgHTML($mensagem);
        $mail->IsHTML(true); // send as HTML



        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
        //echo 'Message has been sent.';
    }
}
