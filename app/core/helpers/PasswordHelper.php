<?php

namespace App\Core\Helpers;

class PasswordHelper
{
    public string $password;

    public function __construct(string $password = '')
    {
        $this->password = $password;
    }

    /**
     * Criptografa uma senha
     *
     * @var string $this->password
     * @return string
     */
    public function encrypt(): string
    {
        return md5($this->password);
    }

    /**
     * Retorna TRUE se a senha é válida
     *
     * @var string $this->password
     * @param string $passwordHash
     * @return boolean
     */
    public function isValid(string $passwordHash): bool
    {
        return $this->encrypt($this->password) === $passwordHash;
    }

    /**
     * Retorna TRUE se a senha não é válida
     *
     * @var string $this->password
     * @param string $passwordHash
     * @return boolean
     */
    public function isNotValid(string $passwordHash): bool
    {
        return !$this->isValid($passwordHash);
    }
}
