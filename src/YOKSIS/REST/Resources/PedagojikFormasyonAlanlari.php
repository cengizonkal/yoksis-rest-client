<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Entities\PedagojikFormasyonAlani;
use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;

/**
 * Class PedagojikFormasyonAlanlari
 * @package Conkal\YOKSIS\REST\Resources
 * @method PedagojikFormasyonAlani[] all
 */
class PedagojikFormasyonAlanlari extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'pedagojikformasyonalanlari';
    protected $entity = \Conkal\YOKSIS\REST\Entities\PedagojikFormasyonAlani::class;
}