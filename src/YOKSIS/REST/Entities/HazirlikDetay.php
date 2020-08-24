<?php


namespace Conkal\YOKSIS\REST\Entities;


class HazirlikDetay extends Entity
{

    public $id;
    public $basarmaDurumu="";
    public $birimId;
    public $bitirmeTarihi="";
    public $hazirlikDonemNo;
    public $hazirlikTuru;
    public $muafiyetDurumu;
    public $muafiyetNedeni="";
    public $muafiyetTarihi="";
    public $ogretimDili;
    public $tckno;
    public $hazirlikNotu="";
}