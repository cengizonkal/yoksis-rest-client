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
