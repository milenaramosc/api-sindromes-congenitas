<?php

namespace App\Core\Handlers\Response;

class Status
{
    public const OK              = 200;
    public const CREATED         = 201;
    public const ACCEPTED        = 202;
    public const NO_CONTENT      = 204;
    public const PARTIAL_CONTENT = 206;

    public const BAD_REQUEST                        = 400;
    public const UNAUTHORIZED                       = 401;
    public const PAYMENT_REQUIRED                   = 402;
    public const FORBIDDEN                          = 403;
    public const NOT_FOUND                          = 404;
    public const METHOD_NOT_ALLOWED                 = 405;
    public const NOT_ACCEPTABLE                     = 406;
    public const PROXY_AUTHENTICATION_REQUIRED      = 407;
    public const REQUEST_TIMEOUT                    = 408;
    public const CONFLICT                           = 409;
    public const GONE                               = 410;
    public const LENGTH_REQUIRED                    = 411;
    public const PRECONDITION_FAILED                = 412;
    public const PAYLOAD_TOO_LARGE                  = 413;
    public const URI_TOO_LONG                       = 414;
    public const UNSUPPORTED_MEDIA_TYPE             = 415;
    public const REQUESTED_RANGE_NOT_SATISFIABLE    = 416;
    public const EXPECTATION_FAILED                 = 417;
    public const IM_A_TEAPOT                        = 418;
    public const MISDIRECTED_REQUEST                = 421;
    public const UNPROCESSABLE_ENTITY_WEB_DAV_EN_US = 422;
    public const LOCKED_WEB_DAV_EN_US               = 423;
    public const FAILED_DEPENDENCY_WEB_DAV_EN_US    = 424;
    public const TOO_EARLY                          = 425;
    public const UPGRADE_REQUIRED                   = 426;
    public const PRECONDITION_REQUIRED              = 428;
    public const TOO_MANY_REQUESTS                  = 429;
    public const REQUEST_HEADER_FIELDS_TOO_LARGE    = 431;
    public const UNAVAILABLE_FOR_LEGAL_REASONS      = 451;

    public const INTERNAL_SERVER_ERROR           = 500;
    public const NOT_IMPLEMENTED                 = 501;
    public const BAD_GATEWAY                     = 502;
    public const SERVICE_UNAVAILABLE             = 503;
    public const GATEWAY_TIMEOUT                 = 504;
    public const HTTP_VERSION_NOT_SUPPORTED      = 505;
    public const VARIANT_ALSO_NEGOTIATES         = 506;
    public const INSUFFICIENT_STORAGE            = 507;
    public const LOOP_DETECTED                   = 508;
    public const NOT_EXTENDED                    = 510;
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;
}
