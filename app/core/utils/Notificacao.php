<?php

namespace App\Core\Utils;

use App\Core\Database\Conexao;
use PDOException;

class Notificacao
{
    private static $conexao;

    public function __construct()
    {
        self::$conexao = Conexao::conexao();
    }


    public function listar($json, $page, $limit)
    {
        try {
            // if(empty($page)){
            // $page = 1;
            // }
            // if(empty($limit)){
            // $limit = 10;
            // }

            $json = json_decode($json);

            $cpfcnpj = Str::removeMascaras($json->CPFCNPJ);

            // $params = array(
            // ':CPFCNPJ' => $cpfcnpj
            // );


            $sql = " SELECT
            CODNOTIFICACAO, MENSAGEM, DATA, TITLE
            FROM
            notificacoes
            WHERE
            CPFCNPJ = :CPFCNPJ
            UNION SELECT
            n.CODNOTIFICACAO, n.MENSAGEM, n.DATA, n.TITLE
            FROM
            notificacoes AS n
            LEFT JOIN
            log_mensagem_nao_enviada AS l ON l.ID_MENSAGEM = n.CODNOTIFICACAO
            WHERE
            l.CPFCNPJ_USUARIO = :CPFCNPJ
            ORDER BY CODNOTIFICACAO DESC ";
            $rQuery = self::$conexao->prepare($sql);
            $rQuery->bindParam(':CPFCNPJ', $cpfcnpj);
            $rQuery->execute();

            $resultado = $rQuery->fetchAll(\PDO::FETCH_ASSOC);
            return $rQuery->rowCount() > 0 ? $resultado : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarNotificacoesAdm($page, $limit)
    {
        try {
            $sql = "SELECT CPFCNPJ, CODNOTIFICACAO, MENSAGEM, DATA, TITLE FROM notificacoes WHERE true AND ENVIO_ADM = 'S' GROUP BY MENSAGEM ORDER BY CODNOTIFICACAO DESC";

            if (!empty($limit)) {
                $pagination = new Pagination();
                $result = $pagination->pagination($sql, array(), $page, $limit);
                return $result;
            } else {
                $rQuery = self::$conexao->prepare($sql);
                $rQuery->execute();
                return $rQuery->rowCount() > 0 ? $rQuery->fetchAll(\PDO::FETCH_OBJ) : false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @name salvarMensagemDispositivoCadastrado Insere o log de notificações enviadas
     * @param string $mensagemenviada Json do retorno do envio da notificação
     * @param string $dadosdispositivoCadastrado Json dos dados do dispositivo do usuário
     * @param string $json Json contendo o OPERATIONAL_SYSTEM
     * @param string $message Texto enviado no body da notificação
     * @param string $title Título enviado na notificação
     */
    public function salvarMensagemDispositivoCadastrado($mensagemenviada, $dadosdispositivoCadastrado, $json, $message, $title, $envioAdm = null)
    {

        try {
            $json = json_decode($json);
            $dadosdispositivoCadastrado = json_decode($dadosdispositivoCadastrado);
            $mensagemenviada = json_decode($mensagemenviada);

            $todos    = (!empty($todos) && $todos === 'S') ? $todos = 'S' : $todos = 'N';
            $envioAdm = (!empty($envioAdm) && $envioAdm === 'S') ? $envioAdm = 'S' : $envioAdm = 'N';
            $cpfcnpj = Str::removeMascaras($dadosdispositivoCadastrado->CPFCNPJ);
            $iddispositivo = Str::removeMascaras($dadosdispositivoCadastrado->ID_DISPOSITIVO);

            $messageId = end($mensagemenviada->results);
            $devicetoken = $dadosdispositivoCadastrado->NOTIFICATION_TOKEN;
            $message_id = $messageId->message_id;
            $multicast_id = $mensagemenviada->multicast_id;
            $operationalsystem = $json->OPERATIONAL_SYSTEM;

            $header = Requisicao::headerMinusculo();

            $model = $header['model'];
            $brand = $header['manufacturer'];

            $sql = "INSERT INTO notificacoes 
                            (
                                DEVICETOKEN,
                                MENSAGEM,
                                DATA,
                                MESSAGE_ID,
                                MULTICAST_ID,
                                CPFCNPJ,
                                ID_DISPOSITIVOLOGADO,
                                ID_DISPOSITIVONOVO,
                                MODEL,
                                BRAND,
                                OPERATIONAL_SYSTEM,
                                TITLE,
                                IP,
                                TODOS,
                                ENVIO_ADM                      
                            ) 
                            
                            VALUES (:DEVICETOKEN, :MENSAGEM, NOW(), :MESSAGE_ID, :MULTICAST_ID, :CPFCNPJ, :ID_DISPOSITIVOLOGADO, :ID_DISPOSITIVONOVO, :MODEL, :BRAND, :OPERATIONAL_SYSTEM, :TITLE, :IP, :TODOS, :ENVIO_ADM)";

            $rQuery = self::$conexao->prepare($sql);

            $rQuery->bindParam(':DEVICETOKEN', $devicetoken);
            $rQuery->bindParam(':MENSAGEM', $message);
            $rQuery->bindParam(':MESSAGE_ID', $message_id);
            $rQuery->bindParam(':MULTICAST_ID', $multicast_id);
            $rQuery->bindParam(':CPFCNPJ', $cpfcnpj);
            $rQuery->bindParam('ID_DISPOSITIVOLOGADO', $dadosdispositivoCadastrado->ID_DISPOSITIVO);
            $rQuery->bindParam(':ID_DISPOSITIVONOVO', $iddispositivo);
            $rQuery->bindParam(':MODEL', $model);
            $rQuery->bindParam(':BRAND', $brand);
            $rQuery->bindParam(':OPERATIONAL_SYSTEM', $operationalsystem);
            $rQuery->bindParam(':TITLE', $title);
            $rQuery->bindParam(':IP', $header['ipAddress']);
            $rQuery->bindParam(':TODOS', $todos);
            $rQuery->bindParam(':ENVIO_ADM', $envioAdm);

            $rQuery->execute();
            return self::$conexao->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }


    public static function sendFCM($devicetoken, $message, $sistemaOperacional, $title, $novoId)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $header = Requisicao::headerMinusculo();
        $modelo = $header['model'];
        $novoId = $novoId != "N" ? "authorize_new_device" : null;
        $fabricante = $header['manufacturer'];
        $idDispositivo = isset($header['id']) ? $header['id'] : $header['id'];
        $ip = $header['ipAddress'];

        $Localizacao = Notificacao::localizarIp($ip);
        $Localizacao = json_decode($Localizacao);
        $cidade = $Localizacao->city;
        $uf = $Localizacao->region;

        if ($sistemaOperacional == 'ios') {
            $fields = array(
                'to' => $devicetoken,
                'collapse_key' => "new_message",
                'delay_while_idle' => false,
                'notification' => array(
                    "vibrate" => true,
                    "playSound" => true,
                    "visibility" => "public",
                    "priority" => "max",
                    "importance" => "max",
                    "title" => $title,
                    "body" => $message,
                    "color" => "#35768c",
                    "largeIcon" => "ic_notification",
                    "smallIcon" => "ic_notification",
                    "data" => "{\"action\": \"authorize_new_device\", \"model\": \"$modelo\", \"brand\": \"$fabricante\", \"localization\": \"$cidade, $uf\", \"id\": \"$idDispositivo\"}"
                )
            );
        } else {
            $fields = array(
                'to' => $devicetoken,
                'collapse_key' => "new_message",
                'delay_while_idle' => false,
                'notification' => array(
                    "title" => $title,
                    "body" => $message,
                    "color" =>  "#35768c",
                    "largeIcon" =>  "ic_notification",
                    "smallIcon" => "ic_notification",
                    "action" => $novoId,
                ),
                "data" =>  array(
                    "action" => $novoId,
                    "model" => $modelo,
                    "brand" => $fabricante,
                    "localization" => $cidade . ', ' . $uf,
                    "id" => $idDispositivo
                ),
            );
        }

        $fields = json_encode($fields);
        $headers = array(
            "Authorization: key=" . FIREBASE_SERVER_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        // remover após os testes
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, 1);

        $json = array(
            'device_token' => $devicetoken,
            'result'       => $result
        );

        return $json;
    }

    public static function buscarToken($token)
    {

        $url = 'https://iid.googleapis.com/iid/v1:batchImport';

        $fields = array(
            'application' => $GLOBALS['appDomainName'],
            'sandbox' => true,
            'apns_tokens' => array($token)

        );



        $fields = json_encode($fields);

        $headers = array(
            "Authorization: key=AAAAjF2Vq-Y:APA91bGK8-KTrfJrnDCcGFZvjP0wpUMgi0w0-nickynqP_kToLbzY9BVdDbUJGYMRLIpYf9POOk5H4Ikk1AQwhzwQ3kWNqiEa3BgtpvVRXl7ca_oRSgweV4ZGighY5FJKyMh8SoT18YI8okfwYQcONymAJX23YypcQ",
            "Content-Type: application/json"
        );



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }


    public static function localizarIp($ip)
    {


        $url = 'http://ip-api.com/json/' . $ip;




        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => ""

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        return $response;
    }

    public function listarDestinatarios($idMensagem)
    {
        try {
            $sql = "SELECT 
                            l.ID_MENSAGEM, u.NOME, l.CPFCNPJ_USUARIO,n.MENSAGEM, n.TODOS, n.DATA, n.TITLE
                        FROM
                            log_mensagem_nao_enviada AS l
                                LEFT JOIN
                            usuarios AS u ON u.CPFCNPJ = l.CPFCNPJ_USUARIO
                                LEFT JOIN
                            notificacoes AS n ON n.CODNOTIFICACAO = l.ID_MENSAGEM
                        WHERE
                            l.ID_MENSAGEM = :ID_MENSAGEM
                                AND l.ENVIADO = 'S'
                        ORDER BY u.NOME ASC";

            $rQuery = self::$conexao->prepare($sql);
            $rQuery->bindParam(':ID_MENSAGEM', $idMensagem);
            $rQuery->execute();

            return $rQuery->rowCount() > 0 ? $rQuery->fetchAll(\PDO::FETCH_OBJ) : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertLogMsgEnviada($cpfcnpj_usuario, $id_mensagem, $enviado)
    {

        try {
            $sql = "INSERT INTO log_mensagem_nao_enviada(CPFCNPJ_USUARIO, ID_MENSAGEM, DATA, ENVIADO) VALUES (:CPFCNPJ_USUARIO, :ID_MENSAGEM, NOW(), :ENVIADO)";
            $rQuery = self::$conexao->prepare($sql);
            $rQuery->bindValue(':CPFCNPJ_USUARIO', $cpfcnpj_usuario);
            $rQuery->bindValue(':ID_MENSAGEM', $id_mensagem);
            $rQuery->bindValue(':ENVIADO', $enviado);

            return $rQuery->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPJ($json, $page, $limit)
    {
        try {
            if (empty($page)) {
                $page = 1;
            }
            if (empty($limit)) {
                $limit = 10;
            }


            $cpfcnpj = Str::removeMascaras($json["CPFCNPJ"]);

            $params = array(
                ':CPFCNPJ' => $cpfcnpj
            );

            $sql = "SELECT nf.CODNOTIFICACAO, nf.MENSAGEM, nf.DATA, nf.TITLE,DATE_FORMAT(nf.DATA, '%H:%i%:%s') AS HORA 
            FROM notificacoes nf
            RIGHT JOIN notificacoes n ON n.CODNOTIFICACAO = nf.CODNOTIFICACAO
            LEFT JOIN log_mensagem_nao_enviada AS l ON l.ID_MENSAGEM = n.CODNOTIFICACAO
            WHERE nf.CPFCNPJ = :CPFCNPJ OR l.CPFCNPJ_USUARIO = :CPFCNPJ ORDER BY CODNOTIFICACAO DESC";


            $pagination = new Pagination();
            $result = $pagination->pagination($sql, $params, $page, $limit);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
}
