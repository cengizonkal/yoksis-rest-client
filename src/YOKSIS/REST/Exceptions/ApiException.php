<?php

namespace Conkal\YOKSIS\REST\Exceptions;

/**
 * Servisin hata durum koduyla (4xx/5xx) yanıt verdiği istisnalar.
 */
interface ApiException extends YoksisException
{
    /**
     * @return int HTTP durum kodu
     */
    public function getStatusCode();

    /**
     * @return string Yanıt gövdesi
     */
    public function getResponseBody();

    /**
     * @return string|null Yanıttan ayrıştırılabilen hata mesajı
     */
    public function getErrorMessage();
}
