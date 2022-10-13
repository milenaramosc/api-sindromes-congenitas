<?php

namespace Modules\HtmlToPdf;

class Options
{
    private OrientationInterface $orientation;
    private ?int $marginTop;
    private ?int $marginBottom;

    public function __construct()
    {
        $this->orientation = new Portrait();
        $this->marginTop = null;
        $this->marginBottom = null;
    }

    public function get(): array
    {
        $options['orientation'] = (string) $this->orientation;

        if ($this->marginTop !== null) {
            $this->options['margin-top'] = $this->marginTop;
        }

        if ($this->marginBottom !== null) {
            $this->options['margin-bottom'] = $this->marginBottom;
        }

        return $options;
    }

    public function setOrientation(OrientationInterface $orientation): self
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function setMargins(?int $marginTop, ?int $marginBottom): self
    {
        $this->marginTop = $marginTop;
        $this->marginBottom = $marginBottom;

        return $this;
    }
}
