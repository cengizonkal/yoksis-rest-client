<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;

/**
 * Ceza türleri (cezaTurleriniListele).
 *
 * @method \Conkal\YOKSIS\REST\Entities\CezaTuru[] all()
 */
class CezaTurleri extends ResourceAbstract
{
    use AllTrait;

    protected $endPoint = 'cezaturleri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\CezaTuru::class;
}
