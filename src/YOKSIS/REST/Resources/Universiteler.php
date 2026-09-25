<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;

/**
 * Üniversitelerin iletişim ve birim bilgileri (universiteListele).
 *
 * @method \Conkal\YOKSIS\REST\Entities\Universite[] all()
 */
class Universiteler extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'universiteler';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Universite::class;
}
