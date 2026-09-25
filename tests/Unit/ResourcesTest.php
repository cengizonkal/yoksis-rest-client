<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\REST\Entities\AskerlikErtelemeReferans;
use Conkal\YOKSIS\REST\Entities\HazirlikTuru;
use Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula;
use Conkal\YOKSIS\REST\Entities\OgrenciIzin;
use Conkal\YOKSIS\REST\Entities\PedagojikFormasyon;
use Conkal\YOKSIS\REST\Entities\YerlestirmeVeri;
use GuzzleHttp\Psr7\Response;

class ResourcesTest extends TestCase
{
    public function test_all_hydrates_entities()
    {
        $entities = $this->client([self::json([['kod' => 1, 'ad' => 'Zorunlu'], ['kod' => 2, 'ad' => 'İsteğe Bağlı']])])
            ->hazirlikTurleri()->all();

        $this->assertCount(2, $entities);
        $this->assertInstanceOf(HazirlikTuru::class, $entities[0]);
        $this->assertSame('İsteğe Bağlı', $entities[1]->ad);
        $this->assertSame(\Conkal\YOKSIS\REST\YOK::TEST_URI . 'hazirlikturleri', (string)$this->lastRequest()->getUri());
    }

    public function test_all_returns_empty_array_for_empty_body()
    {
        $this->assertSame([], $this->client([new Response(200)])->pedagojikFormasyonAlanlari()->all());
    }

    public function test_query_sends_query_string()
    {
        $entities = $this->client([self::json([['id' => 3, 'tcKimlikNo' => '11111111111']])])
            ->ogrenciIzinler()->query(['tcKimlikNo' => '11111111111']);

        $this->assertInstanceOf(OgrenciIzin::class, $entities[0]);
        $this->assertSame('tcKimlikNo=11111111111', $this->lastRequest()->getUri()->getQuery());
    }

    public function test_query_wraps_single_object_response()
    {
        $entities = $this->client([self::json(['tcKimlikNo' => '1', 'krediBursDurumu' => ['kod' => 1, 'ad' => 'Burs']])])
            ->kykOgrenciSorgula()->query(['tcKimlikNo' => '1']);

        $this->assertCount(1, $entities);
        $this->assertInstanceOf(KykOgrenciSorgula::class, $entities[0]);
        $this->assertSame('Burs', $entities[0]->krediBursDurumu->ad);
    }

    public function test_find_returns_single_entity()
    {
        $entity = $this->client([self::json([['id' => 7]])])->pedagojikFormasyon()->find(7);

        $this->assertInstanceOf(PedagojikFormasyon::class, $entity);
        $this->assertSame(7, $entity->id);
        $this->assertStringEndsWith('pedagojikFormasyon/7', $this->lastRequest()->getUri()->getPath());
    }

    public function test_find_accepts_single_object_response()
    {
        $entity = $this->client([self::json(['id' => 7])])->pedagojikFormasyon()->find(7);
        $this->assertSame(7, $entity->id);
    }

    public function test_find_returns_list_for_multiple_items()
    {
        $items = $this->client([self::json([['nedenNo' => 1], ['nedenNo' => 2]])])
            ->askerlikErtelemeReferans()->find('ERTELEME_IPTAL_NEDENLERI');

        $this->assertCount(2, $items);
        $this->assertInstanceOf(AskerlikErtelemeReferans::class, $items[1]);
    }

    public function test_find_returns_null_when_nothing_found()
    {
        $this->assertNull($this->client([self::json([])])->pedagojikFormasyon()->find(1));
    }

    public function test_create_posts_entity_as_json()
    {
        $entity = new PedagojikFormasyon(['tcKimlikNo' => '1', 'alanId' => 81]);
        $result = $this->client([self::json('KAYIT:42')])->pedagojikFormasyon()->create($entity);

        $request = $this->lastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $body = json_decode((string)$request->getBody(), true);
        $this->assertSame('1', $body['tcKimlikNo']);
        $this->assertSame(81, $body['alanId']);
        $this->assertSame('KAYIT:42', $result);
    }

    public function test_create_returns_raw_body_when_not_json()
    {
        $result = $this->client([new Response(200, [], 'Kayıt başarılı')])
            ->hazirlikDetay()->create(new \Conkal\YOKSIS\REST\Entities\HazirlikDetay());

        $this->assertSame('Kayıt başarılı', $result);
    }

    public function test_delete_sends_delete_request()
    {
        $this->client([new Response(200)])->ogrenciIzinler()->delete(15);

        $request = $this->lastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertStringEndsWith('ogrenciizinler/15', $request->getUri()->getPath());
    }

    public function test_yerlestirme_veri_reads_paged_content()
    {
        $entities = $this->client([self::json(['content' => [['tcKimlikNo' => '1'], ['tcKimlikNo' => '2']], 'totalElements' => 2])])
            ->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']);

        $this->assertCount(2, $entities);
        $this->assertInstanceOf(YerlestirmeVeri::class, $entities[0]);
        $this->assertSame('tur=YKS&yil=2019', $this->lastRequest()->getUri()->getQuery());
    }

    public function test_yerlestirme_veri_handles_missing_content()
    {
        $this->assertSame([], $this->client([self::json([])])->yerlestirmeVeri()->query([]));
    }

    public function test_fotograf_indir_saves_file()
    {
        $path = tempnam(sys_get_temp_dir(), 'yoksis');
        $bytes = $this->client([new Response(200, ['Content-Type' => 'image/jpeg'], 'resim')])
            ->fotografIndir()->save('11111111111', $path);

        $this->assertSame(5, $bytes);
        $this->assertSame('resim', file_get_contents($path));
        $this->assertSame('tcKimlikNo=11111111111', $this->lastRequest()->getUri()->getQuery());
        unlink($path);
    }
}
