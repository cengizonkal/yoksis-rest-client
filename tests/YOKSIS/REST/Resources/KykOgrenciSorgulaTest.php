<?php

namespace YOKSIS\REST\Resources;

use PHPUnit\Framework\TestCase;

class KykOgrenciSorgulaTest extends TestCase
{

    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
        $this->client->setAuth($auth);
    }

    public function test_it_should_return_kyk()
    {
        $result = $this->client->kykOgrenciSorgula()->query(['tcKimlikNo' => '12345678901']);
        var_dump($result);

    }
}
