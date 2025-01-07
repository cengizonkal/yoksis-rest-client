<?php

namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\OgrenciTranskript;
use PHPUnit\Framework\TestCase;

class OgrenciTranskriptTest extends TestCase
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

    public function test_it_should_create_a_transcript(){

        $transkript = new \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript();

        $transkript->ogrenciId = '123456';
        $transkript->tcKimlikNo = getenv('TEST_TCKNO');
        $transkript->birimId = '123456';

        $this->client->ogrenciTranskript()->create($transkript);
    }

}