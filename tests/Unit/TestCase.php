<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Utilities\BasicAuth;
use Conkal\YOKSIS\REST\YOK;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    protected $history = [];

    /**
     * @var MockHandler
     */
    protected $mock;

    /**
     * @param Response[] $responses
     */
    protected function client(array $responses = [])
    {
        $this->history = [];
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $stack->push(Middleware::history($this->history));

        return new YOK(YOK::TEST_URI, new Client(['handler' => $stack]), new BasicAuth('kullanici', 'sifre'));
    }

    protected static function json($data, $status = 200)
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

    /**
     * @return RequestInterface
     */
    protected function lastRequest()
    {
        $this->assertNotEmpty($this->history, 'Hiç istek gönderilmedi.');
        return end($this->history)['request'];
    }
}
