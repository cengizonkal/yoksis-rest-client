# YOKSIS REST API CLIENT

### Kurulum
```
composer require conkal/yoksis-rest-client 
```
### Kullanım
```php
require __DIR__ . '/vendor/autoload.php';
$pass = getenv('YOKSIS_PASSWORD');
$user = getenv('YOKSIS_USERNAME');
$auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
$client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
$client->setAuth($auth);
```
### Pedagojik Formasyon
```php

$pedagojikFormasyon = new \Conkal\YOKSIS\REST\Entities\PedagojikFormasyon();
$pedagojikFormasyon->tcKimlikNo = "<tckno>";
$pedagojikFormasyon->alanId = 1;
$pedagojikFormasyon->belgeNo = '<belge no>';
$pedagojikFormasyon->belgeTarihi = '<d/m/Y>';
$pedagojikFormasyon->universiteId = "<universite username>";
$pedagojikFormasyon->fakulteId = "<fakülte id>";

// kaydet
$client->pedagojikFormasyon()->create($pedagojikFormasyon);

```

### Yerleşen Verisi
```php
/** @var \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[] $yerlesenler */
$yerlesenler = $this->client->yerlestirmeVeri()->query(['tur' => 'YKS', 'yil' => '2019']);
```

### Hazırlık Detay
```php
$detay =  new \Conkal\YOKSIS\REST\Entities\HazirlikDetay();
$detay->tckno = '<tckno>';
$detay->hazirlikTuru = 2;
$detay->ogretimDili = 1;
$detay->hazirlikDonemNo = 1;
$detay->muafiyetDurumu = MuafiyetDurumu::MUAF_DEGIL;
$detay->birimId = '<birim id>';

// kaydet
$client->hazirlikDetay()->create($this->entity);
```

### Öğrenci Transkript
```php
$transkript = new \Conkal\YOKSIS\REST\Entities\OgrenciTranskript();
$transkript->ogrenciId = '123456';
$transkript->tcKimlikNo = getenv('TEST_TCKNO');
$transkript->birimId = '123456';
$transkript->donemler = [
    new \Conkal\YOKSIS\REST\Entities\Transkript\Donem([
        'donemYili' => '2018-2019',
        'donemNumarasi' => '1',
        'donemNotOrtalamasi' => '3.5',
        'genelNotOrtalamasi' => '3.5',
        'toplamKredi' => '120',
        'toplamAKTS' => '120',
        'donemSonuDurumu' => '1',
        'dersler' => [
            new \Conkal\YOKSIS\REST\Entities\Transkript\Ders([
                'dersKodu' => 'MAT101',
                'dersAdi' => 'Matematik',
                'kredi' => '5',
                'akts' => '5',
                'harfNotu' => 'AA',
                'notu' => '4.0',
                'donemIciDurumu' => '1',
                'donemSonuDurumu' => '1',
            ]),
            new \Conkal\YOKSIS\REST\Entities\Transkript\Ders([
                'dersKodu' => 'FIZ101',
                'dersAdi' => 'Fizik',
                'kredi' => '5',
                'akts' => '5',
                'harfNotu' => 'AA',
                'notu' => '4.0',
                'donemIciDurumu' => '1',
                'donemSonuDurumu' => '1',
            ]),
        ],
        'donemAltiTip1' => [
            new \Conkal\YOKSIS\REST\Entities\Transkript\TransferUniversite([
                'universiteAdi' => 'Harvard',
                'fakulteAdi' => 'Fizik',
                'bolumAdi' => 'Fizik',
                'donemYili' => '2018-2019',
                'donemNumarasi' => '1',
                'kredi' => '5',
                'akts' => '5',
                'harfNotu' => 'AA',
                'notu' => '4.0',
            ]),
        ],
    ])
];

$this->client->ogrenciTranskript()->create($transkript);
```
