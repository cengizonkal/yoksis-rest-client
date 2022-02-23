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
trait FindTrait
{
    public function find($id)
    {
        $items = json_decode($this->client->send($this->endPoint.'/'.$id)->getBody());

        //if there is only one item in the array, return it
        if (count($items) == 1) {
            return new $this->entity($items[0]);
        }

        //if there is more than one item in the array, return an array of entities
        $entities = [];
        foreach ($items as $item) {
            $entities[] = new $this->entity($item);
        }
        return $entities;
    }
}