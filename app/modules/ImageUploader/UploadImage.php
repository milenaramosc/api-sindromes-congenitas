<?php

namespace Modules\ImageUploader;

use App\Core\Exceptions\RBMException;
use App\Core\Utils\Files;
use App\Model\RupDocs;
use Modules\ImageUploader\ImageToSave;

/**
 * Recebe o upload de imagens para gravar no servidor
 */
class UploadImage
{
    private ImageToSave $imageToSave;

    public function __construct(ImageToSave $imageToSave)
    {
        $this->imageToSave = $imageToSave;
    }

    /**
     * Salva uma imagem de cliente
     *
     * @return ImageToSave
     * @throws RBMException
     */
    public function save(): ImageToSave
    {
        $rupDocs = new RupDocs();

        $this->writeFile($this->imageToSave->cpfCnpj);
        return $rupDocs->save($this->imageToSave);
    }

    /**
     * Salva um arquivo
     *
     * @param string $dirName
     * @return void
     * @throws RBMException
     */
    private function writeFile(string $dirName): void
    {
        $saved = Files::saveUploadImg(
            $dirName,
            $this->imageToSave->fileName,
            $this->imageToSave->decodedImage
        );

        if ($saved === false) {
            throw new RBMException("Não foi possível gravar o documento");
        }
    }
}
