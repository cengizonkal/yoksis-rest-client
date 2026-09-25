<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\PaginatesTrait;

/**
 * Üniversiteden mezun olan kişiler (mezunListele). Yanıt sayfalıdır.
 *
 * @method \Conkal\YOKSIS\REST\Pagination\Page paginate(array $query = [])
 * @method \Generator|\Conkal\YOKSIS\REST\Entities\Mezun[] cursor(array $query = [], $size = null, $maxPages = 1000)
 */
class Mezunlar extends ResourceAbstract
{
    use PaginatesTrait;

    protected $endPoint = 'mezunlar';
    protected $entity = \Conkal\YOKSIS\REST\Entities\Mezun::class;

    /**
     * İstenen sayfadaki mezunları döndürür. Tüm mezunlar için cursor() kullanın.
     *
     * @param array $query ör. ['page' => 0, 'size' => 1000]
     * @return \Conkal\YOKSIS\REST\Entities\Mezun[]
     */
    public function query(array $query = [])
    {
        return $this->paginate($query)->items();
    }
}
