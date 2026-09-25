<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Ceza alan öğrenci kaydı (ogrencicezalar).
 */
class OgrenciCeza extends Entity
{
    public $id;
    public $tcKimlikNo;
    public $birimID;

    /**
     * @var mixed cezaturleri servisinden dönen kod
     */
    public $cezaID;
    public $yonetmelikMaddeFikra;

    /**
     * @var mixed 1: Evet, 2: Hayır (bkz. Constants\Ceza\CezaMahkemeIptal)
     */
    public $cezaMahkemeIptalMi;

    /**
     * @var mixed dd/MM/yyyy
     */
    public $cezaTarihi;

    /**
     * @var mixed dd/MM/yyyy
     */
    public $cezaBaslangicTarihi;

    /**
     * @var mixed dd/MM/yyyy
     */
    public $cezaBitisTarihi;

    /**
     * @var mixed Servis tarafından doldurulur
     */
    public $guncelleyenTck;

    /**
     * @var mixed Servis tarafından doldurulur
     */
    public $guncellemeTarihi;
}
