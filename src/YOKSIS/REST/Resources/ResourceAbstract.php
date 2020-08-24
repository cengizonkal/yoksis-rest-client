<?php

namespace Conkal\YOKSIS\REST\Resources;


use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\YOK;

abstract class ResourceAbstract
{

    protected $client;
    protected $endPoint;
    protected $entity;

    public function __construct(YOK $client)
    {
        $this->client = $client;
    }






}
