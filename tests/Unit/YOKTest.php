<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\YOK;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class YOKTest extends TestCase
{
    public function test_it_sends_basic_auth_and_json_headers()
    {
        $this->client([self::json([])])->send('hazirlikturleri');

        $request = $this->lastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(YOK::TEST_URI . 'hazirlikturleri', (string)$request->getUri());
        $this->assertSame('Basic ' . base64_encode('kullanici:sifre'), $request->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    public function test_it_normalizes_base_uri_slashes()
    {
        $client = $this->client([self::json([])]);
        $client->setBaseUri('https://servisler.yok.gov.tr/resttest/obs');
        $client->send('/hazirlikturleri');

        $this->assertSame('https://servisler.yok.gov.tr/resttest/obs/', $client->getBaseUri());
        $this->assertSame(YOK::TEST_URI . 'hazirlikturleri', (string)$this->lastRequest()->getUri());
    }

    public function test_it_works_without_auth()
    {
        $client = new YOK(YOK::TEST_URI, $this->client([self::json([])])->client);
        $client->send('hazirlikturleri');

        $this->assertFalse($this->lastRequest()->hasHeader('Authorization'));
    }

    public function test_it_passes_guzzle_options_through()
    {
        $this->client([self::json([])])->send('x', ['method' => 'post', 'query' => ['a' => 'b'], 'json' => ['c' => 1]]);

        $request = $this->lastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('a=b', $request->getUri()->getQuery());
        $this->assertSame('{"c":1}', (string)$request->getBody());
    }

    public function test_http_errors_are_thrown()
    {
        $this->expectException(ClientException::class);
        $this->client([new Response(401)])->hazirlikTurleri()->all();
    }

    public function test_resource_accessor_names_are_case_insensitive()
    {
        $client = $this->client();
        $this->assertInstanceOf(\Conkal\YOKSIS\REST\Resources\OgrenciIzinler::class, $client->ogrenciizinler());
        $this->assertInstanceOf(\Conkal\YOKSIS\REST\Resources\OgrenciIzinler::class, $client->ogrenciIzinler());
    }
}
