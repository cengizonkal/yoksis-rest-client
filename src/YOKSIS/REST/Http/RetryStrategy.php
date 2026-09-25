<?php

namespace Conkal\YOKSIS\REST\Http;

use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Geçici hatalarda (bağlantı hatası, 429, 502, 503, 504) isteği yeniden dener.
 *
 * Varsayılan olarak yalnızca GET/HEAD istekleri yeniden denenir; POST gibi
 * idempotent olmayan istekler mükerrer kayıt oluşturabileceği için denenmez.
 */
class RetryStrategy
{
    /**
     * @var int
     */
    private $maxRetries;

    /**
     * @var int milisaniye
     */
    private $baseDelay;

    /**
     * @var int milisaniye
     */
    private $maxDelay;

    /**
     * @var string[]
     */
    private $methods;

    /**
     * @var int[]
     */
    private $statusCodes;

    /**
     * @param int $maxRetries En fazla yeniden deneme sayısı
     * @param int $baseDelay İlk bekleme süresi (ms); her denemede iki katına çıkar
     * @param string[] $methods Yeniden denenecek HTTP metotları
     * @param int[] $statusCodes Yeniden denenecek durum kodları
     * @param int $maxDelay En uzun bekleme süresi (ms)
     */
    public function __construct(
        $maxRetries = 2,
        $baseDelay = 500,
        array $methods = ['GET', 'HEAD'],
        array $statusCodes = [429, 502, 503, 504],
        $maxDelay = 10000
    ) {
        $this->maxRetries = max(0, (int)$maxRetries);
        $this->baseDelay = max(0, (int)$baseDelay);
        $this->maxDelay = max(0, (int)$maxDelay);
        $this->methods = array_map('strtoupper', $methods);
        $this->statusCodes = array_map('intval', $statusCodes);
    }

    /**
     * Guzzle Middleware::retry için karar fonksiyonu.
     *
     * @return bool
     */
    public function decide($retries, RequestInterface $request, ?ResponseInterface $response = null, $exception = null)
    {
        if ($retries >= $this->maxRetries || !in_array(strtoupper($request->getMethod()), $this->methods, true)) {
            return false;
        }
        if ($exception instanceof ConnectException) {
            return true;
        }
        return $response !== null && in_array($response->getStatusCode(), $this->statusCodes, true);
    }

    /**
     * Guzzle Middleware::retry için bekleme süresi (ms). Retry-After başlığı varsa ona uyar.
     *
     * @return int
     */
    public function delay($retries, ?ResponseInterface $response = null)
    {
        if ($response !== null) {
            $retryAfter = $response->getHeaderLine('Retry-After');
            if (ctype_digit($retryAfter)) {
                return min((int)$retryAfter * 1000, $this->maxDelay);
            }
        }
        return (int)min($this->baseDelay * pow(2, max(0, $retries - 1)), $this->maxDelay);
    }
}
