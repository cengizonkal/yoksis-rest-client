<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Sağlık Bakanlığı tescil bilgisi (saglikbakanligitescilsorgula).
 */
class SaglikBakanligiTescil extends Entity
{
    public $tckn;
    public $tahsilTuruId;
    public $tahsilTuru;
    public $tescilNo;

    /**
     * @var mixed dd/mm/yyyy
     */
    public $tescilTarihi;
    public $baslamaTarihi;
    public $mezuniyetTarihi;
    public $bransKodu;
    public $yandalKodu;
    public $birimId;
    public $tescilDogrulamaKodu;
    public $tescilDogrulamaLink;
    public $ogrenciId;
}
