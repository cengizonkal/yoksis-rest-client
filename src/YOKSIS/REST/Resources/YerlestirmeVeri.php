<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\PaginatesTrait;

/**
 * Class YerlestirmeVeri
 * @package Conkal\YOKSIS\REST\Resources
 * @method \Conkal\YOKSIS\REST\Pagination\Page paginate(array $query = [])
 * @method \Generator|\Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[] cursor(array $query = [], $size = null, $maxPages = 1000)
 */
class YerlestirmeVeri extends ResourceAbstract
{
    use PaginatesTrait;

    protected $endPoint = 'yerlestirmeveri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri::class;

    /**
     * Yalnızca istenen sayfadaki kayıtları döndürür. Toplam kayıt sayısı gibi
     * sayfa bilgileri için paginate(), tüm sayfalar için cursor() kullanın.
     *
     * @param array $query ör. ['tur' => 'YKS', 'yil' => '2019']
     * @return \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[]
     */
    public function query(array $query)
    {
        return $this->paginate($query)->items();
    }

}
