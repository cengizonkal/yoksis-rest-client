<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

use Conkal\YOKSIS\REST\Entities\Entity;

trait AllTrait
{
    /**
     * @return Entity[]
     */
    public function all()
    {
        return $this->hydrateMany($this->request($this->endPoint));
    }
}
