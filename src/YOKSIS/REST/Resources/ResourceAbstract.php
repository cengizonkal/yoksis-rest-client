<?php

namespace Conkal\YOKSIS\REST\Resources;


use Conkal\YOKSIS\REST\Entities\Entity;
use Conkal\YOKSIS\REST\YOK;

abstract class ResourceAbstract
{

    /**
     * @var YOK
     */
    protected $client;

    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var string|null Entity sınıf adı
     */
    protected $entity;

    public function __construct(YOK $client)
    {
        $this->client = $client;
    }

    public function getEndPoint()
    {
        return $this->endPoint;
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * İstek gönderir ve JSON yanıtı çözer. Yanıt JSON değilse ham gövdeyi döndürür.
     *
     * @param string $endPoint
     * @param array $options
     * @return mixed
     */
    protected function request($endPoint, array $options = [])
    {
        $body = (string)$this->client->send($endPoint, $options)->getBody();
        if ($body === '') {
            return null;
        }

        $decoded = json_decode($body);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $body;
        }
        return $decoded;
    }

    /**
     * @param mixed $data
     * @return Entity
     */
    protected function hydrate($data)
    {
        return new $this->entity($data);
    }

    /**
     * Yanıtı entity dizisine çevirir. Tek bir nesne dönerse tek elemanlı dizi oluşturur.
     *
     * @param mixed $data
     * @return Entity[]
     */
    protected function hydrateMany($data)
    {
        if (is_object($data)) {
            return [$this->hydrate($data)];
        }
        if (!is_array($data)) {
            return [];
        }

        $entities = [];
        foreach ($data as $item) {
            $entities[] = $this->hydrate($item);
        }
        return $entities;
    }
}
