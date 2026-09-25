<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Üniversitenin son başarılı teyitleşme bilgisi (sonbasariliteyitlesme).
 */
class Teyitlesme extends Entity
{
    public $universiteID;

    /**
     * @var mixed dd.MM.yyyy HH:mm:ss
     */
    public $sonBasariliTeyitlesmeTarihi;
}
