<?php

namespace App\Core\Utils;

class Requisicao
{
    public static function requestApi($method, $url, $data, $header = '', $returnHttpCode = false)
    {
        $curl = curl_init($url);
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        //        curl_setopt($curl, CURLOPT_SSLVERSION, 6); //Retirar essa linha caso esteja dando falha de SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_ENCODING, '');

        // EXECUTE:

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception(curl_error($curl), curl_errno($curl));
        }

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($curl);

        curl_close($curl);
        // if (!$response) die("Connection Failure");

        if ($returnHttpCode) {
            $statusCodeArray = array('httpStatusCode' => $statusCode);
            $responseAux = json_decode($response, 1);
            if ($responseAux != null) {
                $response = array_merge($responseAux, $statusCodeArray);
                $response = json_encode($response);
            } else {
                $response = json_encode($statusCodeArray);
            }
        }

        return $response;
    }

    public static function requestApiCapture($rota, $header)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $rota,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public static function requestApiCancelamento($rota, $header)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $rota,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public static function requestApiConferirVendaPagamento($rota, $header)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $rota,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public static function headerMinusculo()
    {
        //usado por conta de configurações do servidor de produção, duplica o header como as chaves minusculas
        //servidor no atual momento não vem o o header com a primeira letra maiuscula ex: "Jwt", e o codigo esperar "jwt"
        $header = apache_request_headers();

        foreach ($header as $key => $h) {
            $header[strtolower($key)] = $h;
        }

        return $header;
    }

    /**
     * @name getRequestJson Retorna json da requisição
     * @return string|null
     */
    public static function getRequestJson()
    {
        return $GLOBALS['json'];
    }

    /**
     * @name getRequestObj Retorna o objeto do json da requisição
     * @return object|null
     */
    public static function getRequestObj()
    {
        return json_decode($GLOBALS['json']);
    }

    /**
     * @name getRequestArray Retorna o array do json da requisição
     * @return array|null
     */
    public static function getRequestArray()
    {
        return json_decode($GLOBALS['json'], 1);
    }
}
