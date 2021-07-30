<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\Constants\HazirlikDetay\BasarmaDurumu;
use Conkal\YOKSIS\Constants\HazirlikDetay\MuafiyetDurumu;
use Conkal\YOKSIS\REST\Resources\HazirlikDetay;
use PHPUnit\Framework\TestCase;

class HazirlikDetayTest extends TestCase
{

    /**
     * @var \Conkal\YOKSIS\REST\YOK
     */
    private $client;
    /**
     * @var \Conkal\YOKSIS\REST\Entities\HazirlikDetay
     */
    private $entity;

    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
        $this->client->setAuth($auth);


        $this->entity = new \Conkal\YOKSIS\REST\Entities\HazirlikDetay();
        $this->entity->tckno = getenv('TEST_TCKNO');
        $this->entity->hazirlikTuru = 2;
        $this->entity->ogretimDili = 1;
        $this->entity->hazirlikDonemNo = 1;
        $this->entity->muafiyetDurumu = MuafiyetDurumu::MUAF_DEGIL;
        $this->entity->birimId = getenv('TEST_BIRIMID');

    }

    public function test_it_should_save()
    {
        $this->client->hazirlikDetay()->create($this->entity);
    }

    public function test_it_should_find()
    {
        $this->entity = $this->client->hazirlikDetay()->find($this->entity->id);
    }

    public function test_it_should_get_all()
    {
        $entities = $this->client->hazirlikDetay()->all();
        $this->assertTrue(count($entities) > 0);
    }


    public function test_it_should_query()
    {
        $entities = $this->client->hazirlikDetay()->query(['tcKimlikNo' => getenv('TEST_TCKNO')]);

        $this->assertTrue(count($entities) > 0);
    }

    public function test_it_should_not_find()
    {
        $entities = $this->client->hazirlikDetay()->query(['tcKimlikNo' => '12345678911']);
        $this->assertTrue(count($entities) == 0);
    }




    public function test_it_should_delete()
    {
        $entities = $this->client->hazirlikDetay()->all();
        foreach ($entities as $entity) {
            $this->client->hazirlikDetay()->delete($entity->id);
        }
    }
}
