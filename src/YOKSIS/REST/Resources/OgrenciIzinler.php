<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Entities\OgrenciIzin;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Class OgrenciIzinler
 * @package Conkal\YOKSIS\REST\Resources
 * @method OgrenciIzin[] query(array $query)
 */
class OgrenciIzinler extends ResourceAbstract
{
    use CreateTrait;
    use QueryTrait;
    use DeleteTrait;

    protected $endPoint = 'ogrenciizinler';
    protected $entity = \Conkal\YOKSIS\REST\Entities\OgrenciIzin::class;
}