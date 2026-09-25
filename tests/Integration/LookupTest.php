<?php

namespace Conkal\YOKSIS\Tests\Integration;

class LookupTest extends TestCase
{
    public function test_hazirlik_turleri()
    {
        $entities = $this->client->hazirlikTurleri()->all();
        $this->assertNotEmpty($entities);
        $this->assertNotNull($entities[0]->ad);
    }

    public function test_pedagojik_formasyon_alanlari()
    {
        $entities = $this->client->pedagojikFormasyonAlanlari()->all();
        $this->assertNotEmpty($entities);
        $this->assertNotNull($entities[0]->ad);
    }

    public function test_askerlik_erteleme_referans()
    {
        $items = $this->client->askerlikErtelemeReferans()->find('ERTELEME_IPTAL_NEDENLERI');
        $this->assertNotNull($items);
    }

    public function test_yerlestirme_veri()
    {
        $this->assertIsArray($this->client->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']));
    }

    public function test_kyk_ogrenci_sorgula()
    {
        $this->assertIsArray($this->client->kykOgrenciSorgula()->query(['tcKimlikNo' => $this->env('TEST_TCKNO')]));
    }
}
