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
trait CreateTrait
{
    public function create(Entity $entity)
    {
        return $this->client->send($this->endPoint, ['method' => 'post', 'json' => $entity->toArray()]);
    }
}