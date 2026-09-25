<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep[] query(array $query)
 */
class AskerlikErtelemeTalep extends ResourceAbstract
{
    use CreateTrait, QueryTrait;

    protected $endPoint = 'askerlikErtelemeTalep';
    protected $entity = \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep::class;
}