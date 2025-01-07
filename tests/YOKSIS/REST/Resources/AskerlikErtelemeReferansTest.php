<?php

namespace YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\AskerlikErtelemeReferans;
use PHPUnit\Framework\TestCase;

class AskerlikErtelemeReferansTest extends TestCase
{

    private $client;
    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/rest/obs/');
        $this->client->setAuth($auth);
    }

    public function test_it_should_give_all_lookups()
    {
        $items = $this->client->askerlikErtelemeReferans()->find('ERTELEME_IPTAL_NEDENLERI');
        $this->assertTrue(is_array($items));
    }
}
