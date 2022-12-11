<?php

namespace Modules\Configuracoes;

use App\Core\Utils\Money;

abstract class Configuracao
{
    public const LIMITE_PAGAMENTO_BOLETO = 4;

    /** @var int Id da configuração */
    protected int $id;

    /** @var mixed Valor da configuração */
    protected $valor;

    public function __construct()
    {
        $this->setId();
        $this->setValor();
    }

    /**
     * Atribui o id da configuração
     *
     * @return void
     */
    abstract protected function setId();

    /**
     * Atribui o valor da configuração
     *
     * @return void
     */
    private function setValor(): void
    {
        $model = new ConfiguracoesModel();
        $this->valor = $model->get($this->id);
    }

    public function toString(): string
    {
        return (string) $this->valor;
    }

    public function toInteger(): int
    {
        return (int) $this->valor;
    }

    public function toFloat(): float
    {
        return Money::moedaBDTWO($this->toString());
    }
}
