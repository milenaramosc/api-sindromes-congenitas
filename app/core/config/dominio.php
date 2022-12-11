<?php

use App\Core\Utils\Str;

/**
 * Caminho absoluto para o diretório de imagens de usuários
 * @var string
 */
const ABSOLUTE_IMG_DIR = ABSOLUTE_MAIN_DIR . DIRECTORY_SEPARATOR . 'img';

/**
 * Caminho absoluto para o diretório de arquivos de logs
 * @var string
 */
const ABSOLUTE_LOG_DIR = ABSOLUTE_MAIN_DIR . DIRECTORY_SEPARATOR . 'logs';

/**
 * Caminho absoluto para o diretório de arquivos de views
 * @var string
 */
const ABSOLUTE_VIEW_DIR = ABSOLUTE_MAIN_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view';

/**
 * Caminho absoluto para o diretório de chaves
 * @var string
 */
const ABSOLUTE_KEYS_DIR = ABSOLUTE_MAIN_DIR . DIRECTORY_SEPARATOR . 'keys';

/**
 * Caminho absoluto para o diretório de relatório
 * @var string
 */
const ABSOLUTE_BILLET_DIR = ABSOLUTE_MAIN_DIR
    . DIRECTORY_SEPARATOR
    . 'app'
    . DIRECTORY_SEPARATOR
    . 'modules'
    . DIRECTORY_SEPARATOR
    . 'ExibicaoRelatorio';

/**
 * Caminho absoluto para o diretório de boletos
 * @var string
 */
const ABSOLUTE_BILLET_IMG_DIR = ABSOLUTE_BILLET_DIR . DIRECTORY_SEPARATOR . 'img';

/**
 * Protocolo da requisição (http ou https)
 * @var string
 */
$protocolo = (isset($_SERVER['HTTPS']))
    ? ((strtolower($_SERVER['HTTPS']) == 'on') ? "https://" : "http://")
    : "http://";

/**
 * Link raiz do projeto
 * Exemplo: http://localhost/api_conta_digital_cdc
 * @var string
 */
$rootDir = $protocolo . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

/**
 * $rootDir com / no final
 * Exemplo: http://localhost/api_conta_digital_cdc/
 * @var string
 */
$projectRoot = Str::adicionaCaractereFinal($rootDir, '/');

/**
 * Nome da aplicação
 * @var string
 */
$nomeApp = 'GRUPO SQUID';

/**
 * Nome da aplicação
 * @var string
 */
$file_ext = 'grupo_squid';

/**
 * Alias de $rootDir
 * @var string
 */
$raiz = $rootDir;

/**
 * Diretório das imagens públicas
 * @var string
 */
$dirImg = "/app/assets/img";

/**
 * @var string
 */
$dirFly = 'fly';
