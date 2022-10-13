<@php

namespace {moduleNamespace};

use Modules\Services\Service;

class {moduleName}Factory
{
    /**
     * ID da tabela 'services'
     * 
     * @var int
     */
    private const SERVICE_ID = {serviceId};

    /**
     * Retorna uma instância de {moduleName}Interface
     *
     * @return {moduleName}Interface
     */
    public function getInstance(): {moduleName}Interface
    {
        try {
            {service} = {newService};
            {product} = {service}->class;
            return new {product}({service});
        } catch (\Throwable {th}) {
            throw new \Exception("Erro no módulo '{moduleName}'", 0, {th});
        }
    }
}
