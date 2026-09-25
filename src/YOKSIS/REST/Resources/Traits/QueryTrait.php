<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

use Conkal\YOKSIS\REST\Entities\Entity;

trait QueryTrait
{
    /**
     * @param array $query
     * @return Entity[]
     */
    public function query(array $query)
    {
        return $this->hydrateMany($this->request($this->endPoint, ['query' => $query]));
    }
}
