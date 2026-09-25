<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Askerlik erteleme/iptal talebinin sonucu (askerlikErtelemeTalep/{talepKayitUid}).
 */
class AskerlikErtelemeTalepSonucu extends Entity
{
    /**
     * @var mixed Talep oluşturulurken dönen kimlik
     */
    public $talepKayitUid;

    /**
     * @var mixed {aciklama, islemSonucKodu}
     */
    public $islemSonucu;
    public $tcKimlikNo;

    /**
     * @var mixed E: Erteleme, I: İptal, R: Ret (bkz. Constants\Askerlik\TalepSonucu)
     */
    public $sonuc;
    public $ertBasTarihi;
    public $ertBitTarihi;

    /**
     * @var mixed askerlikErtelemeReferans/RED_NEDENLERI kodlarından biri
     */
    public $redNedeni;
    public $sevkeTabiOlduguCelpDonemi;

    /**
     * Talep henüz sonuçlanmadı mı (EDV09.007)?
     *
     * @return bool
     */
    public function beklemedeMi()
    {
        return $this->islemSonucKodu() === \Conkal\YOKSIS\Constants\Askerlik\IslemSonucKodu::BEKLEMEDE;
    }

    /**
     * @return string|null islemSonucu içindeki sonuç kodu (ör. EDV09.000)
     */
    public function islemSonucKodu()
    {
        return isset($this->islemSonucu->islemSonucKodu) ? $this->islemSonucu->islemSonucKodu : null;
    }
}
