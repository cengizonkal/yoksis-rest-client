<?php

namespace Conkal\YOKSIS\REST\Http;

use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Her HTTP denemesini PSR-3 logger'a yazar.
 *
 * Kişisel veri (T.C. kimlik no vb.) sızdırmamak için yalnızca metot, yol
 * (sorgu dizesi olmadan), durum kodu, süre ve deneme numarası loglanır;
 * istek/yanıt gövdeleri ve Authorization başlığı loglanmaz.
 */
class LoggingMiddleware
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(callable $handler)
    {
        $logger = $this->logger;

        return function (RequestInterface $request, array $options) use ($handler, $logger) {
            $start = microtime(true);
            $context = [
                'method' => $request->getMethod(),
                'path' => $request->getUri()->getPath(),
                'attempt' => (isset($options['retries']) ? (int)$options['retries'] : 0) + 1,
            ];

            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($logger, $context, $start) {
                    $context['status'] = $response->getStatusCode();
                    $context['duration_ms'] = (int)round((microtime(true) - $start) * 1000);
                    $level = $context['status'] >= 500 ? 'error' : ($context['status'] >= 400 ? 'warning' : 'info');
                    $logger->log($level, 'YÖKSİS {method} {path} -> {status} ({duration_ms} ms, deneme {attempt})', $context);
                    return $response;
                },
                function ($reason) use ($logger, $context, $start) {
                    $context['duration_ms'] = (int)round((microtime(true) - $start) * 1000);
                    $context['error'] = $reason instanceof \Throwable ? get_class($reason) : 'unknown';
                    $logger->error('YÖKSİS {method} {path} başarısız: {error} ({duration_ms} ms, deneme {attempt})', $context);
                    return new RejectedPromise($reason);
                }
            );
        };
    }
}
