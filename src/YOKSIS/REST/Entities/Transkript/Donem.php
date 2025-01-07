<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;


class Donem
{

    public $donemYili = null;
    public $donemNumarasi = null;
    public $donemNotOrtalamasi = null;
    public $genelNotOrtalamasi = null;
    public $toplamKredi = null;
    public $toplamAKTS = null;
    public $donemSonuDurumu = null;
    /** @var Ders[] */
    public $dersler = [];
    /** @var TransferUniversite[] */
    public $donemAltiTip1 = [];
    /** @var Karar[] */
    public $donemAltiTip2 = [];

}