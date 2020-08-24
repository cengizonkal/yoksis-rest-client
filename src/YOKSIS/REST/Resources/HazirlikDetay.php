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
 * @method \Conkal\YOKSIS\REST\Entities\HazirlikDetay[] all
 * @method \Conkal\YOKSIS\REST\Entities\HazirlikDetay[] query(array $query)
 * @method \Conkal\YOKSIS\REST\Entities\HazirlikDetay find($id)
 */
class HazirlikDetay extends ResourceAbstract
{
    use FindTrait, AllTrait, CreateTrait, DeleteTrait, QueryTrait;

    protected $endPoint = 'hazirlikdetay';
    protected $entity = \Conkal\YOKSIS\REST\Entities\HazirlikDetay::class;
}