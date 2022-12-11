<?php

namespace Modules\Services;

use App\Model\Services;

class Service
{
    /**
     * ID do serviço
     *
     * @var ?integer
     */
    public ?int $id;

    /**
     * Nome do serviço
     *
     * @var ?string
     */
    public ?string $nomeServico;

    /**
     * Nome do parceiro que executará a funcionalidade
     *
     * @var ?string
     */
    public ?string $parceiro;

    /**
     * Classe de produto que será executada
     *
     * @var ?string
     */
    public ?string $class;

    /**
     * Url do serviço
     *
     * @var ?string
     */
    public ?string $url;

    /**
     * Url de autenticação do serviço
     *
     * @var ?string
     */
    public ?string $authUrl;

    /**
     * Login para gerar token do serviço
     *
     * @var ?string
     */
    public ?string $login;

    /**
     * Senha para gerar token do serviço
     *
     * @var ?string
     */
    public ?string $password;

    /**
     * Token gerado para acessar as rotas do serviço
     *
     * @var ?string
     */
    public ?string $token;

    /**
     * Data de expiração do token no formato YYYY-mm-dd H:i:s
     *
     * @var ?string
     */
    public ?string $expiresIn;

    /**
     * Serviço ativo
     *
     * @var boolean
     */
    public bool $ativo = true;

    public function __construct(int $id)
    {
        $this->set($id);
    }

    private function set(int $id)
    {
        $services = new Services();
        $services = $services->getById($id);

        if ($services === []) {
            $this->ativo = false;
        }

        $this->id = $id;

        $this->nomeServico = $services['NOME_SERVICO'];
        $this->parceiro    = $services['PARCEIRO'];
        $this->class       = $services['CLASS'];
        $this->url         = $services['URL'];
        $this->authUrl     = $services['AUTH_URL'];
        $this->login       = $services['LOGIN'];
        $this->password    = $services['PASSWORD'];
        $this->token       = $services['TOKEN'];
        $this->expiresIn   = $services['EXPIRES_IN'];
    }
}
