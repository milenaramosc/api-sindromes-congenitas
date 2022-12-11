# Boas práticas

**Importante** Antes de se aprofundar neste documento, leia o documento de padrões de código.

## Indentação
- Códigos bem indentatos são mais legíveis;
- Utilize uma ferramenta de intentação automática em seu editor/IDE. Para o Visua Studio Code, recomendo a extensão PHP Intelephense.

## Coesão
- Escreva classes coesas, isto é, classes que façam bem uma única coisa;
- Classes coesas possuem apenas uma responsabilidade, por exemplo, uma classe modelo de usuário deve ser responsável apenas por funcionalidades ligadas à usuário;

## Encapsulamento
- Busque encapsular as classes, deixando público apenas o que for necessário de ser utilizado fora da classe;
- Getters e setters **não** são formas eficientes de aplicar encapsulamento:
    - Utilizar métodos de acesso a nossas propriedades faz sentido, desde que nós não utilizemos o retorno para tomar decisões que poderiam estar encapsuladas na classe.
    - Evite usar setters sempre que possível. Dessa forma você terá objetos imutáveis, ou seja, que não sofrem alteração. Esta técnica ajuda bastante na previsibilidade de seu código.
- Atributos podem ser públicos, desde que isso faça sentido para a classe;
- Métodos usados apenas na própria classe devem ser privados (private);
- Métodos usados apenas na própria classe e em suas filhas devem ser protegidos (protected);

## Acoplamento
- O acoplamento é a dependência entre classes;
- É necessário, pois nenhum sistema deve conter classes que funcionem apenas isoladamente;
- O alto acoplamento é um problema e deve ser controlado para evitar a geração de bugs ao realizar modificações em classes dependentes.

## SOLID
**Busque desenvolver seus códigos pensando em SOLID, mas saiba que é quase impossível desenvolver códigos que sigam 100% das ideias**

Utilizando as ideias do SOLID seus códigos ficarão mais limpos e legíveis.

### Single Responsability Principle
- Classes, métodos, funções, módulos e etc devem ter uma única responsabilidade bem definida;

### Open Closed Principle
- Cada classe deve conhecer e ser responsável por suas próprias regras de negócio;
- O princípio Aberto/Fechado (OCP) diz que um sistema deve ser aberto para a extensão, mas fechado para a modificação:
    - Isso significa que devemos poder criar novas funcionalidades e estender o sistema sem precisar modificar muitas classes já existentes.
- Uma classe que tende a crescer "para sempre" é uma forte candidata a sofrer alguma espécie de refatoração.

### Liskov Substitution Principle
- Embora a assinatura (tipo de variáveis de entrada e saída) de um método esteja sendo respeitada em uma herança, ainda assim podemos estar quebrando algum contrato;
- O Princípio de Substituição de Liskov (LSP) diz que devemos poder substituir classes base por suas classes derivadas em qualquer lugar, sem problema;
- Não devemos alterar um comportamento de um método estendido, mesmo que a assinatura seja mantida.

### Dependency Inversion Principle
- É mais interessante e mais seguro para o nosso código depender de interfaces - classes abstratas, assinaturas de métodos e interfaces em si, não apenas interfaces da linguagem - do que das implementações de uma classe;
- As interfaces são menos propensas a sofrer mudanças enquanto implementações podem mudar a qualquer momento;
- O Princípio de Inversão de Dependência (DIP) diz que implementações devem depender de abstrações e abstrações não devem depender de implementações;
- As interfaces devem definir apenas os métodos que fazem sentido para seu contexto.

### Interface Segregation Principle
- Uma classe pode implementar diversas interfaces;
- O Princípio de Segregação de Interfaces (ISP) diz que uma classe não deve ser obrigada a implementar um método que ela não precisa.


**Os pontos mais importantes que devem ser seguidos do SOLID neste projeto são o Single Responsability Principle, Open Closed Principle e Dependency Inversion Principle.**

## Tell Don't Ask
A ideia do Tell Don't Ask é não expor dados internos da classe, expor apenas as funcionalidades.

As regras de negócio de uma classe devem ficar na classe.

Exemplo de como **não** deve ser feito:
```php
<?php

class Usuario
{
    const NIVEL_ACESSO_ADMIN = 1;
    const NIVEL_ACESSO_NORMAL = 2;
    const NIVEL_ACESSO_RESTRITO = 3;

    private int $idUsuario;

    public function __construct(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNivelAcesso(): int
    {
        // consulta no banco de dados
    }
}

$usuario = new Usuario(1);
$perfilUsuario = $usuario->getNivelAcesso();

if ($perfilUsuario === Usuario::NIVEL_ACESSO_ADMIN) {
    // do something
}
```

Como deve ser:

```php
<?php

class Usuario
{
    const NIVEL_ACESSO_ADMIN = 1;
    const NIVEL_ACESSO_NORMAL = 2;
    const NIVEL_ACESSO_RESTRITO = 3;

    private int $idUsuario;

    public function __construct(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNivelAcesso(): int
    {
        // consulta no banco de dados
    }

    public function isAdmin(): bool
    {
        return $this->getNivelAcesso() === self::NIVEL_ACESSO_ADMIN;
    }

    public function isNormal(): bool
    {
        return $this->getNivelAcesso() === self::NIVEL_ACESSO_NORMAL;
    }

    public function isRestrito(): bool
    {
        return $this->getNivelAcesso() === self::NIVEL_ACESSO_RESTRITO;
    }
}

$usuario = new Usuario(1);

if ($usuario->isAdmin()) {
    // do something
}

```

## Evite usar else
- Utilize as ideias de **Fail Fast** e **Early Return**;
- Quanto mais condições, maior a complexidade ciclomática (menor desempenho);
- Maior legibilidade do código

#### Fail Fast
Realize as validações e lance as exceções o quanto antes no método.

Como não deve ser feito:
```php
<?php

class Usuario
{
    private string $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    private function setEmail(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $this->email = $email;
        } else {
            throw new \InvalidArgumentException('Email inválido');
        }
    }
}
```

Como deve ser feito:
```php
<?php

class Usuario
{
    private string $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    private function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            throw new \InvalidArgumentException('Invalid e-mail address');
        }

        $this->email = $email;
    }
}
```

Veja que na primeira implementação, a função ``setEmail()`` verifica primeiro se o email é válido para settar o valor do atributo. Já na segunda implementação, a função verifica primeiro se o email não é válido e estoura a exceção, não sendo necessário um ``else``.

#### Early Return
A ideia do **Early Return** é dar o retorno da função o quanto antes, ideia semelhante ao **Fail Fast**

Como não deve ser feito:
```php
<?php

class UsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function deletar(int $idUsuario): string
    {
        if ($this->usuario->deletar($idUsuario)) {
            return RBMMensagens::getJson('S123-000', 200);
        } else {
            return RBMMensagens::getJson('E123-000', 400);
        }
    }
}
```

Como deve ser feito:
```php
<?php

class UsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function deletar(int $idUsuario): string
    {
        if (!$this->usuario->deletar($idUsuario)) {
            return RBMMensagens::getJson('E123-000', 400);
        }

        return RBMMensagens::getJson('S123-000', 200);
    }
}
```

Veja que na primeira implementação a função ``deletar()`` verifica se o usuário foi excluído para retornar a mensagem de sucesso e caso contrário, é exibida a mensagem de erro. Já no segundo exemplo, é verificado se o usuário não foi excluído antes não sendo necessário um bloco ``else`` para dar o retorno de sucesso.

Para utilizar esses conceitos de Fail Fast e Early Return, tente inverter a condição do if. Funciona na maioria dos casos.


