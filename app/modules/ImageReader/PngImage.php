<?php

namespace Modules\ImageReader;

class PngImage extends Image
{
    protected function init(): void
    {
        $this->gdImage = imagecreatefrompng($this->fullFilePath);
        if ($this->gdImage === false) {
            throw new \InvalidArgumentException("Algo inesperado ocorreu ao preparar a imagem");
        }
    }

    public function show(): void
    {
        header("Content-type: image/png");
        imagepng($this->gdImage);
        imagedestroy($this->gdImage);
    }
}
