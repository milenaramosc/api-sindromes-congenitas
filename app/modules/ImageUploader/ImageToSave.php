<?php

namespace Modules\ImageUploader;

use App\Core\Exceptions\RBMException;
use App\Core\Utils\Files;
use App\Core\Utils\UUID;
use App\Model\RupDocs;
use App\Model\RupTipoAnexos;
use DomainException;

class ImageToSave
{
    /**
     * CPF/CNPJ do cliente
     *
     * @var string
     */
    public string $cpfCnpj;

    /**
     * Nome do arquivo
     *
     * @var string
     */
    public string $fileName;

    /**
     * ID do tipo de anexo (ruptipoanexos)
     *
     * @var integer
     */
    public int $category;

    /**
     * Imagem em base64
     *
     * @var string
     */
    public string $imageBase64;

    /**
     * Caminho para a imagem no fly
     *
     * @var string
     */
    public string $flyLink;


    public string $cnpj;

    public string $tipoRep;
    /**
     * Imagem descriptografada
     *
     * @var string
     */
    public string $decodedImage;

    public bool $exists = false;

    private RupDocs $rupDocs;

    /**
     * @param string $cpfCnpj CPF/CNPJ do cliente
     * @param integer $category ID do tipo de anexo (ruptipoanexos)
     * @param string $imageBase64 Base64 da imagem
     */
    public function __construct(
        string $cpfCnpj,
        int $category,
        ?string $imageBase64 = '',
        ?string $tipoRep = '',
        ?string $cnpj = null
    ) {
        $this->rupDocs = new RupDocs();

        $this->cpfCnpj     = $cpfCnpj;
        $this->imageBase64 = $imageBase64;
        $this->cnpj = $cnpj;
        $this->tipoRep = $tipoRep;


        $this->setCategory($category);
        $this->decodeImage();
        $this->setFileName();
    }

    /**
     * Descriptografa a imagem em base64
     *
     * @return void
     * @throws RBMException
     */
    private function decodeImage(): void
    {
        $decoded = base64_decode($this->imageBase64);
        if ($decoded === false) {
            throw new RBMException('Não foi possível ler a imagem');
        }

        $this->decodedImage = $decoded;
    }

    private function setFileName()
    {
        $fileExtension = Files::fileExtension($this->decodedImage);

        if ($fileExtension === '') {
            throw new DomainException("Não foi possível identificar a extensão da imagem");
        }

        $fileName = $this->existingFileName();
        if ($fileName === '') {
            $fileName = UUID::v4();
        }

        $this->fileName = "$fileName.$fileExtension";
    }

    /**
     * Atribui a categoria realizando sua validação
     *
     * @param integer $category
     * @return void
     * @throws RBMException
     */
    private function setCategory(int $category): void
    {
        $rupTipoAnexos = new RupTipoAnexos();

        if ($rupTipoAnexos->isNotValid($category)) {
            throw new RBMException("Tipo de documento '$category' não reconhecido");
        }

        $this->category = $category;
    }

    private function existingFileName(): string
    {
        $fileName = $this->rupDocs->getExistingFileName($this->cpfCnpj, $this->category, null, $this->cnpj);
        if ($fileName === '') {
            return '';
        }

        $this->exists = true;

        $fileName = explode('.', $fileName);
        return $fileName[0];
    }
}
