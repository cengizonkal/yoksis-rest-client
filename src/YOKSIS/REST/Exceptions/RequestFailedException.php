<?php

namespace Conkal\YOKSIS\REST\Exceptions;

use GuzzleHttp\Exception\ClientException;

/**
 * Servis 4xx durum koduyla yanıt verdi.
 *
 * Guzzle'ın ClientException sınıfından türediği için mevcut
 * `catch (ClientException $e)` blokları çalışmaya devam eder.
 */
class RequestFailedException extends ClientException implements ApiException
{
    use ApiErrorTrait;
}
