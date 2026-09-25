<?php

namespace Conkal\YOKSIS\Constants\Askerlik;

/**
 * ASAL işlem sonuç kodları.
 */
class IslemSonucKodu
{
    /** İşlem başarılı */
    const BASARILI = 'EDV09.000';

    /** Gönderilen talep kayıt id hatalı */
    const GECERSIZ_VERI = 'EDV09.006';

    /** Talep henüz sonuçlanmadı (HTTP 206) */
    const BEKLEMEDE = 'EDV09.007';

    /** Talep askerlik şubesine gönderildiği için silinemedi */
    const SILME_BASARISIZ = 'EDV09.010';
}
