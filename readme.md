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
use Conkal\YOKSIS\REST\Utilities\BasicAuth;

require __DIR__ . '/vendor/autoload.php';

$client = new YOK(YOK::TEST_URI); // canlı ortam için YOK::PRODUCTION_URI
$client->setAuth(new BasicAuth(getenv('YOKSIS_USERNAME'), getenv('YOKSIS_PASSWORD')));
```

Zaman aşımı, proxy gibi ayarlar için kendi Guzzle istemcinizi verebilirsiniz:

```php
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

| Erişim | Servis | Metotlar |
|---|---|---|
| `$client->pedagojikFormasyon()` | `pedagojikFormasyon` | all, query, find, create, delete |
| `$client->pedagojikFormasyonAlanlari()` | `pedagojikformasyonalanlari` | all |
| `$client->hazirlikTurleri()` | `hazirlikturleri` | all |
| `$client->hazirlikDetay()` | `hazirlikdetay` | all, query, find, create, delete |
| `$client->yerlestirmeVeri()` | `yerlestirmeveri` | query |
| `$client->fotografIndir()` | `fotografindir` | find, save |
| `$client->ogrenciIzinler()` | `ogrenciizinler` | query, create, delete |
| `$client->yurtDisindanYatayGecis()` | `yurtDisindanYatayGecis` | all, query, find, create, delete |
| `$client->askerlikErtelemeTalep()` | `askerlikErtelemeTalep` | query, create |
| `$client->askerlikErtelemeReferans()` | `askerlikErtelemeReferans` | find |
| `$client->kykOgrenciSorgula()` | `kykogrencisorgula` | query |
| `$client->ogrenciTranskript()` | `ogrencitranskript` | find, create, delete |

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

```php
/** @var \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[] $yerlesenler */
$yerlesenler = $client->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']);
```

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

$client->askerlikErtelemeTalep()->create($talep);
```

### KYK Öğrenci Sorgulama

```php
$sonuc = $client->kykOgrenciSorgula()->query(['tcKimlikNo' => '<tckno>']);
```

### Fotoğraf İndirme

```php
// dosyaya kaydet
$client->fotografIndir()->save('<tckno>', '/tmp/<tckno>.jpg');

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
```

## Hata yönetimi

HTTP hataları (4xx/5xx) Guzzle istisnaları olarak fırlatılır:

```php
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

try {
    $client->pedagojikFormasyon()->create($pedagojikFormasyon);
} catch (ClientException $e) {
    // 4xx: doğrulama hatası, yetki hatası vb.
    echo (string) $e->getResponse()->getBody();
} catch (GuzzleException $e) {
    // bağlantı hatası, 5xx vb.
}
```

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
