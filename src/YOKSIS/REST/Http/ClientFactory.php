<?php

namespace Conkal\YOKSIS\REST\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;

/**
 * Yeniden deneme, loglama ve zaman aşımı ayarlarıyla Guzzle istemcisi oluşturur.
 */
class ClientFactory
{
    /**
     * Desteklenen seçenekler:
     *  - timeout          (float, saniye, varsayılan 30)
     *  - connect_timeout  (float, saniye, varsayılan 10)
     *  - retries          (int, varsayılan 2; 0 yeniden denemeyi kapatır)
     *  - retry_delay      (int, ms, varsayılan 500; her denemede iki katına çıkar)
     *  - retry_methods    (string[], varsayılan ['GET', 'HEAD'])
     *  - retry_statuses   (int[], varsayılan [429, 502, 503, 504])
     *  - logger           (Psr\Log\LoggerInterface|null)
     *  - handler          (callable|null, test için özel Guzzle handler)
     *  - guzzle           (array, Guzzle istemcisine doğrudan geçilecek ek seçenekler)
     *
     * @param array $options
     * @return Client
     */
    public static function create(array $options = [])
    {
        $options = array_merge([
            'timeout' => 30,
            'connect_timeout' => 10,
            'retries' => 2,
            'retry_delay' => 500,
            'retry_methods' => ['GET', 'HEAD'],
            'retry_statuses' => [429, 502, 503, 504],
            'logger' => null,
            'handler' => null,
            'guzzle' => [],
        ], $options);

        $stack = HandlerStack::create($options['handler']);

        if ((int)$options['retries'] > 0) {
            $strategy = new RetryStrategy(
                $options['retries'],
                $options['retry_delay'],
                $options['retry_methods'],
                $options['retry_statuses']
            );
            $stack->push(Middleware::retry([$strategy, 'decide'], [$strategy, 'delay']), 'yoksis_retry');
        }

        // Retry'dan sonra (daha içte) eklendiği için her deneme ayrı loglanır.
        if ($options['logger'] instanceof LoggerInterface) {
            $stack->push(new LoggingMiddleware($options['logger']), 'yoksis_log');
        }

        return new Client(array_merge($options['guzzle'], [
            'handler' => $stack,
            'timeout' => $options['timeout'],
            'connect_timeout' => $options['connect_timeout'],
        ]));
    }
}
