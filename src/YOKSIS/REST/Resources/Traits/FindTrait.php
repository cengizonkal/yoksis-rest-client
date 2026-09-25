<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;


use Conkal\YOKSIS\REST\Entities\Entity;

trait FindTrait
{
    /**
     * Tek bir kayıt dönerse entity, birden fazla kayıt dönerse entity dizisi, hiç kayıt yoksa null döndürür.
     *
     * @param int|string $id
     * @return Entity|Entity[]|null
     */
    public function find($id)
    {
        $entities = $this->hydrateMany($this->request($this->path($id)));

        if (count($entities) === 0) {
            return null;
        }
        if (count($entities) === 1) {
            return $entities[0];
        }
        return $entities;
    }
}
