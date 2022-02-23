<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep[] all
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep[] query(array $query)
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep find($id)
 */
class AskerlikErtelemeTalep extends ResourceAbstract
{
    use CreateTrait, QueryTrait;

    protected $endPoint = 'askerlikErtelemeTalep';
    protected $entity = \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep::class;
}