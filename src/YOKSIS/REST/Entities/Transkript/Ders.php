<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;

class Ders
{
    public $dersinKodu;
    /** @var string */
    public $dersinAdi;
    /** @var string */
    public $ortalamayaDahilmi;
    /** @var string */
    public $dersinStatusu;
    /** @var string */
    public $ogretimDili;
    /** @var string */
    public $dersAlinmaTuru;
    /** @var string */
    public $teorikSaati;
    /** @var string */
    public $uygulamaSaati;
    /** @var string */
    public $yerel;
    /** @var string */
    public $akts;
    /** @var string */
    public $not;
    /** @var string */
    public $hamBasariNotu;
    /** @var string */
    public $puan;
    /** @var string */
    public $aciklama;
    /** @var string */
    public $dersinAmaci;
    /** @var string */
    public $dersinIcerigi;
    /** @var string */
    public $dersDonemi;
    /** @var string */
    public $dersGecmeDurumu;
    /** @var DersDil[] */
    public $digerDilDers = [];
}
