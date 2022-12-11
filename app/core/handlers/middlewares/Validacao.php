<?php

use App\Core\Handlers\Middlewares\Seguranca;

$request = App\Core\Handlers\Request\RequestHandler::getInstance();

if ($request->getMethod() === 'GET') {
    $json = json_encode($_GET);
} else {
    $json = file_get_contents('php://input');
}

$header = $request->lowerCaseHeader();

$requisicao = substr($_GET['url'], -1) === '/'
    ? '/' . $_GET['url'] . $_SERVER['REQUEST_METHOD']
    : '/' . $_GET['url'] . '/' . $_SERVER['REQUEST_METHOD'];

if (!in_array($requisicao, ROTAS_LIBERADAS)) {
    $json = json_decode($json);

    if (in_array($requisicao, ROTAS_TERCEIROS)) {
        $jwtTerceiros = new App\Model\JwtTerceiros();

        $logado = $jwtTerceiros->validarJwt($header);

        $json = array_merge((array)$json, $logado);
        $json = array_merge((array)$json, $header);

        if ($logado['status'] !== "LOGADO") {
            header("HTTP/1.0 403");
            exit(json_encode([
                'retorno'  => 'erro',
                'status'   => strtolower($logado['status']),
                'mensagem' => $logado['mensagem'],
            ]));
        }
    }
    // else {
    //     $usuario = new App\Model\Usuario();

    //     if (empty($header['Authorization']) || (!$usuario->validateJwt($header['Authorization']))) {
    //         header("HTTP/1.0 404 Not Found");
    //         exit(json_encode([
    //             "retorno"  => "erro",
    //             "mensagem" => "Acesso negado!",
    //             "cod"      => 1
    //         ]));
    //     }

    //     $cpfcnpj = $usuario->getId();
    //     $GLOBALS['CPFCNPJ'] = $cpfcnpj;

    //     if (isset($json->EMAILVENDEDOR) && !empty($json->EMAILVENDEDOR)) {
    //         if ($json->EMAILVENDEDOR !== $GLOBALS['CPFCNPJ']) {
    //             header("HTTP/1.0 404 Not Found");
    //             exit(json_encode([
    //                 "retorno"  => "erro",
    //                 "mensagem" => "Acesso negado!",
    //                 "cod"      => 2
    //             ]));
    //         }
    //     } elseif (isset($json->CPFCNPJ) && !empty($json->CPFCNPJ)) {
    //         if ($json->CPFCNPJ !== App\Core\Utils\Str::removeMascaras($GLOBALS['CPFCNPJ'])) {
    //             header("HTTP/1.0 302 Not Authorized");
    //             exit(json_encode([
    //                 "retorno"  => "erro",
    //                 "mensagem" => "Acesso negado!",
    //                 "cod"      => 3
    //             ]));
    //         }
    //     }

    //     // Rotas exclusivas para ADMIN, Operador ou Coban
    //     if (in_array($requisicao, ROTAS_ADM)) {
    //         $coban = new App\Model\Coban();
    //         if (!$coban->verificarCobanADM($cpfcnpj)) {
    //             if (in_array($requisicao, ROTAS_COBAN)) {
    //                 if (!$coban->permissaoRotaCoban($cpfcnpj)) {
    //                     header("HTTP/1.0 404 Not Found");
    //                     exit(json_encode([
    //                         "retorno"  => "erro",
    //                         "mensagem" => "Acesso negado!",
    //                         "cod"      => 4
    //                     ]));
    //                 }
    //             }
    //         }
    //     }

    //     if (is_array($json) && empty($json)) {
    //         $json['CPFCNPJ'] = $cpfcnpj;
    //     }
    //     if (is_array($json) && empty($json['CPFCNPJ'])) {
    //         $json['CPFCNPJ'] = $cpfcnpj;
    //     }

    //     if (is_object($json) && empty($json->CPFCNPJ)) {
    //         $json->CPFCNPJ = $cpfcnpj;
    //     }
    // }

    $json = json_encode($json);
}

$json = Seguranca::noInjection($GLOBALS['json']);
