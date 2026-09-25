<?php

namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula[] query(array $query)
 */
class KykOgrenciSorgula extends ResourceAbstract
{
    use QueryTrait;

    protected $endPoint = 'kykogrencisorgula';
    protected $entity = \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula::class;
}
