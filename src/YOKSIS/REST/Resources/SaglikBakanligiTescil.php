<?php


namespace Conkal\YOKSIS\REST\Resources;

class SaglikBakanligiTescil extends ResourceAbstract
{
    protected $endPoint = 'saglikbakanligitescilsorgula';
    protected $entity = \Conkal\YOKSIS\REST\Entities\SaglikBakanligiTescil::class;

    /**
     * Mezunun Sağlık Bakanlığı tescil bilgilerini döndürür (saglikBakTescilKayit).
     *
     * @param string|int $tcKimlikNo
     * @return \Conkal\YOKSIS\REST\Entities\SaglikBakanligiTescil[]
     */
    public function sorgula($tcKimlikNo)
    {
        return $this->hydrateMany($this->request($this->endPoint, ['query' => ['tcKimlikNo' => $tcKimlikNo]]));
    }
}
