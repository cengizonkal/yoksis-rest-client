# Katkıda Bulunma

Katkılarınız için teşekkürler! Hata bildirimleri, yeni servis desteği ve dokümantasyon iyileştirmeleri memnuniyetle karşılanır.

## Başlarken

```bash
git clone https://github.com/cengizonkal/yoksis-rest-client.git
cd yoksis-rest-client
composer install
composer test
```

## Yeni bir servis eklemek

1. `src/YOKSIS/REST/Entities` altına servisin alanlarını içeren, `Entity` sınıfından türeyen bir sınıf ekleyin.
2. `src/YOKSIS/REST/Resources` altına `ResourceAbstract` sınıfından türeyen bir kaynak sınıfı ekleyin.
   `$endPoint` ve `$entity` alanlarını tanımlayıp gereken trait'leri (`AllTrait`, `FindTrait`, `QueryTrait`,
   `CreateTrait`, `DeleteTrait`) kullanın.
3. `YOK` sınıfına kaynağı döndüren bir metot ekleyin.
4. `tests/Unit` altına sahte (mock) HTTP yanıtlarıyla çalışan testler ekleyin.
5. `readme.md` dosyasındaki servis tablosunu ve `CHANGELOG.md` dosyasını güncelleyin.

## Pull request kuralları

- `composer test` komutu başarılı olmalı.
- Kodun PHP 7.2 ile uyumlu kalmasına dikkat edin (CI, PHP 7.2 – 8.4 arasında çalışır).
- Testlerde, commit'lerde ya da issue'larda **gerçek T.C. kimlik numarası, kullanıcı adı veya şifre paylaşmayın.**
