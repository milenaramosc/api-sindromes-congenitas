# Padrões de código

## Conta Digital

Este documento busca padronizar a escrita de código fonte para a API Conta Digital.

### Nomenclatura de arquivos

#### Classes

Arquivos de classe devem contem o mesmo nome da classe, por exemplo:

```php
class Usuario
{
   ...
}
```

Seu arquivo deve ser 'Usuario.php'

#### Arquivos que serão incluídos

Devem conter o sulfixo '.inc', como por exemplo os arquivos de rotas:

analise.inc.php;

atividade.inc.php;

...

### Declaração de constantes

Constantes devem ser declaradas em maiúsculo seguindo o padrão **snake case**

Constantes declaradas dentro de uma classe são atributos dela. Portanto, devem ser declaradas com o comando `const`:

```php
class StatusTransacao
{
   const STATUS_AGUARDANDO = 1;
   const STATUS_APROVADO   = 2;
   const STATUS_CANCELADO  = 3;
}
```

Constantes globais devem utilizar o comando `define`:

```php
define('DB_HOST', 'dbhost.com');
define('DB_NAME', 'db_name');
define('DB_USER', 'db_user');
define('DB_PASS', 'db_pass');
```

### Declaração de variáveis

1. Variáveis devem conter ser declaradas seguindo o padrão **camel case** com a primeira letra **minúscula**.
2. Devem ser nomeadas de forma que facilite o entendimento sobre os dados armazenados nela.
   1. Uma variável que contenha o objeto de um usuário não deveria se chamar `$casa`
3. Variáveis que armazenam instâncias de classes devem conter o mesmo nome da classe (seguindo a mesma ideia do ponto 1).
4. Cuidado com abreviações.
   1. Pense que a abreviação escolhida pode não ser clara para outras pessoas.

Exemplos:

```php
$usuario = new Usuario();
$valorPago = 1000;
$linhaDigitavel = '23795792300000293474600090008274765099999980123';
```

### Declaração de funções
1. Variáveis devem conter ser declaradas seguindo o padrão **camel case** com a primeira letra **minúscula**.
```php
class Usuario
{
   public function getAll(): array {...}
}
```

2. Devem ser nomeadas de forma clara e descritiva.
3. Se possível, declare o tipo dos parâmetros e o qual tipo a função retorna
```php
class Usuario
{
   public function getAll(): array {...}
   public function insert(string $name, string $password, DateTimeInterface $birthDate): void {...}
}
```

### Declaração de classes

Declare as classes com o padrão **camel case** com a primeira letra **maiúscula**

#### Models
Devem ter o mesmo nome de suas respectivas tabelas no banco de dados

#### Controllers
Devem conter o nome de suas respectivas models seguidas do sulfixo ``Controller``