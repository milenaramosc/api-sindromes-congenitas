<?php

namespace App\Core\Handlers\Response;

class ResponseHeader
{
    public static function pdfFile(string $fileName): void
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=" . $fileName);
    }
}
