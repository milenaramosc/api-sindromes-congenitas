<?php

namespace Modules\HtmlToPdf;

interface OrientationInterface
{
    public const PORTRAIT = 'Portrait';
    public const LANDSCAPE = 'Landscape';
    public function __toString();
}
