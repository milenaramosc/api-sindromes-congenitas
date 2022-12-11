<?php

namespace CLI\Commands\Make;

use CLI\Utils\FileMaker;
use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php rbm --make:controller controllerName
 */
class MakeController extends Make
{
    /**
     * Nome do novo controller
     *
     * @var string
     */
    private string $controllerName;

    /**
     * Caminho para a pasta dos controllers no sistema
     *
     * Por padrão, app/controller
     *
     * @var string
     */
    private string $controllersPath;

    /**
     * Caminho para os arquivos de rotas
     *
     * Por padrão, app/routes
     *
     * @var string
     */
    private string $routesPath;

    /**
     * Nome do arquivo de rotas
     *
     * @var string
     */
    private string $routeName;

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:controller";

    /**
     * Namespace dos controllers
     *
     * @var string
     */
    private const CONTROLLER_NAMESPACE = "App\\Controller";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->controllersPath = "app" . DIRECTORY_SEPARATOR . "controller";
        $this->routesPath = "app" . DIRECTORY_SEPARATOR . "routes";
    }

    protected function readArgs(): array
    {
        $controllerName = Str::addToEndNotHaving(Str::pascalCase($this->opts[self::COMMAND]), "Controller");
        $routeName = Str::removeHaving(Str::camelCase($this->opts[self::COMMAND]), "Controller");

        return [
            "controllerName" => $controllerName,
            "routeName" => $routeName
        ];
    }

    public function execute(): void
    {
        $this->controllerName = $this->args['controllerName'];
        $this->routeName = "{$this->args['routeName']}.inc";
        $this->makeControllerFile();
        $this->makeRouteFile();
        Message::printLine("Controller criado com sucesso");
    }

    private function makeControllerFile()
    {
        $fileMaker = new FileMaker(
            $this->controllersPath
                . DIRECTORY_SEPARATOR
                . $this->controllerName,
            "Controller",
            "Controller",
            [
                "{namespace}" => self::CONTROLLER_NAMESPACE,
                "{controllerName}" => $this->controllerName,
            ]
        );

        $fileMaker->make();
    }

    private function makeRouteFile()
    {
        $fileMaker = new FileMaker(
            $this->routesPath
                . DIRECTORY_SEPARATOR
                . $this->routeName,
            "Controller",
            "Route",
            [
                "{namespace}" => self::CONTROLLER_NAMESPACE,
                "{controllerName}" => $this->controllerName,
            ]
        );

        $fileMaker->make();
    }
}
