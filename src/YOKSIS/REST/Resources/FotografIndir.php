<?php


namespace Conkal\YOKSIS\REST\Resources;


use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

class FotografIndir extends ResourceAbstract
{
    protected $endPoint = 'fotografindir';

    public function find($tcKimlikNo)
    {
        return $this->client->send($this->endPoint, ['query' => ['tcKimlikNo' => $tcKimlikNo]]);
    }
}