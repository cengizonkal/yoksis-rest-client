<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Class YurtDisindanYatayGecis
 * @package Conkal\YOKSIS\REST\Resources
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis[] all
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis[] query(array $query)
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis find($id)
 */
class YurtDisindanYatayGecis extends ResourceAbstract
{
    use FindTrait, AllTrait, CreateTrait, DeleteTrait, QueryTrait;

    protected $endPoint = 'yurtDisindanYatayGecis';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis::class;
}