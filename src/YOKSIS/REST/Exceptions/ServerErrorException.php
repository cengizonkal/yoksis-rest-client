<?php

namespace Conkal\YOKSIS\REST\Exceptions;

use GuzzleHttp\Exception\ServerException;

/**
 * Servis 5xx durum koduyla yanıt verdi.
 *
 * Guzzle'ın ServerException sınıfından türer.
 */
class ServerErrorException extends ServerException implements ApiException
{
    use ApiErrorTrait;
}
