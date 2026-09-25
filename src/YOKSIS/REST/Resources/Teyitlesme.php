<?php


namespace Conkal\YOKSIS\REST\Resources;

/**
 * Teyitleşme (sonBasariliTeyitlesmeTarihiniGetir).
 */
class Teyitlesme extends ResourceAbstract
{
    protected $endPoint = 'sonbasariliteyitlesme';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Teyitlesme::class;

    /**
     * Üniversitenin en son başarılı teyitleşme tarih ve saatini döndürür.
     *
     * @return \Conkal\YOKSIS\REST\Entities\Teyitlesme|null
     */
    public function sonBasarili()
    {
        $items = $this->hydrateMany($this->request($this->endPoint));
        return $items ? $items[0] : null;
    }
}
