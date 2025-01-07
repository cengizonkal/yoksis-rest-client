<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;

use Conkal\YOKSIS\REST\Entities\Entity;

class OgrenciTranskript extends Entity
{
    public $ogrenciId = null;
    public $tcKimlikNo = null;
    public $birimId = null;
    public $krediTuru = null;
    public $basarilanKredi = null;
    
    /** @var Donem[] */
    public $donemler = [];
    
    /** @var Tez */
    public $tez = null;

    /** @var Aciklama[] */
    public $aciklamalar = [];


    /** @var NotBaremi[] */
    public $notBaremleri = [];
}