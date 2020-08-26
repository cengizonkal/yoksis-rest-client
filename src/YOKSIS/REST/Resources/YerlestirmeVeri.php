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
     * @param array $query
     * @return \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri[]
     */
    public function query(array $query)
    {
        $response = $this->client->send($this->endPoint, ['query' => $query])->content;
        $entities = [];
        if ($response) {
            foreach ($response as $item) {
                array_push($entities, new \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri($item));
            }
        }
        return $entities;
    }

}