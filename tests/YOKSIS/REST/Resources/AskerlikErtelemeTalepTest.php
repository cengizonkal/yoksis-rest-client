<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\AskerlikErtelemeTalep;
use PHPUnit\Framework\TestCase;

class AskerlikErtelemeTalepTest extends TestCase
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

    public function test_it_should_create_askerlik_talep()
    {
        $entity = new \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep();
        $entity->tcKimlikNo = '12345678910';
        $entity->birimId = getenv('TEST_BIRIMID');
        $entity->teklifTuru = 'E';
        $entity->teklifNedeniNo = 501;
        $entity->imzalayanTcNo = '12345678910';
        $entity->imzalayanAdSoyad = 'Test';

        $this->client->askerlikErtelemeTalep()->create($entity);
    }
}
