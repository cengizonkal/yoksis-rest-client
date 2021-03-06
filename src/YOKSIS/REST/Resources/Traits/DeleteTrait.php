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
trait DeleteTrait
{

    public function delete($id)
    {
        $this->client->send($this->endPoint . '/' . $id, ['method' => 'delete']);
    }

}