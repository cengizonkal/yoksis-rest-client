<?php


namespace Conkal\YOKSIS\REST\Resources;

/**
 * Class YerlestirmeVeri
 * @package Conkal\YOKSIS\REST\Resources
 */
class YerlestirmeVeri extends ResourceAbstract
{
    protected $endPoint = 'yerlestirmeveri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri::class;

    /**
     * @param array $query ör. ['tur' => 'YKS', 'yil' => '2019']
     * @return \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[]
     */
    public function query(array $query)
    {
        $response = $this->request($this->endPoint, ['query' => $query]);

        // Servis sayfalı yanıt döndürür; kayıtlar "content" alanındadır.
        return $this->hydrateMany(isset($response->content) ? $response->content : []);
    }

}
