<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\OgrenciIzin;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;
use Conkal\YOKSIS\REST\Resources\Traits\UpdateTrait;

/**
 * Class OgrenciIzinler
 * @package Conkal\YOKSIS\REST\Resources
 * @method OgrenciIzin[] query(array $query)
 * @method OgrenciIzin|OgrenciIzin[]|null find($id)
 */
class OgrenciIzinler extends ResourceAbstract
{
    use CreateTrait;
    use QueryTrait;
    use DeleteTrait;
    use FindTrait;
    use UpdateTrait;

    protected $endPoint = 'ogrenciizinler';
    protected $entity = \Conkal\YOKSIS\REST\Entities\OgrenciIzin::class;
}