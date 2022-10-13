<?php

namespace Modules\HtmlToPdf;

class Landscape implements OrientationInterface
{
    public function __toString()
    {
        return OrientationInterface::LANDSCAPE;
    }
}
