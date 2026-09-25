<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Exceptions\ServerErrorException;
use Conkal\YOKSIS\REST\Http\RetryStrategy;
use Conkal\YOKSIS\REST\YOK;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

class ClientFactoryTest extends TestCase
{
    /**
     * @var MockHandler
     */
    private $handler;

    private function yok(array $responses, array $options = [])
    {
        $this->handler = new MockHandler($responses);
        return YOK::create(YOK::TEST_URI, 'kullanici', 'sifre', array_merge([
            'handler' => $this->handler,
            'retry_delay' => 0,
        ], $options));
    }

    public function test_get_requests_are_retried_on_transient_errors()
    {
        $client = $this->yok([new Response(503), new Response(502), self::json([['kod' => 1, 'ad' => 'A']])]);

        $this->assertCount(1, $client->hazirlikTurleri()->all());
        $this->assertSame(0, $this->handler->count());
    }

    public function test_get_requests_are_retried_on_connection_errors()
    {
        $client = $this->yok([
            new ConnectException('timeout', new Request('GET', YOK::TEST_URI)),
            self::json([['kod' => 1]]),
        ]);

        $this->assertCount(1, $client->hazirlikTurleri()->all());
    }

    public function test_it_gives_up_after_max_retries()
    {
        $client = $this->yok([new Response(503), new Response(503), new Response(503), new Response(200)]);

        try {
            $client->hazirlikTurleri()->all();
            $this->fail('İstisna fırlatılmadı.');
        } catch (ServerErrorException $e) {
            $this->assertSame(503, $e->getStatusCode());
        }
        $this->assertSame(1, $this->handler->count(), '1 deneme + 2 yeniden deneme yapılmalı');
    }

    public function test_post_requests_are_not_retried()
    {
        $client = $this->yok([new Response(503), new Response(200)]);

        $this->expectException(ServerErrorException::class);
        try {
            $client->pedagojikFormasyon()->create(new \Conkal\YOKSIS\REST\Entities\PedagojikFormasyon());
        } finally {
            $this->assertSame(1, $this->handler->count());
        }
    }

    public function test_client_errors_are_not_retried()
    {
        $client = $this->yok([new Response(404), new Response(200)]);

        try {
            $client->hazirlikTurleri()->all();
        } catch (\Conkal\YOKSIS\REST\Exceptions\NotFoundException $e) {
        }
        $this->assertSame(1, $this->handler->count());
    }

    public function test_retries_can_be_disabled()
    {
        $client = $this->yok([new Response(503), new Response(200)], ['retries' => 0]);

        $this->expectException(ServerErrorException::class);
        $client->hazirlikTurleri()->all();
    }

    public function test_retry_delay_is_exponential_and_honors_retry_after()
    {
        $strategy = new RetryStrategy(3, 500, ['GET'], [503], 10000);

        $this->assertSame(500, $strategy->delay(1));
        $this->assertSame(1000, $strategy->delay(2));
        $this->assertSame(2000, $strategy->delay(3));
        $this->assertSame(3000, $strategy->delay(1, new Response(503, ['Retry-After' => '3'])));
        $this->assertSame(10000, $strategy->delay(1, new Response(503, ['Retry-After' => '3600'])));
    }

    public function test_every_attempt_is_logged_without_personal_data()
    {
        $calls = [];
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('log')->willReturnCallback(function ($level, $message, array $context = []) use (&$calls) {
            $calls[] = [$level, $message, $context];
        });

        $client = $this->yok([new Response(503), self::json([])], ['logger' => $logger]);
        $client->ogrenciIzinler()->query(['tcKimlikNo' => '11111111111']);

        $this->assertCount(2, $calls);
        $this->assertSame('error', $calls[0][0]);
        $this->assertSame(503, $calls[0][2]['status']);
        $this->assertSame(1, $calls[0][2]['attempt']);
        $this->assertSame('info', $calls[1][0]);
        $this->assertSame(2, $calls[1][2]['attempt']);
        $this->assertSame('/resttest/obs/ogrenciizinler', $calls[1][2]['path']);
        $this->assertStringNotContainsString('11111111111', json_encode($calls));
        $this->assertStringNotContainsString('sifre', json_encode($calls));
    }

    public function test_connection_failures_are_logged()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error')
            ->with($this->stringContains('başarısız'), $this->callback(function ($context) {
                return $context['error'] === ConnectException::class;
            }));

        $client = $this->yok([new ConnectException('x', new Request('GET', YOK::TEST_URI))], [
            'logger' => $logger,
            'retries' => 0,
        ]);

        $this->expectException(\Conkal\YOKSIS\REST\Exceptions\ConnectionException::class);
        $client->hazirlikTurleri()->all();
    }

    public function test_create_sets_basic_auth_and_timeouts()
    {
        $client = $this->yok([self::json([])]);
        $client->hazirlikTurleri()->all();

        $config = $client->client instanceof \GuzzleHttp\Client && method_exists($client->client, 'getConfig')
            ? $client->client->getConfig() : [];
        $this->assertSame(30, $config['timeout']);
        $this->assertSame(10, $config['connect_timeout']);
        $this->assertInstanceOf(\Conkal\YOKSIS\REST\Utilities\BasicAuth::class, $client->getAuth());
    }
}
