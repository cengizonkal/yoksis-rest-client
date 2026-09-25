<?php

namespace Conkal\YOKSIS\REST\Exceptions;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Guzzle istisnalarını kütüphane istisnalarına çevirir.
 *
 * @internal
 */
final class ExceptionFactory
{
    /**
     * @param \Throwable $e
     * @return \Throwable Eşlenebiliyorsa kütüphane istisnası, değilse orijinal istisna
     */
    public static function fromGuzzle($e)
    {
        if ($e instanceof YoksisException) {
            return $e;
        }

        if ($e instanceof ConnectException) {
            return ConnectionException::fromConnectException($e);
        }

        if ($e instanceof BadResponseException && $e->getResponse()) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $status = $response->getStatusCode();

            if ($status >= 500) {
                return ServerErrorException::fromResponse($request, $response, $e);
            }

            switch ($status) {
                case 401:
                case 403:
                    return AuthenticationException::fromResponse($request, $response, $e);
                case 404:
                    return NotFoundException::fromResponse($request, $response, $e);
                case 400:
                case 409:
                case 422:
                    return ValidationException::fromResponse($request, $response, $e);
                case 429:
                    return RateLimitException::fromResponse($request, $response, $e);
                default:
                    return RequestFailedException::fromResponse($request, $response, $e);
            }
        }

        return $e;
    }
}
