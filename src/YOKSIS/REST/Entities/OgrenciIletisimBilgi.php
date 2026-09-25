<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Öğrenci iletişim (e-posta) bilgisi (ogrenciIletisimBilgi).
 */
class OgrenciIletisimBilgi extends Entity
{
    public $id;
    public $tcKimlikNo;
    public $birimId;
    public $ePosta;
}
