<?php

namespace Conkal\YOKSIS\REST\Exceptions;

use GuzzleHttp\Exception\ConnectException;

/**
 * Servise bağlanılamadı (DNS, zaman aşımı, bağlantı reddi vb.).
 *
 * Guzzle'ın ConnectException sınıfından türer.
 */
class ConnectionException extends ConnectException implements YoksisException
{
    /**
     * @return static
     */
    public static function fromConnectException(ConnectException $e)
    {
        return new static(
            sprintf('YÖKSİS servisine bağlanılamadı (%s): %s', $e->getRequest()->getUri()->getHost(), $e->getMessage()),
            $e->getRequest(),
            $e,
            $e->getHandlerContext()
        );
    }
}
