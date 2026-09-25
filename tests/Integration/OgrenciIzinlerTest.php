<?php

namespace Conkal\YOKSIS\Tests\Integration;

use Conkal\YOKSIS\REST\Entities\OgrenciIzin;

class OgrenciIzinlerTest extends TestCase
{
    public function test_create_query_delete()
    {
        $tckno = $this->env('TEST_TCKNO');

        $entity = new OgrenciIzin();
        $entity->tcKimlikNo = $tckno;
        $entity->birimID = $this->env('TEST_BIRIMID');
        $entity->kararTarihi = '01/01/2020';
        $entity->izinBaslangicTarihi = '01/01/2020';
        $entity->izinBitisTarihi = '01/01/2021';
        $entity->izinSuresi = 1;
        $this->client->ogrenciIzinler()->create($entity);

        $izinler = $this->client->ogrenciIzinler()->query(['tcKimlikNo' => $tckno]);
        $this->assertNotEmpty($izinler);

        foreach ($izinler as $izin) {
            $this->client->ogrenciIzinler()->delete($izin->id);
        }
    }
}
