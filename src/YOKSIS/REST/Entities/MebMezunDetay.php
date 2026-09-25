<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * MEB ortaöğretim mezuniyet detayı (mebmezundetaysorgula).
 */
class MebMezunDetay extends Entity
{
    public $tcKimlikNo;
    public $adi;
    public $soyadi;
    public $okulKodu;
    public $okulAdi;
    public $okulTurKodu;
    public $okulTurAdi;
    public $okulAlanKodu;
    public $okulAlanAdi;
    public $okulDalKodu;
    public $okulDalAdi;
    public $okulBirincisi;
    public $okulIlAdi;
    public $okulIlKodu;
    public $okulIlceAdi;
    public $okulIlceKodu;
    public $ogrenimTuru;
    public $mezunDurumuKodu;
    public $mezunDurumu;

    /**
     * @var mixed dd/MM/yyyy
     */
    public $mezuniyetTarih;
    public $notSistemi;
    public $diplomaNotuPuani;
}
