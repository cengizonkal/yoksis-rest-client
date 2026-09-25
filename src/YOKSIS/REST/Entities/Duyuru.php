<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * YÖKSİS duyurusu (duyurular).
 */
class Duyuru extends Entity
{
    public $duyuruId;

    /**
     * @var mixed Duyuru metni
     */
    public $duyuru;
    public $duyuruBirim;
    public $eklemeTarihi;
    public $link;
}
