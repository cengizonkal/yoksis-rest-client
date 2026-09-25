<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Exceptions\ApiException;
use Conkal\YOKSIS\REST\Exceptions\AuthenticationException;
use Conkal\YOKSIS\REST\Exceptions\ConnectionException;
use Conkal\YOKSIS\REST\Exceptions\NotFoundException;
use Conkal\YOKSIS\REST\Exceptions\RateLimitException;
use Conkal\YOKSIS\REST\Exceptions\RequestFailedException;
use Conkal\YOKSIS\REST\Exceptions\ServerErrorException;
use Conkal\YOKSIS\REST\Exceptions\ValidationException;
use Conkal\YOKSIS\REST\Exceptions\YoksisException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ExceptionsTest extends TestCase
{
    /**
     * @return \Throwable
     */
    private function failWith($response)
    {
        try {
            $this->client([$response])->pedagojikFormasyon()->query(['tcKimlikNo' => '11111111111']);
        } catch (\Throwable $e) {
            return $e;
        }
        $this->fail('İstisna fırlatılmadı.');
    }

    public function statusProvider()
    {
        return [
            '400' => [400, ValidationException::class],
            '401' => [401, AuthenticationException::class],
            '403' => [403, AuthenticationException::class],
            '404' => [404, NotFoundException::class],
            '409' => [409, ValidationException::class],
            '418' => [418, RequestFailedException::class],
            '422' => [422, ValidationException::class],
            '429' => [429, RateLimitException::class],
        ];
    }

    /**
     * @dataProvider statusProvider
     */
    public function test_4xx_responses_map_to_library_exceptions($status, $class)
    {
        $e = $this->failWith(new Response($status));

        $this->assertInstanceOf($class, $e);
        $this->assertInstanceOf(YoksisException::class, $e);
        $this->assertInstanceOf(ApiException::class, $e);
        // Geriye uyumluluk: Guzzle istisnası olarak da yakalanabilir.
        $this->assertInstanceOf(ClientException::class, $e);
        $this->assertSame($status, $e->getStatusCode());
        $this->assertSame($status, $e->getCode());
    }

    public function test_5xx_responses_map_to_server_error()
    {
        $e = $this->failWith(new Response(503, [], 'Bakımda'));

        $this->assertInstanceOf(ServerErrorException::class, $e);
        $this->assertInstanceOf(ServerException::class, $e);
        $this->assertInstanceOf(YoksisException::class, $e);
        $this->assertSame('Bakımda', $e->getErrorMessage());
    }

    public function test_error_message_is_parsed_from_json_body()
    {
        $e = $this->failWith(self::json(['message' => 'Belge tarihi hatalı'], 400));

        $this->assertSame('Belge tarihi hatalı', $e->getErrorMessage());
        $this->assertSame(json_encode(['message' => 'Belge tarihi hatalı']), $e->getResponseBody());
        $this->assertStringContainsString('Belge tarihi hatalı', $e->getMessage());
        $this->assertStringContainsString('400', $e->getMessage());
    }

    public function test_message_does_not_leak_query_string()
    {
        $e = $this->failWith(new Response(404));

        $this->assertStringNotContainsString('11111111111', $e->getMessage());
        $this->assertStringContainsString('pedagojikFormasyon', $e->getMessage());
    }

    public function test_html_error_pages_are_not_used_as_message()
    {
        $e = $this->failWith(new Response(500, [], '<html><body>Internal Error</body></html>'));

        $this->assertNull($e->getErrorMessage());
        $this->assertStringContainsString('<html>', $e->getResponseBody());
    }

    public function test_rate_limit_exposes_retry_after()
    {
        $e = $this->failWith(new Response(429, ['Retry-After' => '7']));
        $this->assertSame(7, $e->getRetryAfter());
    }

    public function test_connection_errors_map_to_connection_exception()
    {
        $e = $this->failWith(new ConnectException('cURL error 6', new Request('GET', \Conkal\YOKSIS\REST\YOK::TEST_URI)));

        $this->assertInstanceOf(ConnectionException::class, $e);
        $this->assertInstanceOf(ConnectException::class, $e);
        $this->assertInstanceOf(YoksisException::class, $e);
        $this->assertStringContainsString('servisler.yok.gov.tr', $e->getMessage());
        $this->assertInstanceOf(ConnectException::class, $e->getPrevious());
    }

    public function test_original_guzzle_exception_is_kept_as_previous()
    {
        $e = $this->failWith(new Response(401));
        $this->assertInstanceOf(ClientException::class, $e->getPrevious());
        $this->assertNotInstanceOf(YoksisException::class, $e->getPrevious());
    }
}
