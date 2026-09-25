# YÖKSİS REST API Client

[![Tests](https://github.com/cengizonkal/yoksis-rest-client/actions/workflows/php.yml/badge.svg)](https://github.com/cengizonkal/yoksis-rest-client/actions/workflows/php.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

YÖKSİS (Yükseköğretim Bilgi Sistemi) OBS REST servisleri için PHP istemcisi.

*A PHP client for the Turkish Council of Higher Education (YÖK) YÖKSİS OBS REST services.*

## Gereksinimler

- PHP 7.2.5 veya üzeri (PHP 8.x desteklenir)
- Guzzle 6.5 veya 7.x

## Kurulum

```bash
composer require conkal/yoksis-rest-client
```

0.2.x sürümünden yükseltiyorsanız geriye uyumsuz değişiklikler için [CHANGELOG.md](CHANGELOG.md) dosyasına bakın.

## Kullanım

```php
use Conkal\YOKSIS\REST\YOK;

require __DIR__ . '/vendor/autoload.php';

// canlı ortam için YOK::PRODUCTION_URI
$client = YOK::create(YOK::TEST_URI, getenv('YOKSIS_USERNAME'), getenv('YOKSIS_PASSWORD'));
```

`YOK::create()` zaman aşımı (30 sn) ve geçici hatalarda yeniden deneme ayarlarıyla hazır bir istemci döndürür;
ayrıntılar için [Yeniden deneme ve loglama](#yeniden-deneme-ve-loglama) bölümüne bakın.

İstemciyi elle de kurabilirsiniz. Proxy gibi özel ayarlar için kendi Guzzle istemcinizi verin:

```php
use Conkal\YOKSIS\REST\Utilities\BasicAuth;

$http = new \GuzzleHttp\Client(['timeout' => 30]);
$client = new YOK(YOK::TEST_URI, $http, new BasicAuth($user, $pass));
```

### Kaynak metotları

| Metot | Açıklama |
|---|---|
| `all()` | Tüm kayıtları entity dizisi olarak döndürür. |
| `query(array $query)` | Sorgu parametreleriyle arama yapar, entity dizisi döndürür (sonuç yoksa `[]`). |
| `find($id)` | Tek kayıt dönerse entity, birden fazla kayıt dönerse entity dizisi, hiç kayıt yoksa `null` döndürür. |
| `create(Entity $entity)` | Kaydı gönderir, servisin yanıtını döndürür. |
| `delete($id)` | Kaydı siler. |

### Desteklenen servisler

Servisler YÖK'ün "REST Servisler Yardım Dökümanı" (sürüm 2.0) esas alınarak hazırlanmıştır.
Bölüm numaraları dokümandakilerdir.

| Bölüm | Erişim | Servis | Metotlar |
|---|---|---|---|
| 4 | `$client->ogrenciCezalar()` | `ogrencicezalar` | all, query, find, create, update, delete |
| 4 | `$client->cezaTurleri()` | `cezaturleri` | all |
| 5 | `$client->ogrenciIzinler()` | `ogrenciizinler` | query, find, create, update, delete |
| 6 | `$client->mezunlar()` | `mezunlar` | query, paginate, cursor |
| 7 | `$client->duyurular()` | `duyurular` | all |
| 8 | `$client->universiteler()` | `universiteler` | all |
| 9 | `$client->teyitlesme()` | `sonbasariliteyitlesme` | sonBasarili |
| 10 | `$client->hazirlikTurleri()` | `hazirlikturleri` | all |
| 10 | `$client->hazirlikDetay()` | `hazirlikdetay` | all, query, find, create, update, delete |
| 11 | `$client->pedagojikFormasyonAlanlari()` | `pedagojikformasyonalanlari` | all |
| 11 | `$client->pedagojikFormasyon()` | `pedagojikFormasyon` | all, query, find, create, update, delete |
| 12 | `$client->askerlikDurum()` | `askerlikDurumSorgula` | sorgula |
| 12 | `$client->askerlikErtelemeReferans()` | `askerlikErtelemeReferans` | find |
| 12 | `$client->askerlikErtelemeTalep()` | `askerlikErtelemeTalep` | create, sonuc, delete, query |
| 13 | `$client->yatayGecisler()` | `yatayGecisListele` | query, yilaGore |
| 14 | `$client->yerlestirmeVeri()` | `yerlestirmeveri` | query, paginate, cursor |
| 14 | `$client->fotografIndir()` | `fotografindir`, `toplufotografindir` | find, save, toplu, topluKaydet |
| 14 | `$client->vakifOgrenimUcreti()` | `vakifogrenimucreti` | bildir |
| 15 | `$client->ogrenciTranskript()` | `ogrencitranskript` | find, findByTcKimlikNo, create, delete |
| 16 | `$client->mebMezunDetay()` | `mebmezundetaysorgula` | sorgula |
| 17 | `$client->saglikBakanligiTescil()` | `saglikbakanligitescilsorgula` | sorgula |
| 18 | `$client->kykOgrenciSorgula()` | `kykogrencisorgula` ve ilgili servisler | sorgula, query, topluSorgula, ogrenciSayisi, ogrenciSayisiYilaGore |
| 19 | `$client->yurtDisindanYatayGecis()` | `yurtDisindanYatayGecis` | all, query, find, create, update, delete |
| 20 | `$client->ogrenciIletisimBilgileri()` | `ogrenciIletisimBilgi` | create, update, delete, deleteByTcKimlikNo |

Dokümandaki kod listeleri `Conkal\YOKSIS\Constants` altında sabit olarak bulunur: `DonemTuru` (YKS, DGS, ...),
`OgrenciIzin\IzinTuru`, `Ceza\CezaMahkemeIptal`, `HazirlikDetay\HazirlikTuru`, `Askerlik\*` (referans türleri,
durum ve sonuç kodları) ve `Kyk\KrediTuru`.

> **Doğrulanmamış varsayımlar:** Dokümanda yolu ya da parametre yeri açıkça gösterilmeyen üç çağrı diğer
> servislerle aynı kalıba göre yazılmıştır: `pedagojikFormasyon()->update()` (`PUT pedagojikFormasyon/{id}`),
> `ogrenciIzinler()->find()` (`GET ogrenciizinler/{id}`) ve `vakifOgrenimUcreti()->bildir()` (parametreler hem
> sorgu dizesinde hem gövdede gönderilir). İzin türleri servisinin (`izinTurleriniListele`) yolu dokümanda
> olmadığından eklenmedi; değerler `IzinTuru` sabitlerindedir.

Listede olmayan bir servisi ham olarak çağırmak için `send()` kullanılabilir:

```php
$response = $client->send('servisAdi', ['query' => ['tcKimlikNo' => '<tckno>']]);
$data = json_decode((string) $response->getBody());
```

## Örnekler

### Pedagojik Formasyon

```php
use Conkal\YOKSIS\REST\Entities\PedagojikFormasyon;

$pedagojikFormasyon = new PedagojikFormasyon();
$pedagojikFormasyon->tcKimlikNo = '<tckno>';
$pedagojikFormasyon->alanId = 1;
$pedagojikFormasyon->belgeNo = '<belge no>';
$pedagojikFormasyon->belgeTarihi = '<d/m/Y>';
$pedagojikFormasyon->universiteId = '<universite id>';
$pedagojikFormasyon->fakulteId = '<fakülte id>';

// kaydet
$client->pedagojikFormasyon()->create($pedagojikFormasyon);

// sorgula
$kayitlar = $client->pedagojikFormasyon()->query(['tcKimlikNo' => '<tckno>']);

// sil
$client->pedagojikFormasyon()->delete($kayitlar[0]->id);
```

Entity'ler dizi ile de doldurulabilir:

```php
$pedagojikFormasyon = new PedagojikFormasyon([
    'tcKimlikNo' => '<tckno>',
    'alanId' => 1,
]);
```

### Yerleşen Verisi

Servis sayfalı yanıt döndürür. `query()` yalnızca istenen sayfadaki kayıtları dizi olarak verir:

```php
/** @var \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[] $yerlesenler */
$yerlesenler = $client->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']);
```

Toplam kayıt ve sayfa sayısı gibi bilgiler için `paginate()` kullanın. Dönen `Page` nesnesi dizi gibi gezilebilir:

```php
$sayfa = $client->yerlestirmeVeri()->paginate(['tur' => 'YKS', 'yil' => '2019', 'page' => 0, 'size' => 100]);

$sayfa->getTotalElements(); // toplam kayıt
$sayfa->getTotalPages();    // toplam sayfa
$sayfa->getPageNumber();    // 0'dan başlayan sayfa numarası
$sayfa->hasMorePages();

foreach ($sayfa as $yerlesen) { /* ... */ }
```

Tüm sayfaları tek tek elle çekmek yerine `cursor()` sayfaları ihtiyaç duyuldukça çeker; bellekte tek seferde yalnızca bir sayfa tutulur:

```php
foreach ($client->yerlestirmeVeri()->cursor(['tur' => 'YKS', 'yil' => '2019'], 500) as $yerlesen) {
    // ...
}
```

Parametreler: `yil` ve `tur` zorunludur (`tur` için `Constants\DonemTuru`). İsteğe bağlı olarak `tcKimlikNo`,
`ekayitOlanlar` (`'true'`/`'false'`) ve `ekayitTarihi` (dd/MM/yyyy) ile filtrelenebilir. `page` 0'dan başlar
(varsayılan 0), `size` sayfa boyutudur (varsayılan 1000). Servis sayfa parametresini yok sayarsa `cursor()`
sonsuz döngüye girmez, durur.

### Hazırlık Detay

```php
use Conkal\YOKSIS\Constants\HazirlikDetay\MuafiyetDurumu;
use Conkal\YOKSIS\REST\Entities\HazirlikDetay;

$detay = new HazirlikDetay();
$detay->tckno = '<tckno>';
$detay->hazirlikTuru = 2;
$detay->ogretimDili = 1;
$detay->hazirlikDonemNo = 1;
$detay->muafiyetDurumu = MuafiyetDurumu::MUAF_DEGIL;
$detay->birimId = '<birim id>';

// kaydet
$client->hazirlikDetay()->create($detay);
```

### Ceza Alan Öğrenciler

```php
use Conkal\YOKSIS\Constants\Ceza\CezaMahkemeIptal;
use Conkal\YOKSIS\REST\Entities\OgrenciCeza;

$ceza = new OgrenciCeza([
    'tcKimlikNo' => '<tckno>',
    'birimID' => '<birim id>',
    'cezaID' => 1, // $client->cezaTurleri()->all() ile alınan kod
    'yonetmelikMaddeFikra' => '2',
    'cezaMahkemeIptalMi' => CezaMahkemeIptal::HAYIR,
    'cezaTarihi' => '12/07/2017',
    'cezaBaslangicTarihi' => '13/07/2017',
    'cezaBitisTarihi' => '13/09/2018',
]);
$sonuc = $client->ogrenciCezalar()->create($ceza); // "ID:96375"

$cezalar = $client->ogrenciCezalar()->query(['tcKimlikNo' => '<tckno>']);
$cezalar[0]->cezaBitisTarihi = '18/07/2018';
$client->ogrenciCezalar()->update($cezalar[0]);
```

### Mezunlar, Duyurular, Üniversiteler, Teyitleşme

```php
foreach ($client->mezunlar()->cursor() as $mezun) {
    echo $mezun->tcKimlikNo, ' ', $mezun->adi, ' ', $mezun->soyadi, PHP_EOL;
}

$duyurular = $client->duyurular()->all();
$universiteler = $client->universiteler()->all();
$teyit = $client->teyitlesme()->sonBasarili(); // $teyit->sonBasariliTeyitlesmeTarihi
```

### Öğrenci İzinleri

```php
use Conkal\YOKSIS\REST\Entities\OgrenciIzin;

$izin = new OgrenciIzin([
    'tcKimlikNo' => '<tckno>',
    'birimID' => '<birim id>',
    'kararTarihi' => '01/01/2020',
    'izinBaslangicTarihi' => '01/01/2020',
    'izinBitisTarihi' => '01/01/2021',
    'izinSuresi' => 1,
]);
$client->ogrenciIzinler()->create($izin);

$izinler = $client->ogrenciIzinler()->query(['tcKimlikNo' => '<tckno>']);
```

### Askerlik Erteleme

```php
use Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep;

// teklif nedenleri
$nedenler = $client->askerlikErtelemeReferans()->find('ERTELEME_TEKLIF_NEDENLERI');

$talep = new AskerlikErtelemeTalep();
$talep->tcKimlikNo = '<tckno>';
$talep->birimId = '<birim id>';
$talep->teklifTuru = 'E'; // Erteleme için E, iptal için I
$talep->teklifNedeniNo = 501;
$talep->imzalayanTcNo = '<yetkili tckno>';
$talep->imzalayanAdSoyad = '<yetkili ad soyad>';

$yanit = $client->askerlikErtelemeTalep()->create($talep);

// 3-15 gün içinde sonuçlanır; günde en fazla bir kez sorgulanması önerilir.
$sonuc = $client->askerlikErtelemeTalep()->sonuc($yanit->talepKayitUid);
if ($sonuc->beklemedeMi()) {
    // henüz sonuçlanmadı (HTTP 206)
} elseif ($sonuc->sonuc === \Conkal\YOKSIS\Constants\Askerlik\TalepSonucu::ERTELEME) {
    echo $sonuc->ertBitTarihi;
}
```

Talepten önce öğrencinin askerlik durumunu sorgulayabilirsiniz:

```php
$durum = $client->askerlikDurum()->sorgula('<tckno>');
// $durum->askerlikDurumKod, $durum->askerlikErtDurumKod (bkz. Constants\Askerlik\DurumKodu, ErtelemeDurumu)
```

### KYK Öğrenci Sorgulama

```php
$kyk = $client->kykOgrenciSorgula();

$ogrenci = $kyk->sorgula('<tckno>'); // $ogrenci->krediBursDurumu->ad, $ogrenci->yurtBarinmaDurumu->ad
$liste = $kyk->topluSorgula($tcKimlikNolar); // 100'den fazlaysa otomatik parçalara bölünür
$sayilar = $kyk->ogrenciSayisi('<birim id>');
$yillik = $kyk->ogrenciSayisiYilaGore('<birim id>', '<birim tür>', 2024);
```

### MEB ve Sağlık Bakanlığı

```php
$liseMezuniyeti = $client->mebMezunDetay()->sorgula('<tckno>');
$tescil = $client->saglikBakanligiTescil()->sorgula('<tckno>');
```

### Yurt Dışından Yatay Geçiş ve Öğrenci İletişim Bilgileri

Bu servislerin `{"returnCode", "data", "count"}` zarfı kütüphane tarafından açılır:

```php
use Conkal\YOKSIS\REST\Entities\OgrenciIletisimBilgi;

$kayitlar = $client->yurtDisindanYatayGecis()->query(['tcKimlikNo' => '<tckno>']);

$yanit = $client->ogrenciIletisimBilgileri()->create(new OgrenciIletisimBilgi([
    'tcKimlikNo' => '<tckno>',
    'birimId' => '<birim id>',
    'ePosta' => 'ogrenci@example.com',
])); // $yanit->id
```

### Fotoğraf İndirme

```php
// dosyaya kaydet
$client->fotografIndir()->save('<tckno>', '/tmp/<tckno>.jpg');

// bir yerleştirmenin tüm fotoğrafları (zip, belleğe alınmadan diske yazılır)
$client->fotografIndir()->topluKaydet(2024, \Conkal\YOKSIS\Constants\DonemTuru::YKS, '/tmp/fotograflar.zip');

// ya da ham yanıtı al
$response = $client->fotografIndir()->find('<tckno>');
```

### Öğrenci Transkript

```php
use Conkal\YOKSIS\REST\Entities\Transkript\Ders;
use Conkal\YOKSIS\REST\Entities\Transkript\Donem;
use Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript;

$transkript = new OgrenciTranskript();
$transkript->ogrenciId = '<ogrenci id>';
$transkript->tcKimlikNo = '<tckno>';
$transkript->birimId = '<birim id>';
$transkript->donemler = [
    new Donem([
        'donemYili' => '2018-2019',
        'donemNumarasi' => '1',
        'dersler' => [
            new Ders(['dersinKodu' => 'MAT101', 'dersinAdi' => 'Matematik', 'not' => 'AA']),
        ],
    ]),
];

$client->ogrenciTranskript()->create($transkript);

$kayit = $client->ogrenciTranskript()->find('<ogrenci id>');
$kayit = $client->ogrenciTranskript()->findByTcKimlikNo('<tckno>', '<birim id>');
```

## Hata yönetimi

Tüm hatalar `Conkal\YOKSIS\REST\Exceptions` altındaki istisnalarla fırlatılır:

| İstisna | Durum |
|---|---|
| `AuthenticationException` | 401, 403: kullanıcı adı, şifre ya da servis yetkisi hatalı |
| `NotFoundException` | 404 |
| `ValidationException` | 400, 409, 422: gönderilen veri reddedildi |
| `RateLimitException` | 429 (`getRetryAfter()` bekleme süresini verir) |
| `RequestFailedException` | Diğer 4xx; yukarıdakilerin üst sınıfı |
| `ServerErrorException` | 5xx |
| `ConnectionException` | Bağlantı hatası, zaman aşımı |

Hepsi `YoksisException` arayüzünü uygular. HTTP hatalarında (`ApiException`) durum kodu, yanıt gövdesi ve
yanıttan ayrıştırılabilen hata mesajı alınabilir:

```php
use Conkal\YOKSIS\REST\Exceptions\AuthenticationException;
use Conkal\YOKSIS\REST\Exceptions\ValidationException;
use Conkal\YOKSIS\REST\Exceptions\YoksisException;

try {
    $client->pedagojikFormasyon()->create($pedagojikFormasyon);
} catch (ValidationException $e) {
    echo $e->getErrorMessage();  // servisin döndürdüğü hata mesajı (varsa)
    echo $e->getResponseBody();  // ham yanıt gövdesi
} catch (AuthenticationException $e) {
    // kimlik bilgilerini kontrol edin
} catch (YoksisException $e) {
    // diğer tüm YÖKSİS hataları
}
```

İstisnalar Guzzle'ın kendi sınıflarından türer (`ClientException`, `ServerException`, `ConnectException`),
bu yüzden 1.0'daki `catch (ClientException $e)` gibi kodlar değiştirilmeden çalışmaya devam eder.

## Yeniden deneme ve loglama

`YOK::create()` ile oluşturulan istemci, geçici hatalarda (bağlantı hatası, 429, 502, 503, 504)
isteği artan bekleme süreleriyle yeniden dener. `Retry-After` başlığına uyar. Varsayılan olarak yalnızca GET/HEAD
istekleri yeniden denenir; `create()` gibi POST istekleri mükerrer kayıt oluşturmamak için denenmez.

```php
$client = YOK::create(YOK::TEST_URI, $kullanici, $sifre, [
    'timeout' => 30,          // saniye
    'connect_timeout' => 10,  // saniye
    'retries' => 2,           // 0 yeniden denemeyi kapatır
    'retry_delay' => 500,     // ms; her denemede iki katına çıkar
    'logger' => $logger,      // herhangi bir PSR-3 logger (Monolog vb.)
]);
```

`logger` verildiğinde her deneme metot, yol, durum kodu, süre ve deneme numarasıyla loglanır.
T.C. kimlik numarası gibi kişisel verilerin log'a düşmemesi için sorgu parametreleri, istek/yanıt gövdeleri
ve `Authorization` başlığı loglanmaz.

## Laravel

Paket, Laravel'in paket keşfi (auto-discovery) ile otomatik olarak kaydolur. `.env` dosyasına bilgileri ekleyin:

```dotenv
YOKSIS_BASE_URI=https://servisler.yok.gov.tr/resttest/obs/
YOKSIS_USERNAME=...
YOKSIS_PASSWORD=...
# isteğe bağlı
YOKSIS_TIMEOUT=30
YOKSIS_RETRIES=2
YOKSIS_LOG_CHANNEL=daily
```

Ayarları özelleştirmek için config dosyasını yayınlayın:

```bash
php artisan vendor:publish --tag=yoksis-config
```

İstemciyi facade ya da bağımlılık enjeksiyonu ile kullanın:

```php
use Conkal\YOKSIS\Laravel\Facades\Yoksis;
use Conkal\YOKSIS\REST\YOK;

$turler = Yoksis::hazirlikTurleri()->all();

class YerlesenController
{
    public function index(YOK $yoksis)
    {
        return $yoksis->yerlestirmeVeri()->paginate(['tur' => 'YKS', 'yil' => '2019']);
    }
}
```

Laravel 6 ve sonraki sürümler desteklenir.

## Geliştirme

```bash
composer install
composer test               # birim testleri (ağ bağlantısı gerekmez)
composer test:integration   # YÖKSİS test ortamına gerçek istek gönderir
```

Entegrasyon testleri `YOKSIS_USERNAME` ve `YOKSIS_PASSWORD` ortam değişkenleri tanımlı değilse atlanır.
Ek olarak `YOKSIS_BASE_URI`, `TEST_TCKNO`, `TEST_BIRIMID` ve `TEST_UNIVERSITEID` değişkenleri kullanılır.

Katkıda bulunmak için [CONTRIBUTING.md](CONTRIBUTING.md) dosyasına bakın.

## Lisans

[MIT](LICENSE)
