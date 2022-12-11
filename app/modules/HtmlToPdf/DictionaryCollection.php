<?php

namespace Modules\HtmlToPdf;

class DictionaryCollection
{
    /**
     * A coleção do dicionário
     *
     * @var string[]
     */
    public array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * Adiciona um termo a coleção do dicionário
     *
     * @param string $key
     * @param ?string $value
     *
     * @return self
     */
    public function add(string $key, ?string $value): self
    {
        $this->collection[$key] = $value ?? '';
        return $this;
    }

    public function get()
    {
        return $this->collection;
    }
}
