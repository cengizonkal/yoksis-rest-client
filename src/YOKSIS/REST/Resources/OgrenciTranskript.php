<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;



class OgrenciTranskript extends ResourceAbstract
{

    use CreateTrait;
    use FindTrait;
    use DeleteTrait;
    
    protected $endPoint = 'ogrencitranskript';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript::class;
}