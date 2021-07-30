<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\YurtDisindanYatayGecis;
use PHPUnit\Framework\TestCase;

class YurtDisindanYatayGecisTest extends TestCase
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

    public function test_it_should_create_yatay_gecis()
    {
        $entity = new \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis();
        $entity->tcKimlikNo = getenv('TEST_TCKNO');
        $entity->ulkeId = "9980";
        $entity->yurtdisiUniversiteAdi = "ABC YURTDIŞI UNIVERSITE ADI";
        $entity->yurtdisiProgramAdi = "ABC YURTDISI PROGRAM ADI";
        $entity->yurtdisiUniversiteSinifi = "ABC SINIF";
        $entity->uniyeKayitTarihi = "18/02/2021";
        $entity->kayitOlunanBirimId = getenv('TEST_BIRIMID');
        $entity->notOrtalamasi = "4.35";
        $entity->notSistemi = "5";
//        $entity->yksPuani = "425";
//        $entity->yksBasariSirasi = "1314";
//        $entity->yksPuanTuru = "500";
        $entity->uniDunyaSiralaSistemi = "SİSTEM GİRİLECEK";
        $entity->uniDunyaSiralamasi = "8";


        $this->client->yurtDisindanYatayGecis()->create($entity);


        $this->assertTrue(true);
    }

    public function test_it_should_get_all()
    {
        $entities = $this->client->yurtDisindanYatayGecis()->all();
        $this->assertTrue(count($entities) > 0);
    }

}
