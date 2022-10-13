<?php

require_once 'vendor/autoload.php';

define('ABSOLUTE_MAIN_DIR', dirname(__FILE__));

require_once 'app/core/config/.env.php';
require_once 'app/core/globals/variables.php';
require_once 'app/core/globals/functions.php';
require_once 'app/core/config/server.php';
require_once 'app/core/config/dados-do-projeto.php';
require_once 'app/core/config/dominio.php';
require_once 'app/core/config/rotas-liberadas.php';

/**
 * Captura fatal errors não tratados da aplicação
 */
register_shutdown_function('catch_fatal_error');

/** Inclui os arquivos de mensageria */
requireMessagesFiles();

require_once 'app/core/handlers/middlewares/Validacao.php';

/** Inclui os arquivos de rotas */
requireRouteFiles();

try {
    (new App\Core\Handlers\Router\Runner())->run();
} catch (\Throwable $th) {
    (new App\Core\Handlers\Exceptions\ExceptionHandler($th, 'E000-008'))->print();
}
