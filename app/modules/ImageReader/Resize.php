<?php

namespace Modules\ImageReader;

use App\Core\Exceptions\RBMException;

class Resize
{
    /**
     * Imagem para redimencionar
     *
     * @var Image
     */
    private Image $image;

    /**
     * Nova largura da imagem
     *
     * @var integer
     */
    private int $newWidth;

    /**
     * Nova altura da imagem
     *
     * @var integer
     */
    private int $newHeight;

    /**
     * Posicionamento do crop
     *
     * @var int[] [x, y, width, height]
     */
    private array $cropPosition;

    /**
     * @param string Caminho da imagem a ser carregada
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Armazena posições x e y para crop
     *
     * @param string|int $x
     * @param string|int $y
     * @return void
     */
    public function cropPosition($x, $y): void
    {
        $this->cropPosition = [$x, $y, $this->image->width, $this->image->height];
    }

    /**
     * Redimensiona imagem
     * @param Int $newWidth valor em pixels da nova largura da imagem
     * @param Int $newHeight valor em pixels da nova altura da imagem
     * @param String $resizeMode método para redimensionamento (padrão [vazio], 'fill' [preenchimento] ou 'crop')
     * @return Image
     */
    public function resizeImage(int $newWidth = 0, int $newHeight = 0, string $resizeMode = ''): Image
    {
        $this->newWidth  = $newWidth;
        $this->newHeight = $newHeight;

        if ($newWidth === 0 && $newHeight === 0) {
            return $this->image;
        }

        if (!$this->newWidth) {
            $this->newWidth = $this->image->width / ($this->image->height / $this->newHeight);
        } elseif (!$this->newHeight) {
            $this->newHeight = $this->image->height / ($this->image->width / $this->newWidth);
        }

        switch ($resizeMode) {
            case 'crop':
                $this->resizeCrop();
                break;
            case 'fill':
                $this->resizeFill();
                break;
            default:
                $this->resize();
                break;
        }

        $this->image->width  = $this->newWidth;
        $this->image->height = $this->newHeight;

        return $this->image;
    }

    /**
     * Exibe a imagem
     *
     * @return void
     * @throws RBMException
     */
    public function show(): void
    {
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Transfer-Encoding: binary');

        $this->image->show();
    }

    /**
     * Redimensiona imagem, modo padrão, sem crop ou fill (distorcendo)
     *
     * @return void
     */
    private function resize()
    {
        $originalWidth = $this->image->width;
        $originalHeight = $this->image->height;

        $wmaxDimension = $this->newWidth;
        $hmaxDimension = $this->newHeight;

        $aspectRatio = $originalWidth / $originalHeight;

        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        if (($originalWidth > $wmaxDimension) || ($originalHeight > $hmaxDimension)) {
            if ($originalWidth > $originalHeight) {
                $newWidth = $wmaxDimension;
                $newHeight = intval($newWidth / $aspectRatio);
            } else {
                $newHeight = $hmaxDimension;
                $newWidth = intval($newHeight * $aspectRatio);
            }
        }

        $aspectRatio = $newWidth / $newHeight;
        if ($newWidth > $wmaxDimension) {
            $newWidth = $wmaxDimension;
            $newHeight = intval($newWidth / $aspectRatio);
        } elseif ($newHeight > $hmaxDimension) {
            $newHeight = $hmaxDimension;
            $newWidth =  intval($newHeight * $aspectRatio);
        }

        $gdTempImage = imagecreatetruecolor($newWidth, $newHeight);

        imagefill(
            $gdTempImage,
            0,
            0,
            imagecolorallocate($gdTempImage, 255, 255, 255)
        );

        imagecopyresampled(
            $gdTempImage,
            $this->image->gdImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );
        $this->image->gdImage = $gdTempImage;
    }

    /**
     * Redimensiona imagem proporcionalmente preenchendo espaço vazio
     *
     * @return void
     */
    private function resizeFill(): void
    {
        $difY = $this->newHeight;
        $difX = $this->newWidth;

        if ($this->image->width > $this->image->height) {
            $this->newHeight = (($this->image->height * $this->newWidth) / $this->image->width);
        } elseif ($this->image->width <= $this->image->height) {
            $this->newWidth = (($this->image->width * $this->newHeight) / $this->image->height);
        }

        $gdTempImage = imagecreatetruecolor($this->newWidth, $this->newHeight);

        $difX = 0 * ($difX - $this->newWidth) / 2;
        $difY = 0 * ($difY - $this->newHeight) / 2;

        imagefill(
            $gdTempImage,
            0,
            0,
            imagecolorallocate($gdTempImage, 255, 255, 255)
        );

        imagecopyresampled(
            $gdTempImage,
            $this->image->gdImage,
            $difX,
            $difY,
            0,
            0,
            $this->newWidth,
            $this->newHeight,
            $this->image->width,
            $this->image->height
        );

        $this->image->gdImage = $gdTempImage;
    }

    /**
     * Calcula a posição do crop
     * Os índices 0 e 1 correspondem à posição x e y do crop na imagem
     * Os índices 2 e 3 correspondem ao tamanho do crop
     *
     * @return void
     */
    private function calculateCropPosition(): void
    {
        $averageWidth  = $this->image->width / $this->newWidth;
        $averageHeight = $this->image->height / $this->newHeight;

        if (!is_array($this->cropPosition)) {
            return;
        }

        if ($averageWidth > $averageHeight) {
            $halfWidth  = $this->newWidth / 2;
            $this->cropPosition[2] = $this->image->width / $averageHeight;
            $this->cropPosition[3] = $this->newHeight;
            $this->cropPosition[0] = ($this->cropPosition[2] / 2) - $halfWidth;
            $this->cropPosition[1] = 0;
            return;
        }

        if ($averageWidth <= $averageHeight) {
            $halfHeight = $this->newHeight / 2;
            $this->cropPosition[2] = $this->newWidth;
            $this->cropPosition[3] = $this->image->height / $averageWidth;
            $this->cropPosition[0] = 0;
            $this->cropPosition[1] = ($this->cropPosition[3] / 2) - $halfHeight;
        }
    }

    /**
     * Redimensiona imagem, cropando para encaixar no novo tamanho, sem sobras
     * baseado no script original de Noah Winecoff
     * http://www.findmotive.com/2006/12/13/php-crop-image/
     * atualizado para receber o posicionamento X e Y do crop na imagem
     * @return void
     */
    private function resizeCrop()
    {
        $this->calculateCropPosition();
        $gdTempImage = imagecreatetruecolor($this->newWidth, $this->newHeight);

        imagecopyresampled(
            $gdTempImage,
            $this->image->gdImage,
            -$this->cropPosition[0],
            -$this->cropPosition[1],
            0,
            0,
            $this->cropPosition[2],
            $this->cropPosition[3],
            $this->image->width,
            $this->image->height
        );
        $this->image->gdImage = $gdTempImage;
    }
}
