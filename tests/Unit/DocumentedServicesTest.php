<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\Constants\Askerlik\IslemSonucKodu;
use Conkal\YOKSIS\Constants\DonemTuru;
use Conkal\YOKSIS\REST\Entities;
use Conkal\YOKSIS\REST\Exceptions\NotFoundException;
use GuzzleHttp\Psr7\Response;

/**
 * "REST Servisler Yardım Dökümanı"nda tanımlanan servislerin istek biçimlerini doğrular.
 */
class DocumentedServicesTest extends TestCase
{
    private function assertRequest($method, $path, $query = '')
    {
        $request = $this->lastRequest();
        $this->assertSame($method, $request->getMethod());
        $this->assertSame('/resttest/obs/' . $path, $request->getUri()->getPath());
        $this->assertSame($query, $request->getUri()->getQuery());
        return $request;
    }

    private function body()
    {
        return json_decode((string)$this->lastRequest()->getBody(), true);
    }

    // 4. Ceza alan öğrenciler

    public function test_ogrenci_cezalar_crud()
    {
        $client = $this->client([
            self::json(['id' => 42461, 'tcKimlikNo' => 1, 'cezaID' => 2]),
            self::json([['id' => 1], ['id' => 2]]),
            new Response(201, [], '"ID:96375"'),
            new Response(200),
            new Response(200),
        ]);
        $cezalar = $client->ogrenciCezalar();

        $ceza = $cezalar->find(42461);
        $this->assertRequest('GET', 'ogrencicezalar/42461');
        $this->assertInstanceOf(Entities\OgrenciCeza::class, $ceza);
        $this->assertSame(2, $ceza->cezaID);

        $this->assertCount(2, $cezalar->query(['tcKimlikNo' => '11111111111']));
        $this->assertRequest('GET', 'ogrencicezalar', 'tcKimlikNo=11111111111');

        $yeni = new Entities\OgrenciCeza(['tcKimlikNo' => '1', 'birimID' => 158557, 'cezaID' => '1', 'cezaMahkemeIptalMi' => '2']);
        $this->assertSame('ID:96375', $cezalar->create($yeni));
        $this->assertRequest('POST', 'ogrencicezalar');
        $this->assertSame('1', $this->body()['cezaID']);

        $yeni->id = 31553;
        $cezalar->update($yeni);
        $this->assertRequest('PUT', 'ogrencicezalar/31553');
        $this->assertSame(31553, $this->body()['id']);

        $cezalar->delete(96375);
        $this->assertRequest('DELETE', 'ogrencicezalar/96375');
    }

    public function test_ceza_turleri()
    {
        $turler = $this->client([self::json([['kod' => -1, 'ad' => 'Yok'], ['kod' => 1, 'ad' => 'Uyarma']])])
            ->cezaTurleri()->all();

        $this->assertRequest('GET', 'cezaturleri');
        $this->assertInstanceOf(Entities\CezaTuru::class, $turler[1]);
        $this->assertSame('Uyarma', $turler[1]->ad);
    }

    // 5. İzinler

    public function test_ogrenci_izin_update_and_find()
    {
        $client = $this->client([new Response(200), self::json(['id' => 15, 'izinSuresi' => '2'])]);

        $client->ogrenciIzinler()->update(new Entities\OgrenciIzin(['id' => '15', 'izinSuresi' => '2']));
        $this->assertRequest('PUT', 'ogrenciizinler/15');

        $izin = $client->ogrenciIzinler()->find(15);
        $this->assertRequest('GET', 'ogrenciizinler/15');
        $this->assertSame('2', $izin->izinSuresi);
    }

    public function test_update_requires_an_id()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->client()->ogrenciIzinler()->update(new Entities\OgrenciIzin());
    }

    // 6-9. Mezunlar, duyurular, üniversiteler, teyitleşme

    public function test_mezunlar_are_paginated()
    {
        $client = $this->client([self::json([
            'content' => [['tcKimlikNo' => 1, 'birimID' => 158, 'adi' => 'A', 'soyadi' => 'B']],
            'totalElements' => 1, 'totalPages' => 1, 'number' => 0, 'size' => 1000,
        ])]);

        $page = $client->mezunlar()->paginate(['page' => 0, 'size' => 1000]);

        $this->assertRequest('GET', 'mezunlar', 'page=0&size=1000');
        $this->assertInstanceOf(Entities\Mezun::class, $page[0]);
        $this->assertSame(1, $page->getTotalElements());
        $this->assertTrue($page->isLast());
    }

    public function test_duyurular_and_universiteler()
    {
        $client = $this->client([
            self::json([['duyuruId' => 422, 'duyuru' => 'Yatay geçiş kontenjanları']]),
            self::json([['birimID' => 108153, 'birimAdi' => 'DOKUZ EYLÜL ÜNİVERSİTESİ', 'aktif' => ['kod' => 1, 'ad' => 'Aktif']]]),
        ]);

        $duyurular = $client->duyurular()->all();
        $this->assertRequest('GET', 'duyurular');
        $this->assertInstanceOf(Entities\Duyuru::class, $duyurular[0]);
        $this->assertSame(422, $duyurular[0]->duyuruId);

        $universiteler = $client->universiteler()->all();
        $this->assertRequest('GET', 'universiteler');
        $this->assertInstanceOf(Entities\Universite::class, $universiteler[0]);
        $this->assertSame('Aktif', $universiteler[0]->aktif->ad);
    }

    public function test_son_basarili_teyitlesme()
    {
        $teyit = $this->client([self::json(['universiteID' => 117127, 'sonBasariliTeyitlesmeTarihi' => '10.07.2017 10:34:31'])])
            ->teyitlesme()->sonBasarili();

        $this->assertRequest('GET', 'sonbasariliteyitlesme');
        $this->assertInstanceOf(Entities\Teyitlesme::class, $teyit);
        $this->assertSame('10.07.2017 10:34:31', $teyit->sonBasariliTeyitlesmeTarihi);
    }

    // 10-11. Hazırlık detay ve pedagojik formasyon güncelleme

    public function test_hazirlik_detay_and_pedagojik_formasyon_update()
    {
        $client = $this->client([new Response(200), new Response(200)]);

        $client->hazirlikDetay()->update(new Entities\HazirlikDetay(['id' => '94278', 'hazirlikNotu' => '4']));
        $this->assertRequest('PUT', 'hazirlikdetay/94278');
        $this->assertSame('4', $this->body()['hazirlikNotu']);

        $client->pedagojikFormasyon()->update(new Entities\PedagojikFormasyon(['id' => '2', 'belgeNo' => '1A']));
        $this->assertRequest('PUT', 'pedagojikFormasyon/2');
        $this->assertSame('2', $this->body()['id']);
    }

    // 12. ASAL

    public function test_askerlik_durum_sorgula()
    {
        $durum = $this->client([self::json(['tcKimlikNo' => 1, 'askerlikDurumKod' => 101, 'askerlikErtDurumKod' => 1, 'islemKod' => 'EDV09.000'])])
            ->askerlikDurum()->sorgula('11111111111');

        $this->assertRequest('GET', 'askerlikDurumSorgula', 'tcKimlikNo=11111111111');
        $this->assertInstanceOf(Entities\AskerlikDurum::class, $durum);
        $this->assertSame(101, $durum->askerlikDurumKod);
    }

    public function test_askerlik_talep_sonucu()
    {
        $sonuc = $this->client([self::json([
            'islemSonucu' => ['aciklama' => 'sonuçlanmıştır', 'islemSonucKodu' => 'EDV09.000'],
            'sonuc' => 'E',
            'ertBitTarihi' => '31/12/2025',
        ])])->askerlikErtelemeTalep()->sonuc('L196ABC');

        $this->assertRequest('GET', 'askerlikErtelemeTalep/L196ABC');
        $this->assertSame('L196ABC', $sonuc->talepKayitUid);
        $this->assertSame('E', $sonuc->sonuc);
        $this->assertSame(IslemSonucKodu::BASARILI, $sonuc->islemSonucKodu());
        $this->assertFalse($sonuc->beklemedeMi());
    }

    public function test_askerlik_talep_pending_returns_206()
    {
        $mesaj = "EDV09.007:Gönderilen Talep Kayıt id'ye ait erteleme, beklemektedir!";
        $sonuc = $this->client([new Response(206, [], json_encode($mesaj))])
            ->askerlikErtelemeTalep()->sonuc('L196ABC');

        $this->assertTrue($sonuc->beklemedeMi());
        $this->assertSame($mesaj, $sonuc->islemSonucu->aciklama);
    }

    public function test_askerlik_talep_unknown_uid_throws_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->client([new Response(404, [], "EDV09.006:Gönderilen Talep Kayıt id'yi kontrol ediniz!")])
            ->askerlikErtelemeTalep()->sonuc('YOK');
    }

    public function test_askerlik_talep_delete()
    {
        $this->client([self::json(['islemSonucu' => ['islemSonucKodu' => 'EDV09.010']])])
            ->askerlikErtelemeTalep()->delete('L196ABC');

        $this->assertRequest('DELETE', 'askerlikErtelemeTalep/L196ABC');
    }

    // 13. Yatay geçiş

    public function test_yatay_gecisler()
    {
        $items = $this->client([self::json([['tcKimlikNo' => 1, 'gecisYaptigiBirimID' => 364403, 'gecisKayitTarihi' => '18/10/2018']])])
            ->yatayGecisler()->yilaGore(2018);

        $this->assertRequest('GET', 'yatayGecisListele', 'yil=2018');
        $this->assertInstanceOf(Entities\YatayGecis::class, $items[0]);
        $this->assertSame(364403, $items[0]->gecisYaptigiBirimID);
    }

    // 14. Yerleştirme, fotoğraf, vakıf öğrenim ücreti

    public function test_yerlestirme_veri_reads_firstpage_lastpage_flags()
    {
        $page = $this->client([self::json([
            'content' => [['tcKimlikNo' => 1]],
            'firstPage' => false, 'lastPage' => true, 'numberOfElements' => 1,
            'totalElements' => 9781, 'totalPages' => 10, 'size' => 1000, 'number' => 8,
        ])])->yerlestirmeVeri()->paginate(['yil' => 2019, 'tur' => DonemTuru::YKS, 'ekayitOlanlar' => 'true']);

        $this->assertRequest('GET', 'yerlestirmeveri', 'yil=2019&tur=YKS&ekayitOlanlar=true');
        $this->assertFalse($page->isFirst());
        $this->assertTrue($page->isLast());
        $this->assertSame(9781, $page->getTotalElements());
    }

    public function test_toplu_fotograf_is_streamed_to_disk()
    {
        $path = tempnam(sys_get_temp_dir(), 'yoksis') . '.zip';
        $saved = $this->client([new Response(200, ['Content-Type' => 'application/octet-stream'], 'PK-zip')])
            ->fotografIndir()->topluKaydet(2019, DonemTuru::YKS, $path);

        $this->assertRequest('GET', 'toplufotografindir', 'yil=2019&tur=YKS');
        $this->assertSame($path, $saved);
        $this->assertSame('PK-zip', file_get_contents($path));
        unlink($path);
    }

    public function test_vakif_ogrenim_ucreti()
    {
        $this->client([new Response(200)])->vakifOgrenimUcreti()->bildir('12345678901', true, 2019, DonemTuru::DGS);

        $this->assertRequest('POST', 'vakifogrenimucreti', 'tcKimlikNo=12345678901&ogrenimUcretiOdendiMi=true&yil=2019&tur=DGS');
        $this->assertSame(
            ['tcKimlikNo' => '12345678901', 'ogrenimUcretiOdendiMi' => 'true', 'yil' => 2019, 'tur' => 'DGS'],
            $this->body()
        );
    }

    // 15. Transkript

    public function test_transkript_by_tc_and_birim()
    {
        $transkript = $this->client([self::json(['ogrenciId' => 5, 'tcKimlikNo' => 1])])
            ->ogrenciTranskript()->findByTcKimlikNo('11111111111', 158914);

        $this->assertRequest('GET', 'ogrencitranskript/11111111111/158914');
        $this->assertInstanceOf(Entities\Transkript\OgrenciTranskript::class, $transkript);
    }

    // 16-17. MEB ve Sağlık Bakanlığı

    public function test_meb_mezun_detay_and_saglik_tescil()
    {
        $client = $this->client([
            self::json([['tcKimlikNo' => '1', 'okulTurAdi' => 'Lise', 'diplomaNotuPuani' => '89,59']]),
            self::json([['tckn' => 1, 'tahsilTuru' => 'Yük.Öğr.(6 YIL)', 'tescilNo' => 0]]),
        ]);

        $meb = $client->mebMezunDetay()->sorgula('11111111111');
        $this->assertRequest('GET', 'mebmezundetaysorgula', 'tcKimlikNo=11111111111');
        $this->assertInstanceOf(Entities\MebMezunDetay::class, $meb[0]);
        $this->assertSame('89,59', $meb[0]->diplomaNotuPuani);

        $tescil = $client->saglikBakanligiTescil()->sorgula('11111111111');
        $this->assertRequest('GET', 'saglikbakanligitescilsorgula', 'tcKimlikNo=11111111111');
        $this->assertInstanceOf(Entities\SaglikBakanligiTescil::class, $tescil[0]);
    }

    // 18. KYK

    public function test_kyk_single_and_counts()
    {
        $client = $this->client([
            self::json(['tcKimlikNo' => 1, 'krediBursDurumu' => ['kod' => 0, 'ad' => 'Almıyor']]),
            self::json([['krediTur' => 1, 'erkekOgrenciSayi' => 70, 'kizOgrenciSayi' => 23]]),
            self::json([['krediTur' => 3, 'erkekOgrenciSayi' => 5228, 'kizOgrenciSayi' => 0]]),
        ]);
        $kyk = $client->kykOgrenciSorgula();

        $this->assertSame('Almıyor', $kyk->sorgula('11111111111')->krediBursDurumu->ad);
        $this->assertRequest('GET', 'kykogrencisorgula', 'tcKimlikNo=11111111111');

        $sayilar = $kyk->ogrenciSayisi(156679);
        $this->assertRequest('GET', 'kykogrencisayisorgula', 'birimId=156679');
        $this->assertInstanceOf(Entities\KykOgrenciSayi::class, $sayilar[0]);
        $this->assertSame(70, $sayilar[0]->erkekOgrenciSayi);

        $yillik = $kyk->ogrenciSayisiYilaGore(156679, 1, 2021);
        $this->assertRequest('GET', 'kykogrencisayisorgulabyyil', 'birimId=156679&birimTur=1&yil=2021');
        $this->assertSame(5228, $yillik[0]->erkekOgrenciSayi);
    }

    public function test_kyk_bulk_query_is_chunked_by_100()
    {
        $tcler = [];
        for ($i = 0; $i < 150; $i++) {
            $tcler[] = (string)(10000000000 + $i);
        }
        $client = $this->client([
            self::json(array_fill(0, 100, ['tcKimlikNo' => 1])),
            self::json(array_fill(0, 50, ['tcKimlikNo' => 2])),
        ]);

        $sonuc = $client->kykOgrenciSorgula()->topluSorgula($tcler);

        $this->assertCount(150, $sonuc);
        $this->assertCount(2, $this->history);
        $this->assertRequest('POST', 'kykogrencitoplusorgula');
        $this->assertCount(50, $this->body());
        if (PHP_INT_SIZE >= 8) {
            $this->assertSame(10000000100, $this->body()[0]);
        }
    }

    // 19. Yurt dışından yatay geçiş

    public function test_yurt_disindan_yatay_gecis_unwraps_responses()
    {
        $kayit = ['id' => 6, 'tcKimlikNo' => 1, 'ulkeAdi' => 'TÜRKİYE'];
        $client = $this->client([
            self::json(['returnCode' => 1, 'data' => [$kayit], 'count' => 1]),
            self::json(['returnCode' => 1, 'data' => [$kayit], 'count' => 1]),
            self::json(['returnCode' => 1, 'data' => ['id' => 11], 'count' => 0]),
            self::json(['returnCode' => 1, 'data' => ['id' => 11], 'count' => 0]),
            self::json(['returnCode' => 1, 'data' => ['id' => 6], 'count' => 0]),
        ]);
        $resource = $client->yurtDisindanYatayGecis();

        $items = $resource->query(['tcKimlikNo' => '11111111111']);
        $this->assertRequest('GET', 'yurtDisindanYatayGecis', 'tcKimlikNo=11111111111');
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Entities\YurtDisindanYatayGecis::class, $items[0]);
        $this->assertSame('TÜRKİYE', $items[0]->ulkeAdi);

        $this->assertSame(6, $resource->find(6)->id);
        $this->assertRequest('GET', 'yurtDisindanYatayGecis/6');

        $this->assertSame(11, $resource->create(new Entities\YurtDisindanYatayGecis(['tcKimlikNo' => 1]))->id);
        $this->assertRequest('POST', 'yurtDisindanYatayGecis');

        $resource->update(new Entities\YurtDisindanYatayGecis(['id' => 11, 'notOrtalamasi' => 9.13]));
        $this->assertRequest('PUT', 'yurtDisindanYatayGecis');
        $this->assertSame(11, $this->body()['id']);

        $this->assertSame(6, $resource->delete(6)->id);
        $this->assertRequest('DELETE', 'yurtDisindanYatayGecis/6');
    }

    // 20. Öğrenci iletişim bilgileri

    public function test_ogrenci_iletisim_bilgileri()
    {
        $client = $this->client([
            self::json(['returnCode' => 1, 'data' => ['id' => 21], 'count' => 0]),
            self::json(['returnCode' => 1, 'data' => ['id' => 21], 'count' => 0]),
            new Response(200),
            new Response(200),
        ]);
        $iletisim = $client->ogrenciIletisimBilgileri();

        $kayit = new Entities\OgrenciIletisimBilgi(['tcKimlikNo' => 1, 'birimId' => 2, 'ePosta' => 'a@example.com']);
        $this->assertSame(21, $iletisim->create($kayit)->id);
        $this->assertRequest('POST', 'ogrenciIletisimBilgi');
        $this->assertSame('a@example.com', $this->body()['ePosta']);

        $iletisim->update(new Entities\OgrenciIletisimBilgi(['birimId' => 2, 'tcKimlikNo' => 1, 'ePosta' => 'b@example.com']));
        $this->assertRequest('PUT', 'ogrenciIletisimBilgi');

        $iletisim->delete(21);
        $this->assertRequest('DELETE', 'ogrenciIletisimBilgi/21');

        $iletisim->deleteByTcKimlikNo(2, '11111111111');
        $this->assertRequest('DELETE', 'ogrenciIletisimBilgi', 'birimId=2&tcKimlikNo=11111111111');
    }
}
