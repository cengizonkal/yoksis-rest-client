<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\UpdateTrait;

/**
 * Öğrenci iletişim (e-posta) bilgileri (ogrenciIletisimBilgi).
 *
 * create() ve update() servisin döndürdüğü {"id": ...} nesnesini döndürür.
 * update(): id + tcKimlikNo ya da birimId + tcKimlikNo ile eşleşen kaydın ePosta alanını günceller.
 */
class OgrenciIletisimBilgileri extends ResourceAbstract
{
    use CreateTrait, UpdateTrait, DeleteTrait;

    protected $endPoint = 'ogrenciIletisimBilgi';
    protected $entity = \Conkal\YOKSIS\REST\Entities\OgrenciIletisimBilgi::class;
    protected $wrapsResponses = true;
    protected $updateUsesIdInPath = false;

    /**
     * Birim id ve T.C. kimlik numarasıyla eşleşen iletişim bilgisi kaydını siler.
     *
     * @param int|string $birimId
     * @param int|string $tcKimlikNo
     * @return mixed
     */
    public function deleteByTcKimlikNo($birimId, $tcKimlikNo)
    {
        return $this->request($this->endPoint, [
            'method' => 'DELETE',
            'query' => ['birimId' => $birimId, 'tcKimlikNo' => $tcKimlikNo],
        ]);
    }
}
