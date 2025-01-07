<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;


class OgrenciTranskript extends ResourceAbstract
{
    
    protected $endPoint = 'ogrencitranskript';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YurtDisindanYatayGecis::class;
}