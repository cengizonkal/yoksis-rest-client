<?php


namespace Conkal\YOKSIS\REST\Entities;


class AskerlikErtelemeTalep extends Entity
{

    /**
     * @var string Ogrenci TC Kimlik No
     * (Zorunlu alan)
     */
    public $tcKimlikNo;

    /**
     * @var int Ogrenci Birim Id
     * (Zorunlu alan)
     */
    public $birimId;

    /**
     * @var string Erteleme için E, İptal için I gönderilmeli
     * (Zorunlu alan)
     */
    public $teklifTuru;

    /**
     * @var string (Zorunlu alan)
     * Erteleme için rest/obs/askerlikErtelemeReferans/ERTELEME_TEKLIF_NEDENLERI,
     * İptal İçin /rest/obs/askerlikErtelemeReferans/ERTELEME_IPTAL_NEDENLERI servislerinden dönen değerlerden biri
     */
    public $teklifNedeniNo;

    /**
     * @var mixed (Zorunlu değil)
     * /rest/obs/askerlikErtelemeReferans/AZAMI_SURE_ASMA_NEDENLERI servisinden dönen değerlerden biri
     */
    public $azamiSureArtUnsur;

    /**
     * @var mixed Azami süre aşıldığında ne kadar erteleme yapılacağı. Yıl olarak girilecek.
     * (Zorunlu değil)
     */
    public $ilaveSure;

    /**
     * @var mixed  Yoksis öğrenci durumu Kayıt yenilemiş öğrenciler için en son kayıt yaptığı tarih.
     * Tarih(dd/MM/YYYY) olarak girilecek.
     * (Zorunlu değil)
     */
    public $sonKayitYenilemeTarihi;

    /**
     * @var string Yoksis öğrenci durumu Kayıt yenilemiş öğrenciler için en son kayıt yaptığı dönem.
     * Dönem(örnek:2018-2019 Güz) olarak girilecek.
     * (Zorunlu değil)
     */
    public $sonKayitYenilemeDonemi;

    /**
     * @var string  Öğrenci işleri yetkilisi TC Kimlik No
     * (Zorunlu alan)
     */
    public $imzalayanTcNo;

    /**
     * @var string  Öğrenci işleri yetkilisi TC Kimlik No
     * (Zorunlu alan)
     */
    public $imzalayanAdSoya;
}