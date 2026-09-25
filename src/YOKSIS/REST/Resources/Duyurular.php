<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;

/**
 * YÖKSİS duyuruları (duyuruListele).
 *
 * @method \Conkal\YOKSIS\REST\Entities\Duyuru[] all()
 */
class Duyurular extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'duyurular';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Duyuru::class;
}
