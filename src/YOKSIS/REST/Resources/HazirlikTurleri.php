<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;

/**
 * Class HazirlikTurleri
 * @package Conkal\YOKSIS\REST\Resources
 * @method \Conkal\YOKSIS\REST\Entities\HazirlikTuru[] all
 */
class HazirlikTurleri extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'hazirlikturleri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\HazirlikTuru::class;
}