<?php

namespace App\Core\Handlers\Middlewares;

use App\Core\Handlers\Request\RequestHandler;

interface Middleware
{
    /**
     * Executa o tratamento do middleware
     *
     * @param RequestHandler $request
     * @return void
     */
    public function handle(RequestHandler $request): void;
}
