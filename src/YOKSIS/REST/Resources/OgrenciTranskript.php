<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;

/**
 * @method \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript|\Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript[]|null find($id)
 */
class OgrenciTranskript extends ResourceAbstract
{
    use CreateTrait, FindTrait, DeleteTrait;

    protected $endPoint = 'ogrencitranskript';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript::class;

    /**
     * Öğrencinin transkriptini T.C. kimlik numarası ve birim id ile getirir
     * (getOgrenciTranskriptByTcAndBirimId).
     *
     * @param string|int $tcKimlikNo
     * @param string|int $birimId
     * @return \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript|\Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript[]|null
     */
    public function findByTcKimlikNo($tcKimlikNo, $birimId)
    {
        $entities = $this->hydrateMany($this->request(
            $this->path($tcKimlikNo) . '/' . rawurlencode((string)$birimId)
        ));

        if (count($entities) === 0) {
            return null;
        }
        return count($entities) === 1 ? $entities[0] : $entities;
    }
}
