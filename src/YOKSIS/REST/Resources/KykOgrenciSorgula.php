<?php

namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula[] query(array $query)
 */
class KykOgrenciSorgula extends ResourceAbstract
{
    public $endPoint= 'kykogrencisorgula';
    public $entity= \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula::class;
    use QueryTrait;
}