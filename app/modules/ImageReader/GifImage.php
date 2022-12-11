<?php

namespace Modules\ImageReader;

class GifImage extends Image
{
    protected function init(): void
    {
        $this->gdImage = imagecreatefromgif($this->fullFilePath);
        if ($this->gdImage === false) {
            throw new \InvalidArgumentException("Algo inesperado ocorreu ao preparar a imagem");
        }
    }

    public function show(): void
    {
        header("Content-type: image/gif");
        imagegif($this->gdImage);
        imagedestroy($this->gdImage);
    }
}
