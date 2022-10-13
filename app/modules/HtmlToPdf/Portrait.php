<?php

namespace Modules\HtmlToPdf;

class Portrait implements OrientationInterface
{
    public function __toString()
    {
        return OrientationInterface::PORTRAIT;
    }
}
