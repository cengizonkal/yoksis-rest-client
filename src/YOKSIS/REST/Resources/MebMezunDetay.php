<?php


namespace Conkal\YOKSIS\REST\Resources;

class MebMezunDetay extends ResourceAbstract
{
    protected $endPoint = 'mebmezundetaysorgula';
    protected $entity = \Conkal\YOKSIS\REST\Entities\MebMezunDetay::class;

    /**
     * Öğrencinin MEB ortaöğretim mezuniyet detayını döndürür (MezunDetaySorgula).
     *
     * @param string|int $tcKimlikNo
     * @return \Conkal\YOKSIS\REST\Entities\MebMezunDetay[]
     */
    public function sorgula($tcKimlikNo)
    {
        return $this->hydrateMany($this->request($this->endPoint, ['query' => ['tcKimlikNo' => $tcKimlikNo]]));
    }
}
