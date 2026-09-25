<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Yatay geçiş ile giden öğrenci (yatayGecisListele).
 */
class YatayGecis extends Entity
{
    public $tcKimlikNo;
    public $gecisYaptigiBirimID;

    /**
     * @var mixed dd/MM/yyyy
     */
    public $gecisKayitTarihi;
}
