<?php

namespace App\Core\Utils;

class Payzen
{
    private static $testRequest = "https://api.payzen.com.br/api-payment/V4/";
    private static $testKey = "Basic MzMwMTU4NDI6dGVzdHBhc3N3b3JkX1p6Wk9nSlFwT2ZGNUtGY0lldjFreHp2OGdRelpxR3FDdk43eVU0Z25nQ1dPbA==";

    /**
     * Função para realizar um pagamento.
     * Espera um array com as seguintes informações:
     * valor, codTransacao, numCartao, mes, ano, codigoSeguranca, cartao, nome, numParcela, email
     * Exemplo:
     * $data['valor'] = '992';
     * $data['codTransacao'] = '1234';
     * $data['numCartao'] = '4970100000000055';
     * $data['mes'] = '1';
     * $data['ano'] = '2021';
     * $data['codigoSeguranca'] = '123';
     * $data['cartao'] = 'VISA';
     * $data['nome'] = 'Teste da Silva';
     * $data['numParcela'] = '4';
     * $data['email'] = 'teste@teste.com';
     */
    public function createPayment($data)
    {

        $data = json_decode($data);
        $body = array();
        $body['amount'] = Str::removeMascaras($data->VALOR);
        $body['currency'] = 'BRL';
        $body['orderId'] = $data->CODTRANSACAO;

        $paymentForms = array();
        $paymentForms['paymentMethodType'] = 'CARD';
        $paymentForms['pan'] = str_replace(' ', '', $data->NUMEROCARTAO);
        $paymentForms['expiryMonth'] = substr($data->DTEXP, 0, 2);
        $paymentForms['expiryYear'] = substr($data->DTEXP, 3, 7);
        $paymentForms['securityCode'] = $data->CODSEG;
        $paymentForms['brand'] = strtoupper($data->BANDEIRA);
        $paymentForms['cardHolderName'] = $data->NOME;
        $paymentForms['installmentNumber'] = $data->PARCELAS;
        $paymentForms['identityDocumentNumber'] = $data->DOCUMENTO;
        $paymentForms['identityDocumentType'] = 'DNI';

        $body['paymentForms'] = array($paymentForms);

        $customer = array();
        $customer['email'] = $data->EMAIL;
        $body['customer'] = $customer;

        $cardOptions = array();
        $cardOptions['captureDelay'] = 5; //quantos dias após a venda da capture
        $transactionOptions = array();
        $transactionOptions['cardOptions'] = $cardOptions;
        $body['transactionOptions'] = $transactionOptions;

        $body = json_encode($body);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$testRequest . "Charge/CreatePayment",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . self::$testKey,
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
        //return $err ? "cURL Error #:".$err : $response;
    }

    /**
     * Função para capturar um pagamento.
     * Espera um array com as seguintes informações: uuids
     * Exemplo:
     * $data['uuids'] = 'bed8a8130e6141cfa11d8bb753db6bc7';
     */
    public function capture($data)
    {
        $body = array();
        $body['uuids'] = array($data->UUIDS);
        $body = json_encode($body);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$testRequest . "Transaction/Capture",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . self::$testKey,
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
        //        return $err ? "cURL Error #:".$err : $response;
    }

    /**
     * Função para cancelar um pagamento.
     * Espera um array com a seguintes informação: uuid
     * Exemplo:
     * $data['uuid'] = 'bed8a8130e6141cfa11d8bb753db6bc7';
     */
    public function cancel($data)
    {

        $data = json_decode($data);
        $body = array();
        $body['uuid'] = $data->UUIDS;
        $body = json_encode($body);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$testRequest . "Transaction/CancelOrRefund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . self::$testKey,
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
        //return $err ? "cURL Error #:".$err : $response;
    }

    /**
     * Função para reembolsar parcial ou por completo um pagamento.
     * Espera um array com a seguintes informação: uuid e modo.
     * Exemplo:
     * $data['uuid'] = 'bed8a8130e6141cfa11d8bb753db6bc7';
     * $data['modo'] = REFUND_ONLY';
     */
    public function refund($data)
    {

        $data = json_decode($data);
        $body = array();
        $body['uuid'] = $data->UUIDS;
        $body['amount'] = $data->VALOR;
        $body['resolutionMode'] = $data->MODO;
        $body = json_encode($body);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$testRequest . "Transaction/CancelOrRefund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . self::$testKey,
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
        //return $err ? "cURL Error #:".$err : $response;
    }
}
