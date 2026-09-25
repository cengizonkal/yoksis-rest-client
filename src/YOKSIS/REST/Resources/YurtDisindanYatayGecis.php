<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;
use Conkal\YOKSIS\REST\Resources\Traits\UpdateTrait;

/**
 * Class YurtDisindanYatayGecis
 * @package Conkal\YOKSIS\REST\Resources
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis[] all
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis[] query(array $query)
 * @method \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis|\Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis[]|null find($id)
 *
 * Servis yanıtları {"returnCode", "data", "count"} zarfıyla döner; kütüphane "data" alanını açar.
 * create(), update() ve delete() servisin döndürdüğü {"id": ...} nesnesini döndürür.
 */
class YurtDisindanYatayGecis extends ResourceAbstract
{
    use FindTrait, AllTrait, CreateTrait, UpdateTrait, DeleteTrait, QueryTrait;

    protected $endPoint = 'yurtDisindanYatayGecis';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis::class;
    protected $wrapsResponses = true;
    protected $updateUsesIdInPath = false;
}