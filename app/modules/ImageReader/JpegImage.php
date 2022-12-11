<?php

namespace Modules\ImageReader;

class JpegImage extends Image
{
    protected function init(): void
    {
        $this->gdImage = imagecreatefromjpeg($this->fullFilePath);
        if ($this->gdImage === false) {
            throw new \InvalidArgumentException("Algo inesperado ocorreu ao preparar a imagem");
        }
    }

    public function show(): void
    {
        header("Content-type: image/jpeg");
        imagejpeg($this->gdImage, null, 100);
        imagedestroy($this->gdImage);
    }
}
