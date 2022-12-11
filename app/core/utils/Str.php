<?php

namespace App\Core\Utils;

use App\Model\Atividade;
use App\Model\Cartao;
use App\Model\Coban;
use App\Model\TaxaOperadoraFundo;
use App\Model\TaxaUsuario;
use App\Model\Transacao;
use App\Model\Usuario;
use App\Model\Vendedor;
use App\Core\Handlers\Response\ResponseHandler as RBMMensagens;

class Str
{
    public static function validarCpf($cpf, $returnBool = false)
    {
        $json = array(
            "retorno" => "erro",
            "mensagem" => "CPF inválido"
        );

        //CPF 00000000000 é para simulacao
        if (
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            if ($returnBool) {
                return false;
            }
            echo json_encode($json);
            exit;
        }

        $cpf = preg_replace('/[^0-9]/', '', (string)$cpf);
        // Valida tamanho
        if (strlen($cpf) != 11) {
            if ($returnBool) {
                return false;
            }
            echo json_encode($json);
            exit;
        }
        // Calcula e confere primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto)) {
            if ($returnBool) {
                return false;
            }
            echo json_encode($json);
            exit;
        }
        // Calcula e confere segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function validarCnpj($cnpj, $returnBool = false)
    {

        $json = array(
            "retorno" => "erro",
            "mensagem" => "CNPJ inválido"
        );

        $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14) {
            if ($returnBool) {
                return false;
            }
            echo json_encode($json);
            exit;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            if ($returnBool) {
                return false;
            }
            echo json_encode($json);
            exit;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function soNumero($campo)
    {
        return preg_replace("/[^0-9]/", "", $campo);
    }

    public static function base64decode($encode)
    {
        return base64_decode(implode('', preg_split('/\s*/', $encode)));
    }

    public static function capturarExtensaoImagem($imageData): string
    {
        $imageTypes = [
            "jpeg" => "FFD8",
            "png" => "89504E470D0A1A0A",
            "gif" => "474946",
            "bmp" => "424D",
            "tiff" => "4949",
            "tiff" => "4D4D"
        ];

        foreach ($imageTypes as $mime => $hexBytes) {
            $bytes = self::getBytesFromHexString($hexBytes);
            if (substr($imageData, 0, strlen($bytes)) == $bytes) {
                return $mime;
            }
        }

        return '';
    }

    public static function getBytesFromHexString($hexdata)
    {
        for ($count = 0; $count < strlen($hexdata); $count += 2) {
            $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));
        }

        return implode($bytes);
    }

    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    public static function abreviaNome($nome)
    {
        $p1 = strpos(trim($nome), " ");
        return ($p1 > 0) ? substr($nome, 0, $p1 + 2) . "." : $nome;
    }

    //ESTA FUNCAO RETORNA UMA STRING NUMERICA QUE REPRESENTA UM CNPJ,CPF OU CEP COM OS SEUS RESPECTIVOS PONTOS, TRACOS E BARRAS
    public static function insereCaracteres($string)
    {
        //SE FOR CPF
        if (strlen($string) == 11) {
            $string = substr($string, 0, 3) . "." . substr($string, 3, 3) . "." . substr($string, 6, 3) . "-" . substr($string, -2);
        } elseif (strlen($string) == 14) { // SE FOR CNPJ
            $string = substr($string, 0, 2) . "." . substr($string, 2, 3) . "." . substr($string, 5, 3) . "/" . substr($string, 8, 4) . "-" . substr($string, -2);
        } else { //SE FOR CEP
            $string = substr($string, 0, 5) . "-" . substr($string, -3);
        }
        return $string;
    }

    public static function moedaBD($string)
    {
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        return floatval($string);
    }

    public static function moedaBDTWO($string)
    {
        $string = (strpos($string, ".") > -1 && strpos($string, ",") == false) ? str_replace(".", ",", $string) : $string;
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);

        return floatval($string);
    }

    public static function formatMoeda($string)
    {
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace(',', '.', $string);
        $string = str_replace('.', '.', $string);
        return $string;
    }

    public static function randString($size)
    {
        //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
        $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $return = "";

        for ($count = 0; $size > $count; $count++) {
            //Gera um caracter aleatorio
            $return .= $basic[rand(0, strlen($basic) - 1)];
        }

        return $return;
    }

    public static function verificarCPFValido($cpf)
    {
        if (
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return false;
        }

        $cpf = preg_replace('/[^0-9]/', '', (string)$cpf);
        // Valida tamanho
        if (strlen($cpf) != 11) {
            return false;
        }

        // Calcula e confere primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        // Calcula e confere segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function verificarCNPJValido($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function tratarArrayPraPoderOrganizar($data)
    {
        $datahora = explode('-', $data);
        $data = explode('/', $datahora[0]);
        $dataCompleta = trim($data[2]) . '-' . $data[1] . '-' . $data[0] . $datahora[1];
        return strtotime($dataCompleta);
    }

    public static function formataComVerificacaoValor($valor)
    {
        $valor = (strpos($valor, ".") > -1 && strpos($valor, ",") == false) ? str_replace(".", ",", $valor) : $valor;
        $valor = Str::moedaBD($valor);
        return number_format($valor, 2, '.', '');
    }

    public static function gerarComprovanteTransacao($codTransacao, $transacoes, $dadosVendedor)
    {
        $valor = 0;
        $valorReceber =  0;
        $valorDescontado = 0;

        $comprovante = array(
            "ID" => $codTransacao,
            "VALOR" => 'R$ ' . $valor,
            "DATA" => date('d/m/Y H:i:s'),
            "TIPO" => '',
            "IDMODOPAGAMENTO" => '1',
            "MODOPAGAMENTO" => '',
            "DESCRICAO" => 'Venda de Cartão',
            "STATUSTRANSACAO" => 2,
            "STATUS" => 'APROVADO',
            "STATUSANTECIPADO" => '',
            "IDANTECIPA" => '',
            "NOMEVENDEDOR" => $dadosVendedor->NOME,
            "CPFCNPJVENDEDOR" => $dadosVendedor->CPFCNPJEMPRESA,
            "EMAILVENDEDOR" => $dadosVendedor->EMAIL,
            "VALORRECEBER" => 'R$ ' . self::formataComVerificacaoValor($valorReceber),
            "VALORDESCONTADO" => 'R$ ' . self::formataComVerificacaoValor($valorDescontado)
        );

        foreach ($transacoes as $key => $t) {
            $index = ++$key;
            if (!empty($t['VALOR_FINAL_COMPRA'])) {
                $comprovante['TID' . $index] = $t['respostaCaptura'];
                $comprovante['PARCELAS' . $index] =  $t['QTD_PARCELAS'];
                $comprovante['FORMAPAGAMENTOCARTAO' . $index] = $t['QTD_PARCELAS'] . ' x R$ ' . self::formataComVerificacaoValor($t['VALOR_PARCELA']);
                $comprovante['DIGITOSCARTAO' . $index] = $t['DIGITOS'];
                $comprovante['VENDADIGITADACARTAO' . $index] = 'R$ ' . $t['VALOR_DIGITADO'];
                $comprovante['VALORCARTAO' . $index] = 'R$ ' . self::formataComVerificacaoValor($t['VALOR_FINAL_COMPRA']);
                $comprovante['CARTAOBANDEIRA' . $index] = $t['CARTAOBANDEIRA'];

                //VALOR TOTAL
                $comprovante['VALOR'] += Str::moedaBDTWO($t['VALOR']);
                $comprovante['VALORRECEBER'] += $t['VALOR_RECEBIDO'];
                $comprovante['VALORDESCONTADO'] += $t['SOMA_VALOR_REAIS_TAXAS'];
            };
        }

        return $comprovante;
    }

    public static function gerarRequisicaoCielo(
        $codTransacao,
        $json,
        $dadosCartao,
        $type,
        $qualCartao,
        $header = array()
    ) {
        $qualCartao = $qualCartao - 1;

        $documento = self::removeMascaras($json->CARTOES[$qualCartao]->CPF_CARTAO);
        $requisicao = array(
            "CODTRANSACAO" => $codTransacao,
            "CPFCLIENTE" => self::removeMascaras($json->CARTOES[$qualCartao]->CPF_CARTAO),
            "CPFCNPJ" => self::removeMascaras($json->CPFCNPJ),
            "NOME" => $json->CARTOES[$qualCartao]->TITULAR_CARTAO,
            "TIPO" => $type,
            "VALOR" => self::moedaBDTWO($dadosCartao['VALOR_FINAL_COMPRA']), // * 100,
            "PARCELAS" => $dadosCartao['QTD_PARCELAS'],

            "DOCUMENTO" => $documento,
            "TIPODOCUMENTO" => strlen($documento) > 11 ? "CNPJ" : "CPF",

            //Dados do Cartão
            "NUMEROCARTAO" => $json->CARTOES[$qualCartao]->NUMERO_CARTAO,
            "NOMEIMPRESSO" => $json->CARTOES[$qualCartao]->TITULAR_CARTAO,
            "DTEXP" => $json->CARTOES[$qualCartao]->CC_CARTAO,
            "CODSEG" => $json->CARTOES[$qualCartao]->COD_CARTAO,
            "BANDEIRA" => $json->CARTOES[$qualCartao]->BANDEIRA,

            //Dados pessoais
            "EMAIL" => $json->EMAIL, //so tem no Link
            "RUA" => $json->RUA, //so tem no Link
            "BAIRRO" => $json->BAIRRO, //so tem no Link
            "CIDADE" => $json->CIDADE, //so tem no Link
            "NUMERO" => $json->NUMERO, //so tem no Link
            "COMPLEMENTO" => $json->COMPLEMENTO, //so tem no Link
            "CEP" => Str::removeMascaras($json->CEP), //so tem no Link
            "ESTADO" => $json->ESTADO, //so tem no Link
            "TELEFONE" => $json->CARTOES[$qualCartao]->TELEFONE_CARTAO,

            "ID_DISPOSITIVO" => $header['ID_DISPOSITIVO'],
            "IP" => $header['IP']
        );
        return json_encode($requisicao);
    }

    public static function gerarDadosCartao($json, $dadosCartao, $codTransacao, $qualCartao)
    {
        $usuario         = new Usuario();
        $coban           = new Coban();
        $transacao       = new Transacao();
        $cartao          = new Cartao();
        $taxaOperadora   = new TaxaOperadoraFundo();

        $cpfcnpj         = $transacao->getCPFCNPJTransacao($codTransacao);
        $getUsuario      = $usuario->getUsuario(json_encode(array('CPFCNPJ' => $cpfcnpj)));
        if (!$getUsuario) {
            return false;
        }

        $idCoban         = $getUsuario['ID_COBAN'];
        $taxaAntecipacao = $coban->verificarTaxaCoban(json_encode(array("ID_COBAN" => $idCoban, "ID_TIPO_TAXA" => 2)));
        $taxaEmpresta    = $coban->verificarTaxaCoban(json_encode(array("ID_COBAN" => $idCoban, "ID_TIPO_TAXA" => 13)));
        $taxa3DS         = $taxaOperadora->buscarTaxa(array('id' => 9));

        $taxaAntecipacao = $taxaAntecipacao['VALOR'];
        $taxaEmpresta    = $taxaEmpresta['VALOR'];
        $taxa3DS         = $taxa3DS['TAXA'];
        $keyArrayCartao  = $qualCartao - 1;
        $taxaCartao      = $cartao->getTaxaCartaoMes($dadosCartao['QTD_PARCELAS'], $json->CARTOES[$keyArrayCartao]->BANDEIRA);

        return array(
            "CPF_CARTAO" => $json->CARTOES[$keyArrayCartao]->CPF_CARTAO,
            "VALOR" => $dadosCartao['VALOR_RECEBIDO'], // Valor que o vendedor recebe
            "VENDADIGITADA" => $dadosCartao['VALOR_DIGITADO'],
            "VALOR_PAGO_CLIENTE" => $dadosCartao['VALOR_FINAL_COMPRA'],
            "PARCELAS" => $dadosCartao['QTD_PARCELAS'],
            "TITULAR" => $json->CARTOES[$keyArrayCartao]->TITULAR_CARTAO,
            "CARTAOBANDEIRA" => $json->CARTOES[$keyArrayCartao]->BANDEIRA,
            "CODSEGURANCA" => $json->CARTOES[$keyArrayCartao]->COD_CARTAO,
            "NUM_CARTAO" => $json->CARTOES[$keyArrayCartao]->NUMERO_CARTAO,
            "VENCIMENTO" => $json->CARTOES[$keyArrayCartao]->CC_CARTAO,
            "DIGITOS" => substr($json->CARTOES[$keyArrayCartao]->NUMERO_CARTAO, -4),
            "CODTRANSACAO" => $codTransacao,
            "FORMADEPARCELAMENTO" => $dadosCartao['VENDEDOR_ASSUME'],
            "VALORPARCELADO" => $dadosCartao['VALOR_PARCELA'],
            "TAXA_FINANCIAMENTO_PORCENTAGEM" => $dadosCartao['VALOR_PORCENTAGEM_FINANCIAMENTO'],
            "TAXA_FINANCIAMENTO_REAIS" => $dadosCartao['VALOR_REAIS_FINANCIAMENTO'],
            "TAXA_PROCESSAMENTO_PORCENTAGEM" => $dadosCartao['VALOR_PORCENTAGEM_PROCESSAMENTO'],
            "TAXA_PROCESSAMENTO_REAIS" => $dadosCartao['VALOR_REAIS_PROCESSAMENTO'],
            "QUAL_CARTAO" => $qualCartao,
            "TAXA_ANTECIPACAO" => $taxaAntecipacao,
            "TAXA_EMPRESTA" => $taxaEmpresta,
            "TAXA_3DS" => $taxa3DS,
            "TAXA_CARTAO" => $taxaCartao,
        );
    }

    public static function gerarDadosTransacao(
        $taxaFinanciamento,
        $taxaProcessamento,
        $valorTransacao,
        $qtdParcelas,
        $vendedorAssume = false,
        $numero_Cartao = ""
    ) {

        $TaxasOperadoraFundo = new TaxaOperadoraFundo();
        $taxaOperadora = $TaxasOperadoraFundo->getAllTaxasOperadora();

        //feito para corrigir caso a taxa já vem calculada($taxa / 100)
        if ($taxaFinanciamento * 100 < 100) {
            $taxaFinanciamento = $taxaFinanciamento * 100;
        }
        if ($taxaProcessamento * 100 < 100) {
            $taxaProcessamento = $taxaProcessamento * 100;
        }

        if (empty($taxaOperadora)) {
            $taxaOperadoraFinanciamentoPorcentagem = "0";
            $taxaOperadoraProcessamentoPorcentagem = "0";
        } else {
            $taxaOperadora = array_column($taxaOperadora, "TAXA", "ID");
            $taxaOperadoraFinanciamentoPorcentagem = $taxaOperadora[7];
            $taxaOperadoraProcessamentoPorcentagem = $taxaOperadora[4];
        }

        $somaTaxasPorcentagem = $taxaFinanciamento + $taxaProcessamento;

        $valorTransacao = self::trocaVirgulaPorPontoEViraDecimal($valorTransacao);
        $valorReaisTaxaFinanciamento = $valorTransacao * ($taxaFinanciamento / 100);
        $valorReaisTaxaProcessamento = 0; //$valorTransacao * ($taxaProcessamento / 100); // REMOVIDO TAXA PROCESSAMENTO, ACORDADO Q ESTA TAXA SERIA A TAXA A VISTA ENT O CONTROLE VAI SER APENAS COM O RANGE DE PARCELAS, CASO 1 - A VISTA E DAI POR DIANTE
        $somaValorReaisTaxas = $valorReaisTaxaFinanciamento + $valorReaisTaxaProcessamento;

        $valorFinalCompra = ($vendedorAssume) ? $valorTransacao : $valorTransacao + $somaValorReaisTaxas;

        $valorRecebido = ($vendedorAssume) ? $valorTransacao - $somaValorReaisTaxas : $valorTransacao;
        $vendedorAssume = ($vendedorAssume) ? 1 : 0;

        // Parcela
        $valorParcelas = self::trocaVirgulaPorPontoEViraDecimal(Maths::roundDown(($vendedorAssume) ? ($valorRecebido / $qtdParcelas) : ($valorFinalCompra / $qtdParcelas), 3));
        $valorParcelasOriginal = self::trocaVirgulaPorPontoEViraDecimal($valorTransacao / $qtdParcelas);
        $valorParcelaReaisTaxaFinanciamento = $valorTransacao * ($taxaFinanciamento / 100);
        $valorParcelaReaisTaxaProcessamento = $valorTransacao * ($taxaProcessamento / 100);

        $somaParcelaReais = self::trocaVirgulaPorPontoEViraDecimal($valorParcelaReaisTaxaFinanciamento + $valorParcelaReaisTaxaProcessamento);

        // Operadora
        $taxaPorcentagemOperadora = ($taxaFinanciamento) ? $taxaOperadoraFinanciamentoPorcentagem + $taxaOperadoraProcessamentoPorcentagem : $taxaOperadoraProcessamentoPorcentagem;
        $taxaReaisOperadora = $valorFinalCompra * ($taxaPorcentagemOperadora / 100);
        $taxaOperadoraProcessamentoReais = $valorFinalCompra * ($taxaOperadoraProcessamentoPorcentagem / 100);
        $taxaOperadoraFinanciamentoReais = $valorFinalCompra * ($taxaOperadoraFinanciamentoPorcentagem / 100);

        // Spread já splitado ( retirando o valor do custo da operadora)
        $spreadFinanciamentoPorcentagem = ($taxaFinanciamento) ? $taxaOperadoraFinanciamentoPorcentagem - $taxaFinanciamento : 0;
        $spreadProcessamentoPorcentagem =  $taxaProcessamento - $taxaOperadoraProcessamentoPorcentagem;
        $spreadFinanciamentoReais = ($taxaFinanciamento) ? self::trocaVirgulaPorPontoEViraDecimal($taxaOperadoraFinanciamentoReais - $valorReaisTaxaFinanciamento) : 0;
        $spreadProcessamentoReais = self::trocaVirgulaPorPontoEViraDecimal($valorReaisTaxaProcessamento - $taxaOperadoraProcessamentoReais);

        $json = array(
            "VALOR_PARCELA" => $valorParcelas,
            "VALOR_PARCELA_ORIGINAL" => $valorParcelasOriginal,
            "QTD_PARCELAS" => $qtdParcelas,

            "TAXA_FINANCIAMENTO_PORCENTAGEM" => $taxaFinanciamento,
            "TAXA_FINANCIAMENTO_REAIS" => $valorParcelaReaisTaxaFinanciamento,
            "TAXA_PROCESSAMENTO_PORCENTAGEM" => $taxaProcessamento,
            "TAXA_PROCESSAMENTO_REAIS" => $valorParcelaReaisTaxaProcessamento,

            "SPREAD_FINANCIAMENTO_PORCENTAGEM" => $spreadFinanciamentoPorcentagem,
            "SPREAD_FINANCIAMENTO_REAIS" => $spreadFinanciamentoReais,
            "SPREAD_PROCESSAMENTO_PORCENTAGEM" => $spreadProcessamentoPorcentagem,
            "SPREAD_PROCESSAMENTO_REAIS" => $spreadProcessamentoReais,

            "TAXA_OPERADORA_FINANCIAMENTO_PORCENTAGEM" => $taxaOperadoraFinanciamentoPorcentagem,
            "TAXA_OPERADORA_FINANCIAMENTO_REAIS" => $taxaOperadoraFinanciamentoReais,
            "TAXA_OPERADORA_PROCESSAMENTO_PORCENTAGEM" => $taxaOperadoraProcessamentoPorcentagem,
            "TAXA_OPERADORA_PROCESSAMENTO_REAIS" => $taxaOperadoraProcessamentoReais,
            "TAXA_OPERADORA_REAIS_TOTAL" => $taxaReaisOperadora,
            //VALOR TOTAL é usado para a precisação das parcelas
            //caso vendedor assuma as taxas tem q calcular parcelas com base no que o vendedor vai receber, pois já é calculado o valor a receber no campo valor(tabela parcelas)
            "VALOR_TOTAL" => $vendedorAssume == 1 ? $valorRecebido : $valorFinalCompra,
            "VALOR_TOTAL_ORIGINAL" => $valorTransacao,
            "NUMERO_CARTAO" => $numero_Cartao
        );



        $dadosParcelas = self::gerarDadosParcelas($json);

        return array(
            "VALOR_DIGITADO" => $valorTransacao,
            "VALOR_FINAL_COMPRA" => $valorFinalCompra,
            //            "VALOR_FINAL_COMPRA" => number_format($valorFinalCompra, 2, '.', ','),
            "VALOR_RECEBIDO" => $valorRecebido,
            "VALOR_PARCELA" => $valorParcelas,
            "SOMA_VALOR_PORCENTAGEM_TAXAS" => $somaTaxasPorcentagem,
            "SOMA_VALOR_REAIS_TAXAS" => round($somaValorReaisTaxas, 2),
            "VALOR_PORCENTAGEM_FINANCIAMENTO" => $taxaFinanciamento,
            "VALOR_REAIS_FINANCIAMENTO" => round($valorReaisTaxaFinanciamento, 2),
            "VALOR_PORCENTAGEM_PROCESSAMENTO" => $taxaProcessamento,
            "VALOR_REAIS_PROCESSAMENTO" => round($valorReaisTaxaProcessamento, 2),
            "VALOR_PORCENTAGEM_OPERADORA" => $taxaPorcentagemOperadora,
            "VALOR_REAIS_OPERADORA" => round($taxaReaisOperadora, 2),
            "VALOR_OPERADORA_REAIS_TOTAL" => round($taxaReaisOperadora, 2),
            "VENDEDOR_ASSUME" => $vendedorAssume,
            "QTD_PARCELAS" => $qtdParcelas,
            "PARCELAS" => $dadosParcelas,
            "NUMERO_CARTAO" => $numero_Cartao
        );
    }

    public static function gerarDadosParcelas($json)
    {
        $datas = Str::vencimento(date('Y-m-d'), $json['QTD_PARCELAS']);

        $array_parcelas = array();

        foreach ($datas as $key => $data) {
            array_push($array_parcelas, array(
                "VENCIMENTO" => $data,
                "VALOR" => $json['VALOR_PARCELA'],
                "VALOR_ORIGINAL" => $json['VALOR_PARCELA_ORIGINAL'],
                "NUM_PARCELA" => ($key + 1),

                "TAXA_FINANCIAMENTO_PORCENTAGEM" => $json['TAXA_FINANCIAMENTO_PORCENTAGEM'],
                "TAXA_FINANCIAMENTO_REAIS" => Maths::roundDown($json['TAXA_FINANCIAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),
                "TAXA_PROCESSAMENTO_PORCENTAGEM" => $json['TAXA_PROCESSAMENTO_PORCENTAGEM'],
                "TAXA_PROCESSAMENTO_REAIS" => Maths::roundDown($json['TAXA_PROCESSAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),

                "SPREAD_FINANCIAMENTO_PORCENTAGEM" => $json['SPREAD_FINANCIAMENTO_PORCENTAGEM'],
                "SPREAD_FINANCIAMENTO_REAIS" => Maths::roundDown($json['SPREAD_FINANCIAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),
                "SPREAD_PROCESSAMENTO_PORCENTAGEM" => $json['SPREAD_PROCESSAMENTO_PORCENTAGEM'],
                "SPREAD_PROCESSAMENTO_REAIS" => Maths::roundDown($json['SPREAD_PROCESSAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),

                "TAXA_OPERADORA_FINANCIAMENTO_PORCENTAGEM" => $json['TAXA_OPERADORA_FINANCIAMENTO_PORCENTAGEM'],
                "TAXA_OPERADORA_FINANCIAMENTO_REAIS" => Maths::roundDown($json['TAXA_OPERADORA_FINANCIAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),
                "TAXA_OPERADORA_PROCESSAMENTO_PORCENTAGEM" => $json['TAXA_OPERADORA_PROCESSAMENTO_PORCENTAGEM'],
                "TAXA_OPERADORA_PROCESSAMENTO_REAIS" => Maths::roundDown($json['TAXA_OPERADORA_PROCESSAMENTO_REAIS'] / $json['QTD_PARCELAS'], 3),
            ));
        }

        //Precisão valor parcela
        $array_parcelas = Maths::calculaParcelaPrecisa('VALOR', $array_parcelas, $json['VALOR_TOTAL']);
        $array_parcelas = Maths::calculaParcelaPrecisa('VALOR_ORIGINAL', $array_parcelas, $json['VALOR_TOTAL_ORIGINAL']);
        //Precisão valor taxa reais
        $array_parcelas = Maths::calculaTaxasPrecisaParcelas('TAXA_FINANCIAMENTO_REAIS', array('TAXA_FINANCIAMENTO_REAIS', 'TAXA_PROCESSAMENTO_REAIS'), $array_parcelas, $json['TAXA_FINANCIAMENTO_REAIS'] + $json['TAXA_PROCESSAMENTO_REAIS']);
        //Precisão valor taxa spread reais
        $array_parcelas = Maths::calculaTaxasPrecisaParcelas('SPREAD_FINANCIAMENTO_REAIS', array('SPREAD_FINANCIAMENTO_REAIS', 'SPREAD_PROCESSAMENTO_REAIS'), $array_parcelas, $json['SPREAD_FINANCIAMENTO_REAIS'] + $json['SPREAD_PROCESSAMENTO_REAIS']);
        //Precisão valor taxa operadora reais
        $array_parcelas = Maths::calculaTaxasPrecisaParcelas('TAXA_OPERADORA_FINANCIAMENTO_REAIS', array('TAXA_OPERADORA_FINANCIAMENTO_REAIS', 'TAXA_OPERADORA_PROCESSAMENTO_REAIS'), $array_parcelas, $json['TAXA_OPERADORA_FINANCIAMENTO_REAIS'] + $json['TAXA_OPERADORA_PROCESSAMENTO_REAIS']);

        return $array_parcelas;
    }

    public static function trocaVirgulaPorPontoEViraDecimal($valorComVirgulaAoInvesDePonto)
    {
        $size = strlen($valorComVirgulaAoInvesDePonto);
        $char = substr($valorComVirgulaAoInvesDePonto, $size - 3, 1);

        if ($char === '.') {
            return $valorComVirgulaAoInvesDePonto;
        }

        $valorFormatado = str_replace(',', '.', $valorComVirgulaAoInvesDePonto);
        $valorFormatado = bcdiv($valorFormatado, 1, 2);
        return $valorFormatado;
    }

    // Métodos daqui en diante estão na classe errada

    public static function vencimento($data, $qtdParcelas)
    {
        $vencimentos = array();

        for ($i = 0; $i < $qtdParcelas; $i++) {
            $parcela = date('Y-m-d', strtotime($data . "+" . $i . " month"));
            $parcelaSeguinte = date('Y-m-d', strtotime($data . "+" . ($i + 1) . " month"));

            $mesAtual = date("m", strtotime($parcela));
            $mesSeguinte = date('m', strtotime($parcelaSeguinte));
            $vencimentoCorreto = Str::diferencaEntreMeses($mesAtual, $mesSeguinte);

            if (!$vencimentoCorreto) {
                $parcela = Str::ultimoDiaDoMesAnterior($parcela, $parcelaSeguinte);
                //$parcela = Str::verificarDiaSemana($parcela);
                $vencimentos[$i] = $parcela;
            } else {
                //$parcelaSeguinte = Str::verificarDiaSemana($parcelaSeguinte);
                $vencimentos[$i] = $parcelaSeguinte;
            }
        }

        return $vencimentos;
    }

    public static function diferencaEntreMeses($parcelaAtual, $parcelaSeguinte)
    {
        $diferenca = $parcelaSeguinte - $parcelaAtual;
        if (($parcelaSeguinte - $parcelaAtual) == 1 || ($parcelaSeguinte - $parcelaAtual) == -11 || ($parcelaSeguinte - $parcelaAtual) == 0) {
            return true;
        }
        return false;
    }

    public static function ultimoDiaDoMesAnterior($parcela, $parcelaSeguinte)
    {
        $parcelaSeguinte = date('Y-m-d', strtotime($parcelaSeguinte));
        $mes = date('m', strtotime($parcelaSeguinte));
        $ano = date('Y', strtotime($parcelaSeguinte));
        $qtdDeDias = date("t", mktime(0, 0, 0, (int)$mes - 1, '01', $ano));

        return $ano . '-' . str_pad($mes - 1, 2, 0, STR_PAD_LEFT) . '-' . $qtdDeDias;
    }

    public static function buscarTaxa($cpfcnpj, $qtdParcelas = null)
    {
        // Baseado na quantidade de parcelas, busca o valor da taxa
        // Primeiramente, checando a taxa do usuário, caso ele não posssua, buscar de seu respectivo Coban

        $taxaUsuario = new TaxaUsuario();

        if (filter_var($cpfcnpj, FILTER_VALIDATE_EMAIL)) {
            $vendedor = new Vendedor();
            $vendedor = $vendedor->vendedorExiste($cpfcnpj);

            if ($vendedor) {
                $cpfcnpj = $vendedor['CPFCNPJEMPRESA'];
            }
        }

        if ($qtdParcelas) {
            $taxas = $taxaUsuario->buscarTaxaFinanciamento($cpfcnpj, $qtdParcelas);
        } else {
            $taxas = $taxaUsuario->buscarTodasTaxasFinanciamento($cpfcnpj);
        }

        return $taxas;
    }

    public static function getDuplicates($array)
    {
        return array_unique(array_diff_assoc($array, array_unique($array)));
    }

    public static function taxaProcessamento($cpfcnpj)
    {
        return self::buscarTaxaPorTipo($cpfcnpj, 4);
    }

    public static function buscarTaxaPorTipo($cpfcnpj, $tipoTaxa)
    {
        $arrayAuxiliarJuros = array();
        $taxaUsuario = new TaxaUsuario();

        $arrayAuxiliarJuros['CPFCNPJ'] = $cpfcnpj;
        $arrayAuxiliarJuros['ID_TIPO_TAXA'] = $tipoTaxa;
        $retorno = $taxaUsuario->selecionarTaxa(json_encode($arrayAuxiliarJuros));
        $taxas = end($retorno);

        if (!$taxas) {
            $usuario = new Usuario();
            $dadosUsuario = $usuario->getUsuario(json_encode($arrayAuxiliarJuros));
            $coban = new Coban();
            $arrayAuxiliarJuros['ID_COBAN'] = $dadosUsuario['ID_COBAN'];
            $retorno = $coban->selecionarTaxa(json_encode($arrayAuxiliarJuros));
            $taxas = end($retorno);
        }

        return $taxas;
    }

    public static function taxaSaque($cpfcnpj)
    {
        return self::buscarTaxaPorTipo($cpfcnpj, 3);
    }

    public static function taxaAntecipacao($cpfcnpj)
    {
        return self::buscarTaxaPorTipo($cpfcnpj, 2);
    }

    public static function taxaBoleto($cpfcnpj)
    {
        return self::buscarTaxaPorTipo($cpfcnpj, 1);
    }

    public static function taxaRangeVenda($json)
    {
        $arrayAuxiliarJuros = array();
        $taxaUsuario = new TaxaUsuario();
        $cpfcnpj = Str::removeMascaras($json->CPFCNPJ);
        $arrayAuxiliarJuros['CPFCNPJ'] = $cpfcnpj;
        $arrayAuxiliarJuros['ID_TIPO_TAXA'] = '8';

        $taxaRangeVenda = end($taxaUsuario->selecionarTaxa(json_encode($arrayAuxiliarJuros)));

        if (empty($taxaRangeVenda)) {
            $usuario = new Usuario();
            $dadosUsuario = $usuario->getUsuario(json_encode($arrayAuxiliarJuros));

            $coban = new Coban();
            $arrayAuxiliarJuros['ID_COBAN'] = $dadosUsuario['ID_COBAN'];
            $taxaRangeVenda = end($coban->selecionarTaxa(json_encode($arrayAuxiliarJuros)));

            if (empty($taxaRangeVenda)) {
                $atividade = new Atividade();
                $taxaRangeVenda = $atividade->getAtividade($json->ID_ATIVIDADE);
                //$taxaRangeVenda['TAXA'] = $taxaRangeVenda['TAXA'];
                //$taxaRangeVenda['VALOR'] = $taxaRangeVenda['VALOR'];
            }
        }

        return $taxaRangeVenda;
    }

    public static function removeMascaras($string)
    {
        $string = str_replace('.', '', $string);
        $string = str_replace('/', '', $string);
        $string = str_replace('-', '', $string);
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

    public static function verificarDiaSemana($parcela)
    {
        // Se exlcui fins de semana, talvez devesse chamar uma função que verifica se cai num feriado
        if (Str::getDiaDaSemana($parcela) == "Sábado") {
            $parcela = strtotime($parcela . ' + 2 days');
        } elseif (Str::getDiaDaSemana($parcela) == "Domingo") {
            $parcela = strtotime($parcela . ' + 1 days');
        } else {
            $parcela = strtotime($parcela);
        }

        return date('Y-m-d', $parcela);
    }

    public static function getDiaDaSemana($timestamp)
    {
        $timestamp = strtotime($timestamp);
        $date = getdate($timestamp);
        $diaSemana = $date['weekday'];

        if (preg_match('/(sunday|domingo)/mi', $diaSemana)) {
            $diaSemana = 'Domingo';
        } elseif (preg_match('/(monday|segunda)/mi', $diaSemana)) {
            $diaSemana = 'Segunda';
        } elseif (preg_match('/(tuesday|terça)/mi', $diaSemana)) {
            $diaSemana = 'Terça';
        } elseif (preg_match('/(wednesday|quarta)/mi', $diaSemana)) {
            $diaSemana = 'Quarta';
        } elseif (preg_match('/(thursday|quinta)/mi', $diaSemana)) {
            $diaSemana = 'Quinta';
        } elseif (preg_match('/(friday|sexta)/mi', $diaSemana)) {
            $diaSemana = 'Sexta';
        } elseif (preg_match('/(saturday|sábado)/mi', $diaSemana)) {
            $diaSemana = 'Sábado';
        }

        return $diaSemana;
    }

    public function formataValorComQuatroCasasParaCentavosCielo($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts) > 1) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        $response = $response * 100;
        return $response;
    }

    // ===================================================================== jamais mexam nessas funções

    public static function mostraJuros(
        $vnum_mes,
        $vvlr_pres,
        $vvlr_prin,
        $vvlr_acess,
        $data_contrato,
        $parcela
    ) {






        /*

        echo $vnum_mes . "<br>";
        echo $vvlr_pres . "<br>";
        echo $vvlr_prin . "<br>";
        echo $vvlr_acess . "<br>";
        echo $data_contrato . "<br>";
        echo $parcela. "<br>";

         exit;
         */


        /*primeiro item da array � a informa��o de erro;
        vnum_mes      = Numero de Meses;
        vvlr_prin     = Valor Financiado;
        vvlr_acess    = Acess&oacute;rios;
        vvlr_pres     = Presta��o com juros;
        data_contrato = Data do contrato;
        parcela       = Vencimento da 1� Parcela;
        */

        $retorno = array();

        $erro = "";
        $retorno['erro'] = "";
        $vvlr_pres = str_replace(".", "", $vvlr_pres);
        $vvlr_pres = str_replace(",", ".", $vvlr_pres);

        $vvlr_prin = str_replace(".", "", $vvlr_prin);
        $vvlr_prin = str_replace(",", ".", $vvlr_prin);



        $vvlr_acess = str_replace(".", "", $vvlr_acess);
        $vvlr_acess = str_replace(",", ".", $vvlr_acess);

        if ($erro == "") {
            if (($vnum_mes != '') && ($vvlr_pres != '') && ($vvlr_prin != '') && ($vvlr_acess != '')) {
                if (($vvlr_pres * $vnum_mes) < $vvlr_prin) {
                    $erro = 'Total de Prestações menor que valor Principal!';
                    $retorno['erro'] = $erro;
                    return $retorno;
                }




                if (intval($vvlr_pres) > intval($vvlr_prin)) {
                    $erro = 'Juros ao mês superior a 100%!';
                    $retorno['erro'] = $erro;
                    return $retorno;
                }

                $v2 = intval($vvlr_prin) + intval($vvlr_acess);

                $a = 0;
                $b = 0;
                $p = $vvlr_pres;
                $vdif_dc = array();
                $primeiraParcela = $parcela;
                $vvlr_tot = 0;
                $ultimaParcela = Str::descobreUltimaParcela($primeiraParcela, $parcela, $vnum_mes);

                //$parcela      = $primeiraParcela;
                $vdif_dc[0]     = Str::calculaDiferencaEntreDatas($data_contrato, $ultimaParcela);
                $n = ($vnum_mes) * (-1);
                //n=(intval(vdif_dc[0][0]) / 30)*(-1);
                //echo "kkk-->". $vdif_dc[0][0] . '<br>n=' . $n . '<br>ultimaparcela' . $ultimaParcela."<br><br>";
                $vjur1 = 1;
                $vjur2 = 0;
                $vjur_fin1 = 1;
                $vjur_fin2 = 0;
                $vjur = 0.5;
                $vjuranox = 0;
                $vjur_fin = 0.5;
                $vjuranox_fin = 0;

                while (abs($v2 - $a) > 0.00001) {
                    $a = $p * ((1 - (pow((1 + $vjur), $n))) / $vjur);
                    if ($a < $v2) {
                        $vjur1 = $vjur;
                    } else {
                        $vjur2 = $vjur;
                    }
                    $vjur   = ($vjur1 + $vjur2) / 2;
                }
                $vjuranox = (pow((1 + floatval($vjur)), 12) - 1) * 100;
                $vjuranox = Str::arredonda($vjuranox, 5);
                $vjur = $vjur * 100;
                $vjur = Str::arredonda($vjur, 5);

                while (abs($vvlr_prin - $b) > 0.00001) {
                    $b = $p * ((1 - (pow((1 + $vjur_fin), $n))) / $vjur_fin);
                    if ($b < $vvlr_prin) {
                        $vjur_fin1 = $vjur_fin;
                    } else {
                        $vjur_fin2 = $vjur_fin;
                    }
                    $vjur_fin = ($vjur_fin1 + $vjur_fin2) / 2;
                    //echo 'b: ' . $b . '<br>princ: ' . $v2 . '<br>v2-b: ' . ($v2-$b) ;
                }
                //echo "()()()-".$vjur_fin."<br><br>";
                $vjuranox_fin = (pow((1 + floatval($vjur_fin)), 12) - 1) * 100;
                $vjuranox_fin = Str::arredonda($vjuranox_fin, 5);
                $vjur_fin = $vjur_fin * 100;
                $vjur_fin = Str::arredonda($vjur_fin, 5);
                //echo "()()()-".$vjur_fin."<br><br>";
                $vvlr_pres = Str::arredonda($vvlr_pres, 2);
                $vvlr_pres = str_replace(".", ",", $vvlr_pres);
                $vvlr_prin = Str::arredonda($vvlr_prin, 2);
                $vvlr_prin = str_replace(".", ",", $vvlr_prin);
                $vvlr_acess = Str::arredonda($vvlr_acess, 2);
                $vvlr_acess = str_replace(".", ",", $vvlr_acess);
                //echo "()()()-".$vvlr_pres."<br><br>";

                //echo 'vjur: ' . $vjur . '<br>vjurano: ' . $vjuranox . '<br>vjur2: ' . $vjur_fin . '<br>vjurano2: ' . $vjuranox_fin."<br><br>";
                $retorno['vjur'] = str_replace(".", ",", $vjur);
                $retorno['vjuranox'] = str_replace(".", ",", $vjuranox);
                $retorno['vjur_fin'] = str_replace(".", ",", $vjur_fin);
                $retorno['vjuranox_fin'] = str_replace(".", ",", $vjuranox_fin);
                $retorno['vvlr_prin'] = Str::milhar($vvlr_prin, $vvlr_prin);
                $retorno['vvlr_pres'] = Str::milhar($vvlr_pres, $vvlr_pres);
                $retorno['vvlr_acess'] = Str::milhar($vvlr_acess, $vvlr_acess);


                return Str::impressao($vnum_mes, $vvlr_pres, $vvlr_prin, $vvlr_acess, $retorno['vjur'], $retorno['vjuranox'], $retorno['vjur_fin'], $retorno['vjuranox_fin'], $data_contrato, $parcela);
            }
        }
    }

    public static function descobreUltimaParcela($primeiraParcela, $parcela, $vnum_mes)
    {
        $intval = substr($primeiraParcela, 0, 2);
        $pp_mes = substr($primeiraParcela, 3, 2);
        $pp_ano = substr($primeiraParcela, 6, 4);
        $p_dia = substr($parcela, 0, 2);
        $p_mes = substr($parcela, 3, 2);
        $p_ano = substr($parcela, 6, 4);
        $up_dia = $intval;
        $up_mes = intval($p_mes) + (intval($vnum_mes) - 1);
        $up_ano = $p_ano;

        if ($up_mes > 12) {
            $up_mes = $up_mes - 12;
            $up_ano = intval($up_ano) + 1;
        }
        if (($up_mes == 4) || ($up_mes == 6) || ($up_mes == 9) || ($up_mes == 11)) {
            if ($up_dia > 30) {
                $up_dia = 30;
            } else {
                $up_dia = $intval;
            }
        }
        if ($up_mes == 2) {
            if ($up_dia > 28) {
                if ($up_ano % 4 != 0) {
                    $up_dia = 28;
                } else {
                    $up_dia = 29;
                }
            }
        }
        $ultDiaStr = $up_dia;
        $ultMesStr = $up_mes;
        if (strlen($up_dia) < 2) {
            $ultDiaStr = "0" . $up_dia;
        }
        if (strlen($up_mes) < 2) {
            $ultMesStr = "0" . $up_mes;
        }
        $vdata = $ultDiaStr . '/' . $ultMesStr . '/' . $up_ano;

        return $vdata;
    }

    public static function impressao($vnum_mes, $vvlr_pres, $vvlr_prin, $vvlr_acess, $vjur, $vjuranox, $vjur_fin, $vjuranox_fin, $data_contrato, $parcela)
    {

        $vvlr_pres        = str_replace(".", "", $vvlr_pres);
        $vvlr_pres        = str_replace(",", ".", $vvlr_pres);
        $vjur_fin        = str_replace(".", "", $vjuranox_fin);
        $vjur_fin        = str_replace(",", ".", $vjuranox_fin);

        ////////////////////////////////////////////////////////////////////////
        // Se n�o tiver sido calculado juros, realiza o calculo agora
        ////////////////////////////////////////////////////////////////////////

        if ($vjur == "") {
            $vjur = 0;
        }
        //$vetjur=mostra_juros();


        ////////////////////////////////////////////////////////
        // L�gica e c�lculos das datas da tabela
        ////////////////////////////////////////////////////////
        $vvlr_tot    = 0;
        $vet         = array();
        $vdif_data     = array();


        $primeiraParcela = $parcela;
        $ultDiaAno         = Str::getUltimoDiaAno($data_contrato);
        $difAno             = Str::calculaDiferencaEntreDatas($data_contrato, $ultDiaAno);


        $strFormula        = array();

        ////////////////////////////////

        $vdif_dc     = Str::calculaDiferencaEntreDatas($data_contrato, $parcela);
        $mes_contrato = substr($data_contrato, 3, 2);
        $ano_contrato = substr($data_contrato, 6, 4);
        $adif_dc = array();
        $adiff_dc     = 0;
        $vdiff_data     = 0;
        $i_acerto = 0;
        if ($vdif_dc[0] > 30) { // se mais de 30 dias
            $i_acerto = 1;
            $adif_dc[0] = $vdif_dc[0] - 30;
            $adif_dc[1] = 30;
            if ($adif_dc[0] > 30) { // se mais de 60 dias
                $i_acerto = 2;
                $adif_dc[2] = $adif_dc[0] - 30;
                $adif_dc[3] = 0;
                $adif_dc[4] = 30;
            }
            $idx = 0;
            for ($i = 0; $i < sizeof($adif_dc); $i = $i + $i_acerto) {
                $adiff_dc += $adif_dc[$i];
                $v1 = (1 + $vjur_fin / 100);
                $v2 = abs($adiff_dc) / (365);
                $vvlr_desc1     = pow($v1, $v2);
                $vvlr_desc     = $vvlr_pres / $vvlr_desc1;
                $vvlr_desc     = Str::arredonda($vvlr_desc, 2);
                $vvlr_tot     = $vvlr_tot + $vvlr_desc;
                $str_vlr_desc     = str_replace(".", ",", $vvlr_desc);
                $idx++;
            }
            $vvlr_tot = (($vvlr_tot) / ($i_acerto + 1));
        }
        $i_acerto = 0;
        for ($init = 0; $init < (intVal($vnum_mes)); $init++) {
            if ($init != 0) {
                $proxData = Str::verProximaData($primeiraParcela, $parcela);
                $vdif_data = Str::calculaDiferencaEntreDatas($parcela, $proxData);
            } else {
                $proxData = $parcela;
                $vdif_data = $vdif_dc;
            }
            if (($vvlr_tot != 0) && ($init == 0)) {
                $vvlr_desc     = $vvlr_tot;
                $vvlr_desc     = Str::arredonda($vvlr_desc, 2);
                $str_vlr_desc     =  str_replace(".", ",", $vvlr_desc);
                $strFormula[($init + $i_acerto)] = Str::milhar($str_vlr_desc, $str_vlr_desc);
                $parcela = $proxData;
            } else {
                if (($vdif_data[0] == 28) || ($vdif_data[0] == 29)) {
                    $vdif_data[0] = 31;
                }
                $vdiff_data += $vdif_data[0];
                $v1 = (1 + $vjur_fin / 100);
                $v2 = abs($vdiff_data) / ($difAno[0]);
                $vvlr_desc1     = pow($v1, $v2);
                $vvlr_desc     = $vvlr_pres / $vvlr_desc1;
                $vvlr_desc     = Str::arredonda($vvlr_desc, 2);
                $vvlr_tot     = $vvlr_tot + $vvlr_desc;
                $str_vlr_desc     = str_replace(".", ",", $vvlr_desc);
                $strFormula[($init + $i_acerto)] = Str::milhar($str_vlr_desc, ($str_vlr_desc));
                $parcela = $proxData;
            }
        }


        $totalCount = sizeof($strFormula);



        $htmlRstTot = '';
        for ($i = 0; $i < $totalCount; $i++) {
            if ($i != $totalCount - 1) {
                $htmlRstTot .= $strFormula[$i] . ' + ';
            } else {
                $htmlRstTot .= $strFormula[$i];
            }
        }
        $str_vlr_prin = $vvlr_prin;
        $vvlr_prin     = str_replace(".", "", $vvlr_prin);
        $vvlr_prin     = str_replace(",", ".", $vvlr_prin);
        $vResultado     = floatval($vvlr_tot) - floatval($vvlr_prin); // Resultado de tudo menos o valor principal

        $str_Result     = str_replace(".", ",", (Str::arredonda($vResultado, 2)));
        /////////////////////////////////////////////////////////////////////
        // PARTE DO SISTEMA QUE DESCOBRE OS JUROS DO CET
        /////////////////////////////////////////////////////////////////////
        $jurosCET = Str::calculaJurosCET($vnum_mes, $vjuranox_fin, $vjur, $primeiraParcela, $data_contrato, $proxData, $vvlr_prin, $difAno, $vvlr_pres);
        ///////////////////////////////////////////////////////////////////////////////////


        $totalCount2 = sizeof($jurosCET[0]);
        $vlr_total2 = $jurosCET[1];
        $vResultado2 = floatval($vlr_total2) - floatval($vvlr_prin); // Resultado de tudo menos o valor principal
        $str_Result2 = str_replace(".", ",", (Str::arredonda($vResultado2, 2)));

        $juros_CET = $jurosCET[3];
        $vjuranox = str_replace(".", "", $vjuranox);
        $vjuranox = str_replace(",", ".", $vjuranox);
        $vjuranox = floatval($vjuranox);
        $vjuranox_fin = str_replace(".", "", $vjuranox_fin);
        $vjuranox_fin = str_replace(",", ".", $vjuranox_fin);
        $vjuranox_fin = floatval($vjuranox_fin);

        $vjur_fin     = (pow(1 + (floatval($juros_CET) / 100), (1 / 12)) - 1) * 100;

        $retorno = array();

        $retorno['vjur_fin']        = str_replace(".", ",", (Str::arredonda($vjur_fin, 5)));
        $retorno['vjuranox_fin']    = str_replace(".", ",", (Str::arredonda($juros_CET, 5)));

        $retorno['CET'] = str_replace(".", ",", (Str::arredonda($juros_CET, 2)));

        return $retorno;
    }

    public static function calculaJurosCET($vnum_mes, $vjuranox_fin, $vjur_mes, $parcela, $data_contrato, $proxData, $vvlr_prin, $difAno, $vvlr_pres)
    {
        $b          = 0;
        $vjur       = 0.5;
        $vjur_fin   = str_replace(".", "", $vjuranox_fin);
        $vjur_fin   = str_replace(",", ".", $vjur_fin);
        $vjur1      = 1;
        $vjur2      = 0;
        $vjur_fin1  = 1;
        $vjur_fin2  = 0;
        $vjur_mes       = str_replace(".", "", $vjur_mes);
        $vjur_mes       = str_replace(",", ".", $vjur_mes);
        $vjur_mes       = floatval($vjur_mes) / 100;
        $primeiraParcela = $parcela;
        $igual = 0;
        $denovo = 0;
        $vjur_fin_old = 0;
        $vjur_dia   = ((pow(1 + ($vjur_mes), (1 / 30))) - 1) / 100;
        $vjur_dia   = $vjur_dia * 100;
        $vvlr_prin_old = $vvlr_prin;



        while (abs($vvlr_prin - $b) > 0.00001) {
            $vvlr_tot = 0;
            $parcela            = $primeiraParcela;
            $data_niver_cntr = Str::getAniversarioContrato($data_contrato, $primeiraParcela);
            $vdif_dc        = Str::calculaDiferencaEntreDatas($data_contrato, $primeiraParcela);

            $mes_contrato   = substr($data_contrato, 3, 2);
            $ano_contrato   = substr($data_contrato, 6, 4);
            $adif_dc        = array();
            $adiff_dc       = 0;
            $vdiff_data         = 0;
            $i_acerto = 0;
            $vvlr_novo = 0;
            $dias_padrao = Str::quantidadeDias($mes_contrato, $ano_contrato);

            if ($vdif_dc[0] > $dias_padrao) { // se mais de 31 dias
                $i_acerto   = 1;
                $adif_dc1   = Str::calculaDiferencaEntreDatas($data_contrato, $data_niver_cntr);
                $adif_dc2   = Str::calculaDiferencaEntreDatas($data_contrato, $primeiraParcela);
                $qtdMesesDif    = Str::contaMeses($data_niver_cntr, $primeiraParcela);
                $qtdMesesDif = $qtdMesesDif - 1;
                $adif_dc        = $qtdMesesDif + ($adif_dc1[0] / $dias_padrao);
                $idx = 0;
                if ($vvlr_prin == $vvlr_prin_old) {
                    $adiff_dc = $adif_dc;
                    $v1 = (1 + $vjur_dia);
                    $v2 = abs($adif_dc1[0]);
                    $vvlr_desc1     = pow($v1, $v2);

                    $vvlr_desc  = $vvlr_prin * ($vvlr_desc1);
                    ///$vvlr_desc   = arredonda($vvlr_desc,2);

                    $vvlr_novo  = $vvlr_desc;
                    $str_vlr_desc   = str_replace(".", ",", $vvlr_desc);
                    $idx++;
                }
                $vvlr_tot_old = $vvlr_tot;
            }
            $i_acerto = 0;

            for ($init = 0; $init < (intval($vnum_mes)); $init++) {
                if ($init != 0) {
                    $proxData   = Str::verProximaData($primeiraParcela, $parcela);
                    $vdif_data  = Str::calculaDiferencaEntreDatas($parcela, $proxData);
                } else {
                    $proxData   = $parcela;
                    $vdif_data  = $vdif_dc;
                }

                if (($vvlr_tot != 0) && ($init == 0)) {
                    $vvlr_desc  = $vvlr_tot;
                    $vvlr_desc  = Str::arredonda($vvlr_desc, 2);
                    $str_vlr_desc   = str_replace(".", ",", $vvlr_desc);
                    $strFormula[($init + $i_acerto)] = Str::milhar($str_vlr_desc, $str_vlr_desc);
                    $parcela = $proxData;
                    $vdiff_data = $vdif_data[0];
                } else {
                    $vdiff_data += $vdif_data[0];
                    $v1 = (1 + $vjur_fin / 100);
                    $v2 = abs($vdiff_data) / (365);

                    $vvlr_desc1     = pow($v1, $v2);
                    $vvlr_desc  = $vvlr_pres / $vvlr_desc1;
                    $vvlr_desc  = Str::arredonda($vvlr_desc, 2);
                    $vvlr_tot   = $vvlr_tot + $vvlr_desc;
                    $str_vlr_desc = str_replace(".", ",", $vvlr_desc);
                    $strFormula[($init + $i_acerto)] = Str::milhar(($str_vlr_desc), ($str_vlr_desc));
                    $parcela = $proxData;
                }
            }
            $b = $vvlr_tot;
            if ($b < $vvlr_prin) {
                $vjur_fin1 = $vjur_fin;
            } else {
                $vjur_fin2 = $vjur_fin;
            }
            $vjur_fin = (floatval($vjur_fin1) + floatval($vjur_fin2)) / 2;
            if ($vjur_fin_old == $vjur_fin) {
                $igual++;
            } else {
                $igual = 0;
            }
            $vjur_fin_old = $vjur_fin;
            if ($igual == 10) {
                if ($denovo == 0) {
                    $vjur_fin1  = str_replace(".", "", $vjuranox_fin);
                    $vjur_fin1  = str_replace(",", ".", $vjur_fin1);
                    $vjur_fin1  = $vjur_fin1 * 2;
                    $denovo = 1;
                    $igual = 0;
                } else {
                    break;
                }
            }
        }
        $vjuranox_fin = (pow((1 + floatval($vjur_fin)), 12) - 1);
        $vjuranox_fin = (Str::arredonda($vjuranox_fin, 5));
        $vjur_fin = Str::arredonda($vjur_fin, 5);
        $vjur_fin = (Str::arredonda($vjur_fin, 5));

        return  array($strFormula, $vvlr_tot, $vjuranox_fin, $vjur_fin);
    }

    /*
     * @name calcularIdade Recebe a data no formato yyyy-mm-dd
     */
    public function calcularIdade($dataNascimento)
    {
        // Separa em dia, mês e ano
        list($ano, $mes, $dia) = explode('-', $dataNascimento);
        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365);
        return $idade;
    }

    public static function poezero($valor, $tNum)
    {
        $tValor = strlen($valor);
        $vFinal = $valor;
        $zeros = '';
        for ($i = 0; $i < ($tNum - $tValor); $i++) {
            @$zeros += '0';
        }
        $vFinal = "" . $zeros . $vFinal;
        return $vFinal;
    }

    public static function milenio($valor)
    {
        $tvalor = strlen($valor);
        if ($tvalor == 2) {
            if (($valor >= 94) && ($valor < 100)) {
                $vFinal = "19" . $valor;
            } else {
                $vFinal = "20" . $valor;
            }
        } else {
            $vFinal = $valor;
        }
        return ($vFinal);
    }


    public static function mostraData($data)
    {
        if ($data != '') {
            return (substr($data, 8, 2) . '/' . substr($data, 5, 2) . '/' . substr($data, 0, 4));
        }
    }

    public static function calculaDiferencaEntreDatas($dataInicio, $dataFim)
    {

        $initDia = substr($dataInicio, 0, 2);
        $initMes = substr($dataInicio, 3, 2);
        $initAno = substr($dataInicio, 6, 4);

        $fimDia = substr($dataFim, 0, 2);
        $fimMes = substr($dataFim, 3, 2);
        $fimAno = substr($dataFim, 6, 4);

        $datum1 = $initMes . "/" . $initDia . "/" . $initAno;
        $datum2 = $fimMes . "/" . $fimDia . "/" . $fimAno;


        $data1 = strtotime($datum1);
        $data2 = strtotime($datum2);




        $vdif_data = ((((($data2 - $data1)) / 60) / 60) / 24);
        $dt[0] = round($vdif_data);
        return $dt;
    }


    public static function arredonda($valor, $casas)
    {
        $novo = round($valor * pow(10, $casas)) / pow(10, $casas);
        return $novo;
    }

    public static function milhar($price)
    {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -3, 1) == '.') {
            $sents = '.' . substr($price, -2);
            $price = substr($price, 0, strlen($price) - 3);
        } elseif (substr($price, -2, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 2);
        } else {
            $sents = '.00';
        }
        $price = preg_replace("/[^0-9]/", "", $price);
        return number_format($price . $sents, 2, ',', '.');
    }

    public static function dezena($c)
    {
        $tam_c = strlen($c);
        $valor = $c;
        if ($tam_c == 1) {
            $valor = $valor . "0";
        }
        return $valor;
    }


    public static function getUltimoDiaAno($data)
    {
        $diDia = substr($data, 0, 2);
        $diMes = substr($data, 3, 2);
        $diAno = substr($data, 6, 4);

        $dfDia = $diDia;
        $dfMes = $diMes;
        $vdfAno = intval($diAno);
        $dfAno = $vdfAno + 1;
        if (($dfMes == 4) || ($dfMes == 6) || ($dfMes == 9) || ($dfMes == 11)) {
            if ($dfDia >= 31) {
                $dfDia = 30;
            } else {
                if ($dfMes == 2) {
                    if ($dfDia > 28) {
                        if (($dfAno % 4) != 0) {
                            $dfDia = 28;
                        } else {
                            $dfDia = 29;
                        }
                    }
                } else {
                    $dfDia = 31;
                }
            }
        }
        $ultDiaStr = $dfDia;
        $ultMesStr = $dfMes;

        if (strlen($dfDia) < 2) {
            $ultDiaStr = "0" . ($dfDia);
        }
        if (strlen($dfMes) < 2) {
            $ultMesStr = "0" . ($dfMes);
        }
        $vdata = $ultDiaStr . '/' . $ultMesStr . '/' . $dfAno;

        return $vdata;
    }

    public static function verProximaData($dataPrimeiraParcela, $dataUltimaParcela)
    {
        $dppDia =  substr($dataPrimeiraParcela, 0, 2);
        $dppMes =  substr($dataPrimeiraParcela, 3, 2);
        $dppAno = substr($dataPrimeiraParcela, 6, 4);
        $dupDia =  substr($dataUltimaParcela, 0, 2);
        $dupMes =  substr($dataUltimaParcela, 3, 2);
        if (substr($dupMes, 0, 1) == '0') {
            $dupMes = substr($dupMes, 1, 1);
        }
        $dupAno = substr($dataUltimaParcela, 6, 4);
        $proxDia = intval($dupDia);
        $proxMes = intval($dupMes) + 1;
        $proxAno = intval($dupAno);

        if ($dupMes == 12) {
            $proxMes = 1;
            $proxAno = $proxAno + 1;
        }
        if (($dupMes == 4) || ($dupMes == 6) || ($dupMes == 9) || ($dupMes == 11)) {
            if ($dupDia >= 30) {
                $proxDia = $dppDia;
            }
        } else {
            if (($dupMes == 2) || ($dupDia > 31)) {
                $proxDia = $dppDia;
            }
        }

        if ($proxMes == 2) {
            if ($proxDia > 28) {
                if (($proxAno % 4) != 0) {
                    $proxDia = 28;
                } else {
                    $proxDia = 29;
                }
            }
        }
        if (($proxMes == 4) || ($proxMes == 6) || ($proxMes == 9) || ($proxMes == 11)) {
            if ($proxDia >= 31) {
                $proxDia = 30;
            }
        }
        $proxDiaStr = $proxDia;
        $proxMesStr = $proxMes;
        if (strlen($proxDia) < 2) {
            $proxDiaStr = "0" . $proxDia;
        }
        if (strlen($proxMes) < 2) {
            $proxMesStr = "0" . $proxMes;
        }
        $vdata = $proxDiaStr . '/' . $proxMesStr . '/' . $proxAno;
        return $vdata;
    }

    public static function getAniversarioContrato($data_contrato, $primeiraParcela)
    {
        $dia_dc = substr($data_contrato, 0, 2);
        $dia_pp = substr($primeiraParcela, 0, 2);
        $mes_dc = substr($data_contrato, 3, 2);
        $ano_dt = substr($data_contrato, 6, 4);
        if (substr($mes_dc, 0, 1) == '0') {
            $mes_dc = substr($mes_dc, 1, 1);
        }
        $mes_dt  = intval($mes_dc);
        if (intval($dia_dc) > intval($dia_pp)) {
            $mes_dt++;
        }
        if (intval($mes_dt) > 12) {
            $ano_dt = intval($ano_dt) + 1;
            $mes_dt = intval($mes_dt) - 12;
        }
        $dt =  $dia_pp . '/' . Str::poezero($mes_dt, 2) . '/' . Str::milenio($ano_dt);

        return $dt;
    }

    public static function quantidadeDias($mes, $ano)
    {
        if (($mes == 2) || ($mes == 4) || ($mes == 6) || ($mes == 9) || ($mes == 11)) {
            if ($mes == 2) {
                if (($ano % 4) != 0) {
                    $dias = 28;
                } else {
                    $dias = 29;
                }
            } else {
                $dias = 30;
            }
        } else {
            $dias = 31;
        }

        return $dias;
    }

    public static function contaMeses($data1, $data2)
    {
        $mes1   = substr($data1, 3, 2);
        $mes2   = substr($data2, 3, 2);

        if (substr($mes1, 0, 1) == '0') {
            $mes1 = substr($mes1, 1, 2);
        }
        if (substr($mes2, 0, 1) == '0') {
            $mes2 = substr($mes2, 1, 2);
        }

        if (intval($mes1) > intval($mes2)) {
            //alert(parseInt(mes1) + ' > ' + parseInt(mes2));
            $mes1 = intval($mes1) - 12;
        }
        $dif = intval($mes2) - intval($mes1);
        return $dif;
    }

    public static function somarDias($dias)
    {
        return date('Y-m-d', strtotime('+' . $dias . ' days'));
    }

    public static function ocultarDigitos($str)
    {
        $strOcultada = "";
        for ($i = 0; $i < strlen($str); $i++) {
            $strOcultada .= "*";
        }
        return $strOcultada;
    }
    //====================================================

    public static function dataConciliacaoTelenet($data)
    {
        if (empty($data)) {
            return '';
        }

        $y = substr($data, 0, 4);
        $m = substr($data, 4, 2);
        $d = substr($data, 6, 2);

        return $y . '-' . $m . '-' . $d;
    }

    public static function horaConciliacaoTelenet($hora)
    {
        if (empty($hora)) {
            return '';
        }

        $hh = substr($hora, 0, 2);
        $mm = substr($hora, 2, 2);
        $ss = substr($hora, 4, 2);

        return $hh . ':' . $mm . ':' . $ss;
    }

    public static function formatDataDB($data)
    {
        if (!empty($data)) {
            $data = explode('/', $data);
            $data = implode('-', array_reverse($data));
        }
        return $data;
    }

    public static function formatarNomeCartao($nome)
    {
        $nome      = explode(" ", strtoupper(self::cleanStr($nome)));
        $posUltimo = count($nome) - 1;
        $primeiro  = $nome[0];
        $ultimo    = $nome[$posUltimo];
        unset($nome[0]);
        unset($nome[$posUltimo]);
        $meio = "";
        foreach ($nome as $n) {
            $meio .= " " . $n[0];
        }
        return $primeiro . $meio . " " . $ultimo;
    }

    public static function cleanStr($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        return $str;
    }

    /*
     * @name moedaSemMascara remove as máscaras de um valor monetário
     * @exemple (float) 99.99 retorna string "9999"
     * @author Jonas Vicente
     * @param float $valor
     * @return string
     */
    public static function moedaSemMascara($valor = 0)
    {
        $valor = number_format($valor, 2, '.', '');
        return str_replace('.', '', $valor);
    }

    public static function checkDigitsAreSequential($num)
    {
        if (is_numeric($num)) {
            $num = strval($num);
            return strpos('0123456789', $num) !== false || strpos('9876543210', $num) !== false;
        }
        return false;
    }

    public static function validarCpfCnpj($cpfcnpj, $killExecIfNotValid = false)
    {
        $cpfcnpj = self::removeMascaras($cpfcnpj);
        $isValid = true;
        switch (strlen($cpfcnpj)) {
            case 11:
                $isValid = Str::validarCpf($cpfcnpj, true);
                break;
            case 14:
                $isValid = Str::validarCnpj($cpfcnpj, true);
                break;
            default:
                $isValid = false;
                break;
        }
        if (!$isValid) {
            return $killExecIfNotValid
                ? RBMMensagens::printJson('D000-005', 400)
                : false;
        }
        return true;
    }

    public static function adicionaCaractereFinal($string, $caractere)
    {
        $lastChar = substr($string, -1);
        if ($lastChar !== $caractere) {
            $string .= $caractere;
        }
        return $string;
    }

    /**
     * @name inserirZerosEsquerdaDireita Retorna uma string com o valor formatado
     * @exemple inserirZerosEsquerdaDireita(123, 4, 0) => "0123"
     * @exemple inserirZerosEsquerdaDireita(123, 4, 2) => "012300"
     * @param float $value Valor para formatar
     * @param string|int $intLen Quantidade de caracteres da parte inteira do número
     * @param string|int $declLen Quantidade de caracteres da parte decimal do número
     * @return string
     */
    public static function inserirZerosEsquerdaDireita($value, $intLen, $declLen = 0)
    {
        if (!empty($value)) {
            $value = number_format($value, $declLen, '.', ''); // insere zeros à direita
            $value = explode('.', $value);
            $int   = $value[0];
            $dec   = isset($value[1]) ? $value[1] : "";
            $zerosEsq = "";
            for ($i = 0; $i < $intLen - strlen($int); $i++) {
                $zerosEsq .= "0";
            }
            $value = $zerosEsq . $int . $dec;
        }
        return $value;
    }

    public static function gerarTokenAtualizarDados()
    {
        $token = AMBIENTE == 'prod' ? self::createTokenNumbers(6) : '123456';
        return array(
            "token" => $token,
            "encToken" => hash('sha256', $token)
        );
    }

    public static function createTokenNumbers($qtd)
    {
        $t = "";
        for ($i = 0; $i < $qtd; $i++) {
            $t .= mt_rand(0, 9);
        }
        return (string) $t;
    }

    /**
     * Formata a url para o padrão
     * @author Jonas Vicente
     * @param string $url
     * @return string
     */
    public static function setUrlPattern(string $url): string
    {
        $url = explode('?', $url);
        return preg_replace('/\/$/', '', preg_replace('/^\//', '', trim($url[0])));
    }

    /**
     * Trata a entrada de dados para gravar em uma linha no arquivo de logs
     * @param mixed $data
     * @return string
     */
    public static function dataToJsonStr($data)
    {
        if (is_object($data) || is_array($data)) {
            return json_encode($data);
        }

        $json = json_decode(trim(preg_replace("/\r|\n/", "", $data)));
        if ($json === null) {
            return $data;
        }

        return json_encode($json);
    }

    public static function mascararCpfCnpj($cpfcnpj)
    {
        $cpfcnpj = self::removeMascaras($cpfcnpj);
        switch (strlen($cpfcnpj)) {
            case 11:
                return substr($cpfcnpj, 0, 3) . "." . substr($cpfcnpj, 3, 3) . "." . substr($cpfcnpj, 6, 3) . "-" . substr($cpfcnpj, 9);
            case 14:
                return substr($cpfcnpj, 0, 2) . "." . substr($cpfcnpj, 2, 3) . "." . substr($cpfcnpj, 5, 3) . "/" . substr($cpfcnpj, 8, 4) . "-" . substr($cpfcnpj, 12);
        }
        return $cpfcnpj;
    }

    /**
     * Transforma uma variável em json ou em string
     *
     * @param mixed $var
     * @return string
     */
    public static function toJsonIfObject($var): string
    {
        if (is_array($var) || is_object($var)) {
            return json_encode($var);
        }

        return (string) $var;
    }

    public static function getTipoPessoa(string $cpfCnpj): string
    {
        if (strlen($cpfCnpj) === 11) {
            return "PF";
        }

        if (strlen($cpfCnpj) === 14) {
            return "PJ";
        }

        return "";
    }

    public static function cutIfLengthIsBigger(string $string, int $length): string
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length);
        }

        return $string;
    }

    public static function toString($value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }
        if (is_numeric($value) || is_bool($value)) {
            return (string) $value;
        }
        return (string) $value;
    }

    public static function removeLastChar(string $string): string
    {
        return substr($string, 0, -1);
    }

    public static function maskLinhaDigitavel(string $linhaDigitavel): string
    {
        if (strlen($linhaDigitavel) === 47) {
            return self::mask(
                $linhaDigitavel,
                "#####.##### #####.###### #####.###### # ##############"
            );
        }

        if (strlen($linhaDigitavel) === 48) {
            return self::mask(
                $linhaDigitavel,
                "###########-# ###########-# ###########-# ###########-#"
            );
        }

        return $linhaDigitavel;
    }

    /**
     * Adiciona zeros à esquerda até que a string tenha o tamanho definido no
     * parâmetro $stringLength.
     * Se o tamanho de $string for maior que o valor de $stringLength será
     * retornado o valor de $string.
     *
     * @param string $string
     * @param int    $stringLength
     *
     * @return string
     */
    public static function zerosEsquerda(string $string, int $stringLength): string
    {
        while (strlen($string) < $stringLength) {
            $string = "0$string";
        }
        return $string;
    }
}
