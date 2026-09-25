<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Entities\PedagojikFormasyon;
use Conkal\YOKSIS\REST\Entities\Transkript\Ders;
use Conkal\YOKSIS\REST\Entities\Transkript\Donem;
use Conkal\YOKSIS\REST\Entities\Transkript\NotBaremi;
use Conkal\YOKSIS\REST\Entities\Transkript\NotDTO;
use Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript;
use Conkal\YOKSIS\REST\Entities\Transkript\Tez;

class EntityTest extends TestCase
{
    public function test_it_fills_from_array_and_object()
    {
        $fromArray = new PedagojikFormasyon(['id' => 5, 'adi' => 'Ali']);
        $fromObject = new PedagojikFormasyon((object)['id' => 5, 'adi' => 'Ali']);

        $this->assertSame(5, $fromArray->id);
        $this->assertSame('Ali', $fromArray->adi);
        $this->assertEquals($fromArray, $fromObject);
    }

    public function test_it_accepts_unknown_fields_without_deprecation()
    {
        $entity = new PedagojikFormasyon(['yeniAlan' => 'deger']);
        $this->assertSame('deger', $entity->yeniAlan);
        $this->assertSame('deger', $entity->toArray()['yeniAlan']);
    }

    public function test_to_array_converts_nested_entities()
    {
        $transkript = new OgrenciTranskript(['ogrenciId' => '1']);
        $transkript->tez = new Tez(['tezBasligi' => 'Başlık']);
        $transkript->donemler = [
            new Donem(['donemYili' => '2018-2019', 'dersler' => [new Ders(['dersinKodu' => 'MAT101'])]]),
        ];
        $transkript->notBaremleri = [
            new NotBaremi(['notBaremi' => 'AA', 'not' => [new NotDTO(['katsayi' => '4'])]]),
        ];

        $array = $transkript->toArray();

        $this->assertSame('Başlık', $array['tez']['tezBasligi']);
        $this->assertSame('MAT101', $array['donemler'][0]['dersler'][0]['dersinKodu']);
        $this->assertSame('4', $array['notBaremleri'][0]['not'][0]['katsayi']);
        $this->assertSame(json_encode($array), json_encode($transkript));
    }
}
