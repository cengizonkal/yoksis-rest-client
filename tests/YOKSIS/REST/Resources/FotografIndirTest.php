<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\FotografIndir;
use PHPUnit\Framework\TestCase;

class FotografIndirTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
        $this->client->setAuth($auth);
    }

    public function test_it_should_query()
    {
        $response = $this->client->fotografIndir()->find('<tckimlikno>');

        file_put_contents('tckimlikno.png', $response->getBody());
    }
}
