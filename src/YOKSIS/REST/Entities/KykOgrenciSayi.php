<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * KYK kredi/burs alan öğrenci sayıları (kykogrencisayisorgula).
 */
class KykOgrenciSayi extends Entity
{
    /**
     * @var mixed 1: Öğrenim Kredisi, 3: Burs
     */
    public $krediTur;
    public $krediTurAciklama;
    public $erkekOgrenciSayi;
    public $kizOgrenciSayi;
}
