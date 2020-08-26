<?php

namespace YOKSIS\REST\Resources;


use PHPUnit\Framework\TestCase;

class HazirlikTurleriTest extends TestCase
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


    public function test_it_should_get_all()
    {
        $entities = $this->client->hazirlikTurleri()->all();
        $this->assertTrue(count($entities) > 0);
        $this->assertTrue(isset($entities[0]->ad));
    }

}
