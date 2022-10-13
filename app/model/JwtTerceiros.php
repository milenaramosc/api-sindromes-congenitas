<?php

namespace App\Model;

class JwtTerceiros
{
    private $dias = 1; // quantidade de dias para expirar o jwt
    private $numCriptografa = array(
        "etapa1" => 2,
        "etapa3" => 1,
        "etapa5" => 3
    );
    private function getDias()
    {
        return $this->dias;
    }

    private function getNumCriptografa($etapa)
    {
        return $this->numCriptografa[$etapa];
    }

    /* PARTE DE GERAR O JWT */

    public function gerarJwt($data)
    {

        $array = array();
        if (empty($data['cpfcnpj']) or empty($data['nome']) or empty($data['cod'])) {
            header("HTTP/1.0 400");
            $array['retorno'] = "erro";
            $array['mensagem'] = "Favor enviar os dados de acesso.";
            echo json_encode($array);
            exit;
        }

        $array['jwt'] = $this->create($data);
        $array['expira'] = $this->getDias();

        return $array;
    }

    public function create($data)
    {
        global $config;

        $header = json_encode(array("typ" => "JWT", "alg" => "HS256"));
        $data['data_expira'] = date('Y-m-d H:i', strtotime('+' . $this->getDias() . ' days'));

        $payload = json_encode($data);

        $hbase = $this->base64UrlEncode($header);

        $pbase = $this->gerarCriptografia($payload);

        $signature = hash_hmac("sha256", $hbase . "." . $pbase, md5('xxx'), true);

        $bsig = $this->base64UrlEncode($signature);

        $jwt = $hbase . "." . $pbase . "." . $bsig;




        return $jwt;
    }

    private function percorrerCriptografia($valor, $funcao, $quantidade)
    {

        for ($i = 0; $i < $quantidade; $i++) {
            $valor = $this->$funcao($valor);
        }

        return $valor;
    }

    private function gerarCriptografia($hash)
    {
        /*
        Primeira etapa
        Gerar base64UrlEncode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlEncode', $this->getNumCriptografa("etapa1"));

        /*
        Segunda etapa
        Trocar os caracteres "V" -> "xfs"; "T" -> "57"
        */
        $hash = str_replace("V", "xfs", $hash);
        $hash = str_replace("T", "57", $hash);

        /*
        Terceira etapa
        Gerar base64UrlEncode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlEncode', $this->getNumCriptografa("etapa3"));

        /*
        Quarta etapa
        Trocar os caracteres "h" -> "grj"
        */
        $hash = str_replace("h", "gjr", $hash);

        /*
        Quinta etapa (última)
        Gerar base64UrlEncode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlEncode', $this->getNumCriptografa("etapa5"));

        return $hash;
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /* =====================================================================================================*/

    /* PARTE DE VALIDAR O JWT */
    public function validarJwt($header)
    {

        $array = array();
        $authorization  = '';

        if (!empty($header['Authorization'])) {
            $authorization = $header['Authorization'];
        }

        if (!empty($header['authorization'])) {
            $authorization = $header['authorization'];
        }


        if (empty($authorization)) {
            header("HTTP/1.0 403");
            $array['retorno'] = "erro";
            $array['status'] = "erro";
            $array['mensagem'] = "Favor enviar os dados de acesso. Redirecionando para o login...";
            echo json_encode($array);
            exit;
        }

        return $this->validateJwt($authorization);
    }

    public function validateJwt($token)
    {

        $token = explode(' ', $token);
        $token = $token[1];

        $info = (array) json_decode($this->validate($token));




        $validar = array();

        if (!empty($info["cpfcnpj"]) && !empty($info["nome"]) && !empty($info["cod"]) && !empty($info["data_expira"])) {
            if (date('Y-m-d H:i') <= $info["data_expira"]) {
                $validar = array_merge($validar, $info);
                $validar['status'] = 'LOGADO';
                $validar['mensagem'] = 'Login realizado com sucesso.';
            } else {
                $validar['status'] = 'VENCIDO';
                $validar['mensagem'] = 'Favor realizar o login novamente.';
            }
        } else {
            $validar['status'] = 'ACESSO NEGADO';
            $validar['mensagem'] = 'Favor enviar os dados.';
        }

        return $validar;
    }

    private function validate($jwt)
    {
        global $config;
        $array = array();

        $jwt_splits = explode('.', $jwt);



        if (count($jwt_splits) == 3) {
            $signature = hash_hmac("sha256", $jwt_splits[0] . "." . $jwt_splits[1], md5('xxx'), true);

            $bsig = $this->base64UrlEncode($signature);

            if ($bsig == $jwt_splits[2]) {
                $array = $this->descriptografar($jwt_splits[1]);
            }
        }



        return $array;
    }

    private function descriptografar($hash)
    {
        /*
        Primeira etapa
        Gerar base64UrlDecode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlDecode', $this->getNumCriptografa("etapa5"));

        /*
        Segunda etapa
        Trocar os caracteres "gjr" -> "h"
        */
        $hash = str_replace("gjr", "h", $hash);

        /*
        Terceira etapa
        Gerar base64UrlDecode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlDecode', $this->getNumCriptografa("etapa3"));

        /*
        Quarta etapa
        Trocar os caracteres "57" -> "T"; "xfs" -> "V"
        */
        $hash = str_replace("57", "T", $hash);
        $hash = str_replace("xfs", "V", $hash);

        /*
        Quinta etapa
        Gerar base64UrlDecode de acordo com o número atribuído no array $numCriptografa
        */
        $hash = $this->percorrerCriptografia($hash, 'base64UrlDecode', $this->getNumCriptografa("etapa1"));

        return $hash;
    }

    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}
