<?php

namespace Conkal\YOKSIS\REST\Exceptions;

/**
 * 429: Çok fazla istek gönderildi.
 */
class RateLimitException extends RequestFailedException
{
    /**
     * @return int|null Retry-After başlığındaki bekleme süresi (saniye)
     */
    public function getRetryAfter()
    {
        $value = $this->getResponse()->getHeaderLine('Retry-After');
        return ctype_digit($value) ? (int)$value : null;
    }
}
