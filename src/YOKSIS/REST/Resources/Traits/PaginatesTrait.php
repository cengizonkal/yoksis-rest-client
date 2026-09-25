<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\Pagination\Page;

/**
 * Spring Data biçiminde sayfalı yanıt döndüren servisler için.
 */
trait PaginatesTrait
{
    /**
     * Sorgu parametresi olarak gönderilen sayfa numarası ve boyutu alanlarının adları.
     *
     * @var string
     */
    protected $pageParameter = 'page';

    /**
     * @var string
     */
    protected $sizeParameter = 'size';

    /**
     * Tek bir sayfayı sayfa bilgileriyle birlikte döndürür.
     *
     * @param array $query Sorgu parametreleri; sayfa için 'page' (0'dan başlar) ve 'size' eklenebilir
     * @return Page
     */
    public function paginate(array $query = [])
    {
        $response = $this->request($this->endPoint, ['query' => $query]);

        if (is_array($response)) {
            // Servis sayfalamadan düz liste döndürdüyse tek sayfa kabul et.
            return new Page($this->hydrateMany($response), (object)['last' => true]);
        }

        $content = is_object($response) && isset($response->content) ? $response->content : [];
        return new Page($this->hydrateMany($content), is_object($response) ? $response : null);
    }

    /**
     * Tüm sayfalardaki kayıtları sırayla, ihtiyaç duyuldukça çekerek döndürür.
     *
     * <code>
     * foreach ($client->yerlestirmeVeri()->cursor(['tur' => 'YKS', 'yil' => '2019']) as $kayit) { ... }
     * </code>
     *
     * @param array $query
     * @param int|null $size Sayfa boyutu (null ise servisin varsayılanı)
     * @param int $maxPages Güvenlik sınırı: en fazla çekilecek sayfa sayısı
     * @return \Generator|Entity[]
     */
    public function cursor(array $query = [], $size = null, $maxPages = 1000)
    {
        $pageNumber = isset($query[$this->pageParameter]) ? (int)$query[$this->pageParameter] : 0;
        if ($size !== null) {
            $query[$this->sizeParameter] = (int)$size;
        }

        for ($fetched = 0; $fetched < $maxPages; $fetched++) {
            $query[$this->pageParameter] = $pageNumber;
            $page = $this->paginate($query);

            foreach ($page as $item) {
                yield $item;
            }

            // Servis sayfa parametresini yok sayıyorsa sonsuz döngüye girmemek için dur.
            $returned = $page->getPageNumber();
            if ($page->isLast() || count($page) === 0 || ($returned !== null && $returned !== $pageNumber)) {
                return;
            }
            $pageNumber++;
        }
    }
}
