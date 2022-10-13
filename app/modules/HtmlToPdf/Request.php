<?php

namespace Modules\HtmlToPdf;

use Modules\Services\Service;
use Modules\Services\ServiceRequest;

class Request extends ServiceRequest
{
    public function __construct(Service $service)
    {
        parent::__construct($service, true);
    }
}
