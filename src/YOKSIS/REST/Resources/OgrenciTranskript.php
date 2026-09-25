<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript|\Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript[]|null find($id)
 */
class OgrenciTranskript extends ResourceAbstract
{
    use CreateTrait, FindTrait, DeleteTrait;

    protected $endPoint = 'ogrencitranskript';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript::class;
}
