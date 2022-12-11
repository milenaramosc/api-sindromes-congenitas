<?php

ini_set('display_errors', 0);
ini_set('display_startup_erros', 0);
error_reporting(0);

date_default_timezone_set(DEFAULT_TIME_ZONE);

setlocale(LC_ALL, 'pt_BR');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit;
}
