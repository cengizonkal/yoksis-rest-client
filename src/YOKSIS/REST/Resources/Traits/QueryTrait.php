<?php


namespace Conkal\YOKSIS\REST\Resources\Traits;

use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\YOK;

/**
 * Trait FindTrait
 * @package Conkal\YOKSIS\REST\Resources\Traits
 * @property Entity $entity
 * @property YOK $client
 * @property string $endPoint
 */
trait QueryTrait
{
    public function query(array $query)
    {
        $response = json_decode($this->client->send($this->endPoint, ['query' => $query])->getBody());

        $entities = [];
        if ($response) {
            if (is_array($response)) {
                foreach ($response as $item) {
                    $entities[] = new $this->entity($item);
                }
            }else{
                $entities[] = new $this->entity($response);
            }

        }
        return $entities;
    }
}