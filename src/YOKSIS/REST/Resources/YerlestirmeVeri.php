<?php


namespace Conkal\YOKSIS\REST\Resources;




class YerlestirmeVeri extends ResourceAbstract
{


    protected $endPoint = 'yerlestirmeveri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri::class;

    public function query(array $query)
    {
        $response = $this->client->send($this->endPoint, ['query' => $query])->content;
        $entities = [];
        if ($response) {
            foreach ($response as $item) {
                array_push($entities, new $this->entity($item));
            }
        }
        return $entities;
    }

}