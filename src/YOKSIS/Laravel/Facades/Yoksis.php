<?php

namespace Conkal\YOKSIS\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Conkal\YOKSIS\REST\Resources\PedagojikFormasyon pedagojikFormasyon()
 * @method static \Conkal\YOKSIS\REST\Resources\PedagojikFormasyonAlanlari pedagojikFormasyonAlanlari()
 * @method static \Conkal\YOKSIS\REST\Resources\HazirlikTurleri hazirlikTurleri()
 * @method static \Conkal\YOKSIS\REST\Resources\HazirlikDetay hazirlikDetay()
 * @method static \Conkal\YOKSIS\REST\Resources\YerlestirmeVeri yerlestirmeVeri()
 * @method static \Conkal\YOKSIS\REST\Resources\FotografIndir fotografIndir()
 * @method static \Conkal\YOKSIS\REST\Resources\OgrenciIzinler ogrenciIzinler()
 * @method static \Conkal\YOKSIS\REST\Resources\YurtDisindanYatayGecis yurtDisindanYatayGecis()
 * @method static \Conkal\YOKSIS\REST\Resources\AskerlikErtelemeTalep askerlikErtelemeTalep()
 * @method static \Conkal\YOKSIS\REST\Resources\AskerlikErtelemeReferans askerlikErtelemeReferans()
 * @method static \Conkal\YOKSIS\REST\Resources\KykOgrenciSorgula kykOgrenciSorgula()
 * @method static \Conkal\YOKSIS\REST\Resources\OgrenciTranskript ogrenciTranskript()
 * @method static \Conkal\YOKSIS\REST\Resources\OgrenciCezalar ogrenciCezalar()
 * @method static \Conkal\YOKSIS\REST\Resources\CezaTurleri cezaTurleri()
 * @method static \Conkal\YOKSIS\REST\Resources\Mezunlar mezunlar()
 * @method static \Conkal\YOKSIS\REST\Resources\Duyurular duyurular()
 * @method static \Conkal\YOKSIS\REST\Resources\Universiteler universiteler()
 * @method static \Conkal\YOKSIS\REST\Resources\Teyitlesme teyitlesme()
 * @method static \Conkal\YOKSIS\REST\Resources\YatayGecisler yatayGecisler()
 * @method static \Conkal\YOKSIS\REST\Resources\AskerlikDurumSorgula askerlikDurum()
 * @method static \Conkal\YOKSIS\REST\Resources\VakifOgrenimUcreti vakifOgrenimUcreti()
 * @method static \Conkal\YOKSIS\REST\Resources\MebMezunDetay mebMezunDetay()
 * @method static \Conkal\YOKSIS\REST\Resources\SaglikBakanligiTescil saglikBakanligiTescil()
 * @method static \Conkal\YOKSIS\REST\Resources\OgrenciIletisimBilgileri ogrenciIletisimBilgileri()
 * @method static \Psr\Http\Message\ResponseInterface send(string $endPoint, array $options = [])
 *
 * @see \Conkal\YOKSIS\REST\YOK
 */
class Yoksis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'yoksis';
    }
}
