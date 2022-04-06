<?php

namespace Conkal\YOKSIS\REST\Entities;

class KykOgrenciSorgula extends Entity
{

    public $tcKimlikNo;
    public $ad;
    public $soyad;
    public $yurtKodu;
    public $yurtAdi;
    /**
     * @var Tanim
     */
    public $krediBursDurumu;
    public $krediBursBirimId;
    /**
     * @var Tanim
     */
    public $yurtBarinmaDurumu;


}