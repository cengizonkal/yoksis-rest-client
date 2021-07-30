<?php


class PedagojikFormasyonTest extends PHPUnit_Framework_TestCase
{

    private $id;
    private $client;
    private $entity;


    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
        $this->client->setAuth($auth);


        $this->entity = new \Conkal\YOKSIS\REST\Entities\PedagojikFormasyon();
        $this->entity->tcKimlikNo = getenv('TEST_TCKNO');
        $this->entity->alanId = 81;
        $this->entity->belgeNo = '11';
        $this->entity->belgeTarihi = '01/01/2020';
        $this->entity->universiteId = "202951";
        $this->entity->fakulteId = "252237";
    }

    public function test_it_should_create()
    {
        $this->entity->id = explode(':', $this->client->pedagojikFormasyon()->create($this->entity))[1];
    }


    public function test_it_should_find()
    {
        $this->entity = $this->client->pedagojikFormasyon()->find($this->entity->id);
    }

    public function test_it_should_get_all()
    {
        $entities = $this->client->pedagojikFormasyon()->all();
        $this->assertTrue(count($entities) > 0);
    }

    public function test_it_should_query()
    {
        $entities = $this->client->pedagojikFormasyon()->query(['tcKimlikNo' => getenv('TEST_TCKNO')]);
        $this->assertTrue(count($entities) > 0);
    }

    public function test_it_should_not_find()
    {
        $entities = $this->client->pedagojikFormasyon()->query(['tcKimlikNo' => 'zzzzz']);
        $this->assertTrue(count($entities) == 0);
    }


    public function test_it_should_delete()
    {
        $entities = $this->client->pedagojikFormasyon()->all();
        foreach ($entities as $entity) {
            $this->client->pedagojikFormasyon()->delete($entity->id);
        }
    }


}
