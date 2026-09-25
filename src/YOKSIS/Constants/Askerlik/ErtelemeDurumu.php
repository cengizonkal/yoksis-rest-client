<?php

namespace Conkal\YOKSIS\Constants\Askerlik;

/**
 * AskerlikDurum::$askerlikErtDurumKod değerleri.
 */
class ErtelemeDurumu
{
    /** Erteleme ya da uzatma gönderilebilir */
    const ERTELEME_YA_DA_UZATMA_GONDERILEBILIR = 1;

    /** Askerlik durumu uygun olmadığından gönderilemez */
    const GONDERILEMEZ = 2;

    /** Sadece uzatma gönderilebilir */
    const SADECE_UZATMA_GONDERILEBILIR = 3;

    /** Başvuru öğrenci kaydı için ise askerlik şubesine müracaat gerekir; kaydı yapılmış öğrenci için erteleme ya da uzatma gönderilebilir */
    const YENI_KAYIT_ICIN_SUBEYE_MURACAAT = 4;

    /** Halen öğrenci olarak ertelemeli olduğundan erteleme durumuna göre gönderilmelidir */
    const ERTELEME_DURUMUNA_GORE = 5;
}
