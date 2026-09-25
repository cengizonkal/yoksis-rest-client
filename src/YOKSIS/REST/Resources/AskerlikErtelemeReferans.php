<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeReferans|\Conkal\YOKSIS\REST\Entities\AskerlikErtelemeReferans[]|null find($id)
 */
class AskerlikErtelemeReferans extends ResourceAbstract
{

    use FindTrait;

    protected $endPoint = 'askerlikErtelemeReferans';
    protected $entity = \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeReferans::class;
}