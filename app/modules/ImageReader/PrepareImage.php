<?php

namespace Modules\ImageReader;

use App\Core\Exceptions\RBMException;

class PrepareImage
{
    private int $imageType;

    public function __construct(int $imageType)
    {
        $this->imageType = $imageType;
    }

    public function instance(string $fullFilePath): Image
    {
        $classMap = [
            IMAGETYPE_GIF  => "GifImage",
            IMAGETYPE_JPEG => "JpegImage",
            IMAGETYPE_PNG  => "PngImage",
            IMAGETYPE_BMP  => "BmpImage"
        ];

        if (!array_key_exists($this->imageType, $classMap)) {
            throw new RBMException("Tipo de arquivo inválido!");
        }

        $class = "Modules\\ImageReader\\{$classMap[$this->imageType]}";
        return new $class($fullFilePath);
    }
}
