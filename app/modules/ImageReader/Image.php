<?php

namespace Modules\ImageReader;

use App\Core\Exceptions\RBMException;

abstract class Image
{
    /**
     * @return resource|\GdImage
     */
    public $gdImage;

    public string $fullFilePath;
    public int $width;
    public int $height;
    private array $validFileExtensions;

    public function __construct(string $fullFilePath)
    {
        $this->validFileExtensions = ['jpg', 'jpeg', 'jpe', 'gif', 'bmp', 'png'];
        $this->setFullFilePath($fullFilePath);
        $this->setFileExtension();
        $this->setImageInfo();
        $this->init();
    }

    abstract public function show(): void;

    abstract protected function init(): void;

    private function setFullFilePath(string $fullFilePath): void
    {
        if (!file_exists($fullFilePath)) {
            throw new RBMException('Arquivo de imagem não encontrado!');
        }

        $this->fullFilePath = $fullFilePath;
    }

    private function setFileExtension(): void
    {
        $pathinfo = pathinfo($this->fullFilePath);
        $extension = strtolower($pathinfo['extension']);

        if (!in_array($extension, $this->validFileExtensions)) {
            throw new RBMException("Arquivo '{$this->fullFilePath}' não é uma imagem");
        }

        $this->fileExtension = $extension;
    }

    private function setImageInfo(): void
    {
        $info = getimagesize($this->fullFilePath);

        $this->width  = (int) $info[0];
        $this->height = (int) $info[1];
    }
}
