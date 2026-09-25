<?php

namespace Conkal\YOKSIS\REST\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
trait ApiErrorTrait
{
    /**
     * @var string
     */
    private $responseBody = '';

    /**
     * @var string|null
     */
    private $errorMessage;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param \Throwable|null $previous
     * @return static
     */
    public static function fromResponse(RequestInterface $request, ResponseInterface $response, $previous = null)
    {
        $body = (string)$response->getBody();
        $errorMessage = self::extractErrorMessage($body);

        $message = sprintf(
            'YÖKSİS %s %s isteği %d %s ile sonuçlandı%s',
            $request->getMethod(),
            $request->getUri()->getPath(),
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $errorMessage !== null ? ': ' . $errorMessage : '.'
        );

        $exception = new static($message, $request, $response, $previous);
        $exception->responseBody = $body;
        $exception->errorMessage = $errorMessage;
        return $exception;
    }

    public function getStatusCode()
    {
        return $this->getResponse()->getStatusCode();
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $body
     * @return string|null
     */
    private static function extractErrorMessage($body)
    {
        $body = trim($body);
        if ($body === '') {
            return null;
        }

        $decoded = json_decode($body, true);
        if (is_string($decoded)) {
            return self::truncate($decoded);
        }
        if (is_array($decoded)) {
            foreach (['message', 'mesaj', 'hataMesaji', 'hata', 'error', 'aciklama', 'detail'] as $key) {
                if (isset($decoded[$key]) && is_scalar($decoded[$key]) && (string)$decoded[$key] !== '') {
                    return self::truncate((string)$decoded[$key]);
                }
            }
            return null;
        }

        // HTML hata sayfalarını mesaj olarak göstermeyelim.
        if (stripos($body, '<html') !== false || stripos($body, '<!doctype') !== false) {
            return null;
        }
        return self::truncate($body);
    }

    private static function truncate($text)
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if (function_exists('mb_strlen') && mb_strlen($text) > 300) {
            return mb_substr($text, 0, 300) . '…';
        }
        return $text;
    }
}
