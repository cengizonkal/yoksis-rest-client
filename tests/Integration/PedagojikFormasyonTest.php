<?php

namespace Conkal\YOKSIS\Tests\Integration;

use Conkal\YOKSIS\REST\Entities\PedagojikFormasyon;

class PedagojikFormasyonTest extends TestCase
{
    public function test_create_query_delete()
    {
        $tckno = $this->env('TEST_TCKNO');

        $entity = new PedagojikFormasyon();
        $entity->tcKimlikNo = $tckno;
        $entity->alanId = 81;
        $entity->belgeNo = '11';
        $entity->belgeTarihi = '01/01/2020';
        $entity->universiteId = $this->env('TEST_UNIVERSITEID');
        $entity->fakulteId = $this->env('TEST_BIRIMID');

        $this->client->pedagojikFormasyon()->create($entity);

        $entities = $this->client->pedagojikFormasyon()->query(['tcKimlikNo' => $tckno]);
        $this->assertNotEmpty($entities);

        foreach ($entities as $item) {
            $this->client->pedagojikFormasyon()->delete($item->id);
        }
        $this->assertEmpty($this->client->pedagojikFormasyon()->query(['tcKimlikNo' => $tckno]));
    }
}
