<@php

namespace {productNamespace};

use {moduleNamespace}\{moduleName}Interface;
use Modules\Services\Service;
use Modules\Services\ServicesInterface;

class {moduleName}{productName} implements ServicesInterface, {moduleName}Interface
{
    public function __construct(Service $service)
    {
        /**
         * O objeto $service contém os dados necessários para realizar a requisição:
         * 
         * url,
         * login,
         * password,
         * token,
         * expiresIn
         * 
         * Utilize esses dados para remover as configurações do .env.php
         */
    }

    // Implemente os métodos declarados na interface {moduleName}Interface
}
