<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\OgrenciIzin;
use Conkal\YOKSIS\REST\Resources\OgrenciIzinler;
use PHPUnit\Framework\TestCase;

class OgrenciIzinlerTest extends TestCase
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


    public function test_it_should_create_izin()
    {
        $entity = new OgrenciIzin();
        $entity->tcKimlikNo = getenv('TEST_TCKNO');
        $entity->birimID = getenv('TEST_BIRIMID');
        $entity->kararTarihi = '01/01/2020';
        $entity->izinBaslangicTarihi = '01/01/2020';
        $entity->izinBitisTarihi = '01/01/2021';
        $entity->izinSuresi = 1;
        $this->client->ogrenciizinler()->create($entity);
        $this->assertTrue(true);
    }

    public function test_it_should_get_izin()
    {
        $izinler = $this->client->ogrenciizinler()->query(['tcKimlikNo' => getenv('TEST_TCKNO')]);
        $this->assertTrue(count($izinler)>0);

    }

    public function test_it_should_delete_all_izin()
    {
        $izinler = $this->client->ogrenciizinler()->query(['tcKimlikNo' => getenv('TEST_TCKNO')]);
        foreach ($izinler as $izin) {
            $this->client->ogrenciizinler()->delete($izin->id);
        }
        $this->assertTrue(true);
    }

}
