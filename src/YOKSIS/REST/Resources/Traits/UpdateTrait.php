<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

use Conkal\YOKSIS\REST\Entities\Entity;

trait UpdateTrait
{
    /**
     * Kaydı günceller (PUT). Kaydın id'si $id ile ya da entity'nin id alanıyla verilir.
     *
     * @param Entity $entity
     * @param int|string|null $id
     * @return mixed Servisin döndürdüğü yanıt
     */
    public function update(Entity $entity, $id = null)
    {
        if ($id === null && isset($entity->id)) {
            $id = $entity->id;
        }

        if (!$this->updateUsesIdInPath) {
            return $this->request($this->endPoint, ['method' => 'PUT', 'json' => $entity->toArray()]);
        }
        if ($id === null || $id === '') {
            throw new \InvalidArgumentException('Güncellenecek kaydın id bilgisi verilmedi.');
        }
        return $this->request($this->path($id), ['method' => 'PUT', 'json' => $entity->toArray()]);
    }
}
