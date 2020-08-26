<?php


namespace Conkal\YOKSIS\REST;

use Conkal\YOKSIS\REST\Resources\HazirlikDetay;
use Conkal\YOKSIS\REST\Resources\HazirlikTurleri;
use Conkal\YOKSIS\REST\Resources\PedagojikFormasyon;
use Conkal\YOKSIS\REST\Resources\PedagojikFormasyonAlanlari;
use Conkal\YOKSIS\REST\Resources\YerlestirmeVeri;
use Conkal\YOKSIS\REST\Utilities;
use Conkal\YOKSIS\REST\Utilities\BasicAuth;
use GuzzleHttp\Psr7\Request;

class YOK
{
    private $baseUri;

    /**
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * @var BasicAuth
     */
    private $auth;

    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;
        $this->client = new \GuzzleHttp\Client();
    }

    public function setAuth(Utilities\AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }


    public function send($endPoint, $options = [])
    {
        $options = array_merge(
            [
                'method' => 'GET',
                'contentType' => 'application/json',
                'postFields' => null,
                'queryParams' => null
            ],
            $options
        );

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => $options['contentType'],
        ];
        if ($this->auth) {
            $options = array_merge($options, $this->auth->toArray());
        }

        $request = new Request(
            $options['method'],
            $this->baseUri . $endPoint,
            $headers
        );


        $response = $this->client->send($request, $options);

        return json_decode($response->getBody());
    }

    public function pedagojikFormasyon()
    {
        return new PedagojikFormasyon($this);
    }

    public function pedagojikFormasyonAlanlari()
    {
        return new PedagojikFormasyonAlanlari($this);
    }

    public function hazirlikTurleri()
    {
        return new HazirlikTurleri($this);
    }

    public function hazirlikDetay()
    {
        return new HazirlikDetay($this);
    }

    public function yerlestirmeVeri()
    {
        return new YerlestirmeVeri($this);
    }


}