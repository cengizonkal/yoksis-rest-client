# Değişiklik Günlüğü

## [1.1.0] - 2026-09-25

### Eklenenler
- YÖK "REST Servisler Yardım Dökümanı" (sürüm 2.0) kapsamındaki eksik servisler:
  - Ceza alan öğrenciler (`ogrenciCezalar()`) ve ceza türleri (`cezaTurleri()`).
  - Mezunlar (`mezunlar()`, sayfalı), duyurular (`duyurular()`), üniversiteler (`universiteler()`),
    son başarılı teyitleşme (`teyitlesme()->sonBasarili()`), yatay geçiş ile gidenler (`yatayGecisler()`).
  - ASAL: askerlik durum sorgulama (`askerlikDurum()->sorgula()`), talep sonucu sorgulama
    (`askerlikErtelemeTalep()->sonuc()`, HTTP 206 "beklemede" desteğiyle) ve talep silme.
  - Toplu fotoğraf indirme (`fotografIndir()->toplu()` / `topluKaydet()`), vakıf öğrenim ücreti bildirimi
    (`vakifOgrenimUcreti()->bildir()`).
  - Transkripti T.C. kimlik no ve birim id ile getirme (`ogrenciTranskript()->findByTcKimlikNo()`).
  - MEB mezuniyet detayı (`mebMezunDetay()`), Sağlık Bakanlığı tescil bilgisi (`saglikBakanligiTescil()`).
  - KYK: tekli (`sorgula()`), toplu (`topluSorgula()`, 100'lük parçalara bölünür) ve öğrenci sayısı sorguları.
  - Öğrenci iletişim bilgileri (`ogrenciIletisimBilgileri()`).
  - `update()` (PUT): ceza, izin, hazırlık detay, pedagojik formasyon ve yurt dışından yatay geçiş kayıtları için.
  - `ogrenciIzinler()->find()`.
- Dokümandaki kod listeleri için sabitler: `DonemTuru`, `IzinTuru`, `CezaMahkemeIptal`, `HazirlikTuru`,
  `Askerlik\{ReferansTuru, TeklifTuru, TalepSonucu, DurumKodu, ErtelemeDurumu, IslemSonucKodu}`, `Kyk\KrediTuru`.
- Kütüphaneye özel istisnalar (`Conkal\YOKSIS\REST\Exceptions`): `AuthenticationException`, `NotFoundException`,
  `ValidationException`, `RateLimitException`, `RequestFailedException`, `ServerErrorException`, `ConnectionException`.
  Hepsi `YoksisException` arayüzünü uygular. HTTP hatalarında durum kodu, yanıt gövdesi ve servisin hata mesajı
  alınabilir. Guzzle'ın istisnalarından türedikleri için mevcut `catch` blokları bozulmaz.
- Sayfalı yanıtlar için `Page` sınıfı; `YerlestirmeVeri` kaynağına `paginate()` (toplam kayıt/sayfa bilgisiyle) ve
  tüm sayfaları sırayla çeken `cursor()` metotları.
- `YOK::create()` ve `ClientFactory`: zaman aşımı, geçici hatalarda üstel bekleme ile yeniden deneme
  (yalnızca GET/HEAD) ve kişisel veri içermeyen PSR-3 loglama.
- Laravel entegrasyonu: service provider (paket keşfiyle otomatik kayıt), `Yoksis` facade'ı ve
  yayınlanabilir `config/yoksis.php`.

### Değişenler
- `psr/log` çalışma zamanı bağımlılığı olarak eklendi.
- `YerlestirmeVeri::query()`, servis sayfalamadan düz liste döndürürse kayıtları artık boş dizi yerine döndürüyor.

### Düzeltilenler
- `yurtDisindanYatayGecis()`: servisin `{"returnCode", "data", "count"}` zarfı açılmadığı için `all()`, `query()` ve
  `find()` kayıtlar yerine zarfın kendisini döndürüyordu.
- Sayfalı yanıtlarda servisin kullandığı `firstPage`/`lastPage` alanları artık okunuyor.
- Laravel testleri PHP 7.2'de uyumsuz `vlucas/phpdotenv` sürümü yüzünden başarısız oluyordu; geliştirme bağımlılığı
  `laravel/framework` olarak değiştirildi.

## [1.0.0] - 2026-09-25

Bu sürüm geriye uyumsuz değişiklikler içerir (bkz. *Değişenler*). `^0.2` kısıtını kullanan projeler
otomatik olarak yükseltilmez; yükseltmek için `composer require conkal/yoksis-rest-client:^1.0` çalıştırın.

### Eklenenler
- `YOK::TEST_URI` ve `YOK::PRODUCTION_URI` sabitleri.
- `YOK` yapıcısına isteğe bağlı Guzzle istemcisi ve kimlik doğrulama parametreleri.
- `YOK::getBaseUri()`; `setAuth()` ve `setBaseUri()` artık zincirlenebilir.
- `FotografIndir::save()` ile fotoğrafı doğrudan dosyaya kaydetme.
- `Entity` sınıfı `JsonSerializable` arayüzünü uyguluyor; `toArray()` iç içe entity'leri de diziye çeviriyor.
- Ağ bağlantısı gerektirmeyen birim testleri ve PHP 7.2 – 8.4 CI matrisi.
- `LICENSE`, `CONTRIBUTING.md` ve `.gitattributes` dosyaları.

### Değişenler
- Minimum PHP sürümü 7.2.5 oldu; Guzzle 7 desteği eklendi.
- `fakerphp/faker` gereksiz çalışma zamanı bağımlılığı kaldırıldı.
- `find()` hiç kayıt bulunamadığında `null` döndürüyor.
- `create()` JSON olmayan yanıtlarda ham yanıt metnini döndürüyor.
- `delete()` servisin yanıtını döndürüyor.
- Base URI sonundaki `/` artık otomatik ekleniyor.
- Entegrasyon testleri kimlik bilgileri tanımlı değilse atlanıyor.

### Düzeltilenler
- `find()` servis tek nesne ya da boş yanıt döndürdüğünde PHP 8'de oluşan `TypeError`.
- `setAuth()` çağrılmadan istek gönderildiğinde oluşan ölümcül hata.
- `YerlestirmeVeri::query()` yanıtında `content` alanı yoksa oluşan hata.
- PHP 8.2+ sürümlerinde dinamik özellik (dynamic property) uyarıları.
- Transkript alt sınıflarının (`Tez`, `NotBaremi`, `Karar`, ...) diziden doldurulamaması.
- `KykOgrenciSorgula` kaynağında yanlış erişim belirleyicileri.

[1.1.0]: https://github.com/cengizonkal/yoksis-rest-client/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/cengizonkal/yoksis-rest-client/compare/v0.2.1...v1.0.0
