<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Class PedagojikFormasyon
 * @package Conkal\YOKSIS\REST\Resources
 * @method \Conkal\YOKSIS\REST\Entities\HazirlikTuru[] all
 */
class HazirlikTurleri extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'hazirlikturleri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\HazirlikTuru::class;
}