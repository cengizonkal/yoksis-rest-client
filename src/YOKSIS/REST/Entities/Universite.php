<?php

namespace Conkal\YOKSIS\REST\Entities;

/**
 * Üniversite bilgisi (universiteler).
 */
class Universite extends Entity
{
    public $birimID;
    public $birimAdi;
    public $birimAdiIngilizce;
    public $acikAdres;
    public $telefon;
    public $faks;
    public $email;
    public $web;
    public $ilKodu;
    public $ilceKodu;
    public $buyuksehir;
    public $denizKiyisi;

    /**
     * @var mixed {kod, ad, aciklama}
     */
    public $aktif;

    /**
     * @var mixed {kod, ad, aciklama}
     */
    public $universiteTuru;
}
