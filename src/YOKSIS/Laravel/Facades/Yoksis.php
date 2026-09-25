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
