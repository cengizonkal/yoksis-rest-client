<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

trait DeleteTrait
{
    /**
     * @param int|string $id
     * @return mixed Servisin döndürdüğü yanıt (JSON çözülmüş ya da ham metin)
     */
    public function delete($id)
    {
        return $this->request($this->endPoint . '/' . rawurlencode((string)$id), ['method' => 'DELETE']);
    }
}
