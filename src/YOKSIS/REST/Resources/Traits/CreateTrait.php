<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;


use Conkal\YOKSIS\REST\Entities\Entity;

trait CreateTrait
{
    /**
     * @param Entity $entity
     * @return mixed Servisin döndürdüğü yanıt (JSON çözülmüş ya da ham metin)
     */
    public function create(Entity $entity)
    {
        return $this->request($this->endPoint, ['method' => 'POST', 'json' => $entity->toArray()]);
    }
}
