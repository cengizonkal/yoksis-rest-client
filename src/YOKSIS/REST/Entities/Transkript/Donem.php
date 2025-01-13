<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;
use Conkal\YOKSIS\REST\Entities\Entity;


class Donem extends Entity
{

    public $donemYili = null;
    public $donemNumarasi = null;
    public $donemNotOrtalamasi = null;
    public $genelNotOrtalamasi = null;
    public $toplamKredi = null;
    public $toplamAKTS = null;
    public $donemSonuDurumu = null;
    /** @var Ders[] */
    public $dersler = null;
    /** @var TransferUniversite[] */
    public $donemAltiTip1 = [];
    /** @var Karar[] */
    public $donemAltiTip2 = [];
    

}