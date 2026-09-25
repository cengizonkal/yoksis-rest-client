<?php


namespace Conkal\YOKSIS\REST\Resources;

/**
 * ASAL askerlik durum sorgulama (askerlikDurumSorgula).
 */
class AskerlikDurumSorgula extends ResourceAbstract
{
    protected $endPoint = 'askerlikDurumSorgula';
    protected $entity = \Conkal\YOKSIS\REST\Entities\AskerlikDurum::class;

    /**
     * Yeni kayıt veya mevcut öğrencinin askerlikle ilgili son durumunu döndürür.
     *
     * @param string|int $tcKimlikNo
     * @return \Conkal\YOKSIS\REST\Entities\AskerlikDurum|null
     */
    public function sorgula($tcKimlikNo)
    {
        $items = $this->hydrateMany($this->request($this->endPoint, ['query' => ['tcKimlikNo' => $tcKimlikNo]]));
        return $items ? $items[0] : null;
    }
}
