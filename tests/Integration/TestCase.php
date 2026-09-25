<?php

namespace Conkal\YOKSIS\Tests\Integration;

use Conkal\YOKSIS\REST\Utilities\BasicAuth;
use Conkal\YOKSIS\REST\YOK;

/**
 * YÖKSİS test ortamına gerçek istek gönderen testler.
 *
 * YOKSIS_USERNAME ve YOKSIS_PASSWORD ortam değişkenleri tanımlı değilse atlanır.
 * Diğer değişkenler: YOKSIS_BASE_URI (varsayılan test ortamı), TEST_TCKNO, TEST_BIRIMID.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var YOK
     */
    protected $client;

    protected function setUp(): void
    {
        $user = getenv('YOKSIS_USERNAME');
        $pass = getenv('YOKSIS_PASSWORD');
        if (!$user || !$pass) {
            $this->markTestSkipped('YOKSIS_USERNAME ve YOKSIS_PASSWORD tanımlı değil.');
        }

        $this->client = new YOK(getenv('YOKSIS_BASE_URI') ?: YOK::TEST_URI);
        $this->client->setAuth(new BasicAuth($user, $pass));
    }

    protected function env($name)
    {
        $value = getenv($name);
        if (!$value) {
            $this->markTestSkipped($name . ' tanımlı değil.');
        }
        return $value;
    }
}
