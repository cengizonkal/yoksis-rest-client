<?php


namespace Conkal\YOKSIS\REST;

use Conkal\YOKSIS\REST\Resources\AskerlikDurumSorgula;
use Conkal\YOKSIS\REST\Resources\AskerlikErtelemeReferans;
use Conkal\YOKSIS\REST\Resources\AskerlikErtelemeTalep;
use Conkal\YOKSIS\REST\Resources\CezaTurleri;
use Conkal\YOKSIS\REST\Resources\Duyurular;
use Conkal\YOKSIS\REST\Resources\FotografIndir;
use Conkal\YOKSIS\REST\Resources\HazirlikDetay;
use Conkal\YOKSIS\REST\Resources\HazirlikTurleri;
use Conkal\YOKSIS\REST\Resources\KykOgrenciSorgula;
use Conkal\YOKSIS\REST\Resources\MebMezunDetay;
use Conkal\YOKSIS\REST\Resources\Mezunlar;
use Conkal\YOKSIS\REST\Resources\OgrenciCezalar;
use Conkal\YOKSIS\REST\Resources\OgrenciIletisimBilgileri;
use Conkal\YOKSIS\REST\Resources\OgrenciIzinler;
use Conkal\YOKSIS\REST\Resources\OgrenciTranskript;
use Conkal\YOKSIS\REST\Resources\PedagojikFormasyon;
use Conkal\YOKSIS\REST\Resources\PedagojikFormasyonAlanlari;
use Conkal\YOKSIS\REST\Resources\SaglikBakanligiTescil;
use Conkal\YOKSIS\REST\Resources\Teyitlesme;
use Conkal\YOKSIS\REST\Resources\Universiteler;
use Conkal\YOKSIS\REST\Resources\VakifOgrenimUcreti;
use Conkal\YOKSIS\REST\Resources\YatayGecisler;
use Conkal\YOKSIS\REST\Resources\YerlestirmeVeri;
use Conkal\YOKSIS\REST\Resources\YurtDisindanYatayGecis;
use Conkal\YOKSIS\REST\Exceptions\ExceptionFactory;
use Conkal\YOKSIS\REST\Http\ClientFactory;
use Conkal\YOKSIS\REST\Utilities\AuthInterface;
use Conkal\YOKSIS\REST\Utilities\BasicAuth;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class YOK
{
    /**
     * YÖKSİS test ortamı adresi.
     */
    const TEST_URI = 'https://servisler.yok.gov.tr/resttest/obs/';

    /**
     * YÖKSİS canlı ortam adresi.
     */
    const PRODUCTION_URI = 'https://servisler.yok.gov.tr/rest/obs/';

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var ClientInterface
     */
    public $client;

    /**
     * @var AuthInterface|null
     */
    private $auth;

    /**
     * @param string $baseUri YÖKSİS servis adresi (ör. YOK::TEST_URI)
     * @param ClientInterface|null $httpClient Zaman aşımı, proxy vb. ayarlar için özel Guzzle istemcisi
     * @param AuthInterface|null $auth
     */
    public function __construct($baseUri, ?ClientInterface $httpClient = null, ?AuthInterface $auth = null)
    {
        $this->setBaseUri($baseUri);
        $this->client = $httpClient ?: new Client();
        $this->auth = $auth;
    }

    /**
     * Yeniden deneme, zaman aşımı ve (isteğe bağlı) loglama ayarlarıyla hazır bir istemci oluşturur.
     *
     * <code>
     * $client = YOK::create(YOK::TEST_URI, $kullanici, $sifre, ['logger' => $logger, 'retries' => 3]);
     * </code>
     *
     * @param string $baseUri
     * @param string|null $username
     * @param string|null $password
     * @param array $options ClientFactory::create() seçenekleri
     * @return static
     */
    public static function create($baseUri, $username = null, $password = null, array $options = [])
    {
        $auth = $username !== null && $username !== '' ? new BasicAuth($username, $password) : null;
        return new static($baseUri, ClientFactory::create($options), $auth);
    }

    /**
     * @return $this
     */
    public function setAuth(AuthInterface $auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * @return AuthInterface|null
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param string $baseUri
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Servise ham bir istek gönderir.
     *
     * Desteklenen seçenekler: method, contentType ve Guzzle istek seçenekleri (query, json, timeout, ...).
     *
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     * @throws Exceptions\AuthenticationException 401/403
     * @throws Exceptions\NotFoundException 404
     * @throws Exceptions\ValidationException 400/409/422
     * @throws Exceptions\RateLimitException 429
     * @throws Exceptions\RequestFailedException Diğer 4xx
     * @throws Exceptions\ServerErrorException 5xx
     * @throws Exceptions\ConnectionException Bağlantı hatası
     * @throws GuzzleException Diğer Guzzle hataları
     */
    public function send($endPoint, $options = [])
    {
        $options = array_merge(
            [
                'method' => 'GET',
                'contentType' => 'application/json',
            ],
            $options
        );

        $method = strtoupper($options['method']);
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => $options['contentType'],
        ];
        unset($options['method'], $options['contentType'], $options['postFields'], $options['queryParams']);

        if ($this->auth) {
            $options = array_merge($options, $this->auth->toArray());
        }

        $request = new Request($method, $this->baseUri . ltrim($endPoint, '/'), $headers);
        try {
            return $this->client->send($request, $options);
        } catch (GuzzleException $e) {
            throw ExceptionFactory::fromGuzzle($e);
        }
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

    public function fotografIndir()
    {
        return new FotografIndir($this);
    }

    public function ogrenciIzinler()
    {
        return new OgrenciIzinler($this);
    }

    public function yurtDisindanYatayGecis()
    {
        return new YurtDisindanYatayGecis($this);
    }

    public function askerlikErtelemeTalep()
    {
        return new AskerlikErtelemeTalep($this);
    }

    public function askerlikErtelemeReferans()
    {
        return new AskerlikErtelemeReferans($this);
    }

    public function kykOgrenciSorgula()
    {
        return new KykOgrenciSorgula($this);
    }

    public function ogrenciTranskript()
    {
        return new OgrenciTranskript($this);
    }

    public function askerlikDurum()
    {
        return new AskerlikDurumSorgula($this);
    }

    public function cezaTurleri()
    {
        return new CezaTurleri($this);
    }

    public function duyurular()
    {
        return new Duyurular($this);
    }

    public function mebMezunDetay()
    {
        return new MebMezunDetay($this);
    }

    public function mezunlar()
    {
        return new Mezunlar($this);
    }

    public function ogrenciCezalar()
    {
        return new OgrenciCezalar($this);
    }

    public function ogrenciIletisimBilgileri()
    {
        return new OgrenciIletisimBilgileri($this);
    }

    public function saglikBakanligiTescil()
    {
        return new SaglikBakanligiTescil($this);
    }

    public function teyitlesme()
    {
        return new Teyitlesme($this);
    }

    public function universiteler()
    {
        return new Universiteler($this);
    }

    public function vakifOgrenimUcreti()
    {
        return new VakifOgrenimUcreti($this);
    }

    public function yatayGecisler()
    {
        return new YatayGecisler($this);
    }
}
