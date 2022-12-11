<?php

namespace CLI\Commands\Make;

use App\Model\Services;
use CLI\Utils\Dir;
use CLI\Utils\FileMaker;
use CLI\Utils\Message;
use CLI\Utils\Str;

/**
 * php rbm --make:module moduleName -p "a,b,c"
 */
class MakeModule extends Make
{
    /**
     * Nome do novo módulo
     *
     * @var string
     */
    private string $moduleName;

    /**
     * Namespace do novo módulo
     *
     * @var string
     */
    private string $moduleNamespace;

    /**
     * Namespace dos produtos do novo módulo
     *
     * @var string
     */
    private string $productNamespace;

    /**
     * Nome da pasta do novo módulo
     *
     * @var string
     */
    private string $moduleFolderName;

    /**
     * Caminho para a pasta dos módulos no sistema
     *
     * Por padrão, app/services
     *
     * @var string
     */
    private string $modulesPath;

    /**
     * Classe modelo de services
     *
     * @var Services
     */
    private Services $services;

    /**
     * Nome dos comandos para criação de produtos
     *
     * @var array
     */
    private const PRODUCTS_OPT_KEYS = [
        "p",
        "products"
    ];

    /**
     * Nome do comando
     *
     * @var string
     */
    private const COMMAND = "make:module";

    public function __construct(array $opts)
    {
        parent::__construct($opts);
        $this->modulesPath = "app" . DIRECTORY_SEPARATOR . "services";
        $this->services = new Services();
    }

    /**
     * Trata e retorna os argumentos passados nos parâmetros do comando
     *
     * @return array
     */
    protected function readArgs(): array
    {
        $moduleName = Str::pascalCase($this->opts[self::COMMAND]);
        unset($opts[self::COMMAND]);

        return [
            "moduleName" => $moduleName,
            "products" => $this->getProducts()
        ];
    }

    /**
     * Executa o comando solicitado
     *
     * @return void
     */
    public function execute(): void
    {
        $this->moduleName = $this->args['moduleName'];
        $products = $this->args['products'];

        $this->moduleNamespace = "App\\Services\\$this->moduleName";
        $this->productNamespace = "{$this->moduleNamespace}\\Products";

        $this->moduleFolderName = lcfirst($this->moduleName);
        $productsFolderName = $this->moduleFolderName . DIRECTORY_SEPARATOR . "products";

        Dir::create($this->modulesPath . DIRECTORY_SEPARATOR . $productsFolderName);

        $this->makeInterface();
        $this->makeFactory($products);
        $this->makeProducts($products);

        $this->updateComposer();

        Message::printLine(
            "Módulo "
                . $this->modulesPath
                . DIRECTORY_SEPARATOR
                . $this->moduleFolderName
                . " criado com sucesso!"
        );

        Message::printLine("Execute o comando 'composer dump-autoload' para atualizar o autoloader!");

        Message::printLine("Execute a query abaixo no banco de dados para criar o registro do módulo criado:");
        Message::printLine();
        Message::printLine($this->servicesInsertQuery($this->services->getNextId()));
        Message::printLine();
        Message::printLine("Adicione as configurações necessárias no registro inserido.");
    }

    /**
     * Trata os produtos recebidos pelo comando -p|--products
     *
     * @return array
     */
    private function getProducts(): array
    {
        $products = [];
        foreach ($this->opts as $productCommand => $productName) {
            if (in_array($productCommand, self::PRODUCTS_OPT_KEYS)) {
                if (strpos($productName, ',') !== false) {
                    $products = explode(',', $productName);
                } else {
                    $products[] = $productName;
                }
            }
        }

        if ($products === []) {
            $products[] = "Foo";
        }

        foreach ($products as $key => $value) {
            $products[$key] = Str::camelCase($value);
        }

        return $products;
    }

    /**
     * Cria o arquivo de interface para o módulo
     *
     * @return void
     */
    private function makeInterface(): void
    {
        $fileMaker = new FileMaker(
            $this->modulesPath
                . DIRECTORY_SEPARATOR
                . $this->moduleFolderName
                . DIRECTORY_SEPARATOR
                . $this->moduleName
                . "Interface",
            "Module",
            "Interface",
            [
                "{moduleNamespace}" => $this->moduleNamespace,
                "{moduleName}" => $this->moduleName
            ]
        );

        $fileMaker->make();
    }

    /**
     * Cria o arquivo de factory para o módulo
     *
     * @param array $products
     * @return void
     */
    private function makeFactory(array $products): void
    {
        $constProducts = "";
        foreach ($products as $key => $value) {
            if ($key === 0) {
                $constProducts .= "'$value' => '{$this->productNamespace}\\{$this->moduleName}$value',\n";
            } else {
                $constProducts .= "\t\t'$value' => '{$this->productNamespace}\\{$this->moduleName}$value',\n";
            }
        }

        $fileMaker = new FileMaker(
            $this->modulesPath
                . DIRECTORY_SEPARATOR
                . $this->moduleFolderName
                . DIRECTORY_SEPARATOR
                . $this->moduleName
                . "Factory",
            "Module",
            "Factory",
            [
                "{th}"              => '$th',
                "{product}"         => '$product',
                "{serviceId}"       => $this->services->getNextId(),
                "{moduleName}"      => $this->moduleName,
                "{service}"         => '$service',
                "{moduleNamespace}" => $this->moduleNamespace,
                "{newService}"      => 'new Service(self::SERVICE_ID)',
            ]
        );

        $fileMaker->make();
    }

    /**
     * Cria os arquivos de produtos para o módulo
     * com base no comando -p|--products
     *
     * @param array $products
     * @return void
     */
    private function makeProducts(array $products): void
    {
        foreach ($products as $productName) {
            $fileMaker = new FileMaker(
                $this->modulesPath
                    . DIRECTORY_SEPARATOR
                    . $this->moduleFolderName
                    . DIRECTORY_SEPARATOR
                    . "products"
                    . DIRECTORY_SEPARATOR
                    . "{$this->moduleName}$productName",
                "Module",
                "Product",
                [
                    "{productNamespace}" => $this->productNamespace,
                    "{productName}" => $productName,
                    "{moduleNamespace}" => $this->moduleNamespace,
                    "{moduleName}" => $this->moduleName,
                ]
            );

            $fileMaker->make();
        }
    }

    /**
     * Atualiza o arquivo composer.json com os
     * novos namespaces
     *
     * @return void
     */
    private function updateComposer()
    {
        $fileName = ABSOLUTE_MAIN_DIR . DIRECTORY_SEPARATOR . "composer.json";
        $json = file_get_contents($fileName);

        if ($json === null) {
            Message::end("Ocorreu um erro ao ler o arquivo $fileName");
        }

        $json = json_decode($json, true);

        if ($json === null) {
            Message::end("Ocorreu um erro ao ler o json do arquivo $fileName");
        }

        $json["autoload"]["psr-4"]["App\\Services\\{$this->moduleName}\\"]
            = "app/services/{$this->moduleFolderName}/";

        $json["autoload"]["psr-4"]["App\\Services\\{$this->moduleName}\\Products\\"]
            = "app/services/{$this->moduleFolderName}/products/";

        ksort($json["autoload"]["psr-4"]);

        $json = json_encode($json, JSON_PRETTY_PRINT);

        if (file_put_contents($fileName, $json) === false) {
            Message::end("Ocorreu um erro ao escrever o arquivo $fileName");
        }

        Message::printLine("$fileName atualizado");
    }

    private function servicesInsertQuery(int $id)
    {
        return "INSERT INTO services SET ID = $id, NOME_SERVICO = '" . Str::nameFormat($this->moduleName) . "'";
    }
}
