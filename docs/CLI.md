# RBM Conta digital CLI

Este cliente foi criado para automatizar a criação de arquivos básicos desta aplicação.

## Utilização

### help

Utilizado para obter exemplos de como utilizar os comandos

#### Listar todos os comandos disponíveis

```shell
php rbm
php rbm -h
php rbm --help
```

#### Buscar por algum comando

```shell
php rbm -h="comando"
php rbm --help="comando"
```

------------

### make:model

Utilizado para criar um arquivo de model

O novo arquivo é criado em ``app/model/``

```shell
php rbm --make:model nomeModel
```

Executando o comando acima, o seguinte arquivo é criado:

- ``app/model/NomeModel.php``

------------

### make:controller

Utilizado para criar um arquivo de controller e um de rotas

Os novos arquivos são criados em: 
- ``app/controller/``
- ``app/routes/``

```shell
php rbm --make:controller nomeController
```

Executando o comando acima, os seguintes arquivos são criados:

- ``app/controller/nomeController.php``
- ``app/routes/nome.inc.php``

O controller criado terá, automaticamente, a palavra "Controller" no final de seu nome

------------

### make:message

Utilizado para criar a pasta, arquivos e código de mensageria

Os novos arquivos são criados em ``app/messages/``. Nesta pasta é criada uma subpasta com o nome passado no comando e dentro dela ficam os arquivos ``Error.php`` e ``Success.php``

```shell
php rbm --make:message nomeDaPastaDeMensagens
```

Executando o comando acima, os seguinte arquivos são criados:

- ``app/messages/nomeDaPastaDeMensagens/Error.php``
- ``app/messages/nomeDaPastaDeMensagens/Success.php``

A última linha do arquivo ``app/core/handlers/response/README.md`` é atualizada com o range de mensagens criado, por exemplo ``- 48: nomeDaPastaDeMensagens``

------------

### make:module

Utilizado para criar as pastas e arquivos de um módulo

#### Criando um módulo básico

```shell
php rbm --make:module nomeDoModulo
```

Executando o comando acima teremos a seguinte estrutura:
- ``app/services/nomeDoModulo/NomeDoModuloInterface.php``
- ``app/services/nomeDoModulo/NomeDoModuloFactory.php``
- ``app/services/nomeDoModulo/products/NomeDoModuloFoo.php``

O produto ``Foo`` é criado por padrão e deve ser alterado.


#### Definindo produtos do módulo

Definidos pelo argumento -p ou --products

Os produtos devem estar em uma string e separados por vírgula

Se o argumento estiver vazio, o produto ``Foo`` é criado por padrão e deve ser alterado.

```shell
php rbm --make:module nomeDoModulo -p "nomeDoProduto1, nomeDoProduto2, ..."
php rbm --make:module nomeDoModulo --products "nomeDoProduto1, nomeDoProduto2, ..."
```

Executando um dos comandos acima teremos a seguinte estrutura:
- ``app/services/nomeDoModulo/NomeDoModuloInterface.php``
- ``app/services/nomeDoModulo/NomeDoModuloFactory.php``
- ``app/services/nomeDoModulo/products/NomeDoModuloNomeDoProduto1.php``
- ``app/services/nomeDoModulo/products/NomeDoModuloNomeDoProduto2.php``
- ``app/services/nomeDoModulo/products/NomeDoModulo[...].php``


#### Importante

O comando atualiza o ``composer.json`` com o namespace do novo módulo

Após a criação do módulo é necessário atualizar o autoload através do comando:
```shell
composer dump-autoload
```

------------
