<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * ASAL askerlik durum sorgusu sonucu (askerlikDurumSorgula).
 */
class AskerlikDurum extends Entity
{
    public $tcKimlikNo;
    public $adi;
    public $soyadi;

    /**
     * @var mixed 101: Kaydında sakınca yoktur, 102: Askerlik şubesine müracaat etmesi gerekir
     */
    public $askerlikDurumKod;
    public $askerlikDurumAciklama;

    /**
     * @var mixed 1-5, bkz. Constants\Askerlik\ErtelemeDurumu
     */
    public $askerlikErtDurumKod;
    public $askerlikErtDurumAciklama;

    /**
     * @var mixed ör. EDV09.000
     */
    public $islemKod;
    public $islemAciklama;

    /**
     * @var mixed Mevcut ertelemenin bitiş tarihi (dd/MM/yyyy)
     */
    public $ertBitTarihi;
}
