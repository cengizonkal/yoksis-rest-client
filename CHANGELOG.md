# Değişiklik Günlüğü

## [0.3.0] - 2026-09-25

Bu sürüm geriye uyumsuz değişiklikler içerir (bkz. *Değişenler*). `^0.2` kısıtını kullanan projeler
otomatik olarak yükseltilmez; yükseltmek için `composer require conkal/yoksis-rest-client:^0.3` çalıştırın.

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

[0.3.0]: https://github.com/cengizonkal/yoksis-rest-client/compare/v0.2.1...v0.3.0
