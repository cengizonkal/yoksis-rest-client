<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Yatay geçiş ile kurum içi/dışı giden öğrenciler (yatayGecisListele).
 *
 * @method \Conkal\YOKSIS\REST\Entities\YatayGecis[] query(array $query) ör. ['yil' => 2018]
 */
class YatayGecisler extends ResourceAbstract
{
    use QueryTrait;

    protected $endPoint = 'yatayGecisListele';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YatayGecis::class;

    /**
     * Gittiği üniversiteye belirtilen yılda kayıt olan öğrencileri döndürür.
     *
     * @param int $yil
     * @return \Conkal\YOKSIS\REST\Entities\YatayGecis[]
     */
    public function yilaGore($yil)
    {
        return $this->query(['yil' => (int)$yil]);
    }
}
