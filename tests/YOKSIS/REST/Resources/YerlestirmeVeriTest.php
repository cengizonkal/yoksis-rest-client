<?php

namespace YOKSIS\REST\Resources;


use Conkal\YOKSIS\REST\Entities\YerlestirmeVeri;
use PHPUnit\Framework\TestCase;

class YerlestirmeVeriTest extends TestCase
{
    /**
     * @var \Conkal\YOKSIS\REST\YOK
     */
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
        /** @var YerlestirmeVeri[] $entities */
        $entities = $this->client->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']);

        $this->assertTrue(count($entities) > 0);
    }
}
