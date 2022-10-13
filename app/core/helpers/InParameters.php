<?php

namespace App\Core\Helpers;

class InParameters
{
    public array $parameters;
    public string $sql;

    public function __construct(array $values)
    {
        $this->parameters = [];
        $this->sql = '';
        $this->prepare($values);
    }

    private function prepare(array $values): void
    {
        foreach ($values as $key => $value) {
            $paramName = ":inParameter$key";
            $this->parameters[$paramName] = $value;
            $this->sql .= $paramName . ",";
        }

        $this->sql = substr($this->sql, 0, -1);
    }
}
