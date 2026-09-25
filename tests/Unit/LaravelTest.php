<?php

namespace Conkal\YOKSIS\Tests\Unit;

use Conkal\YOKSIS\Laravel\Facades\Yoksis;
use Conkal\YOKSIS\Laravel\YoksisServiceProvider;
use Conkal\YOKSIS\REST\YOK;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Psr\Log\LoggerInterface;

class LaravelTest extends TestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Container::class) || !class_exists(Repository::class)) {
            $this->markTestSkipped('illuminate/container ve illuminate/config kurulu değil.');
        }
    }

    protected function tearDown(): void
    {
        if (class_exists(Facade::class)) {
            Facade::clearResolvedInstances();
            Facade::setFacadeApplication(null);
        }
    }

    private function app(array $config)
    {
        $app = new LaravelTestApplication();
        $app->instance('config', new Repository(['yoksis' => $config]));
        (new YoksisServiceProvider($app))->register();
        return $app;
    }

    public function test_it_registers_a_configured_singleton()
    {
        $app = $this->app([
            'base_uri' => YOK::PRODUCTION_URI,
            'username' => 'kullanici',
            'password' => 'sifre',
            'timeout' => 12,
        ]);

        $yok = $app->make(YOK::class);

        $this->assertInstanceOf(YOK::class, $yok);
        $this->assertSame($yok, $app->make(YOK::class));
        $this->assertSame($yok, $app->make('yoksis'));
        $this->assertSame(YOK::PRODUCTION_URI, $yok->getBaseUri());
        $this->assertSame(['auth' => ['kullanici', 'sifre']], $yok->getAuth()->toArray());
        $this->assertEquals(12, $yok->client->getConfig('timeout'));
    }

    public function test_package_defaults_are_merged()
    {
        $yok = $this->app([])->make(YOK::class);

        $this->assertSame(YOK::TEST_URI, $yok->getBaseUri());
        $this->assertNull($yok->getAuth());
        $this->assertEquals(30, $yok->client->getConfig('timeout'));
    }

    public function test_log_channel_is_resolved_from_log_manager()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $manager = new class($logger) {
            public $requested;
            private $logger;

            public function __construct($logger)
            {
                $this->logger = $logger;
            }

            public function channel($name)
            {
                $this->requested = $name;
                return $this->logger;
            }
        };

        $app = $this->app(['log_channel' => 'yoksis']);
        $app->instance('log', $manager);
        $app->make(YOK::class);

        $this->assertSame('yoksis', $manager->requested);
    }

    public function test_facade_resolves_client()
    {
        $app = $this->app(['username' => 'kullanici', 'password' => 'sifre']);
        Facade::setFacadeApplication($app);

        $this->assertSame($app->make(YOK::class), Yoksis::getFacadeRoot());
        $this->assertInstanceOf(\Conkal\YOKSIS\REST\Resources\HazirlikTurleri::class, Yoksis::hazirlikTurleri());
    }

    public function test_composer_json_declares_auto_discovery()
    {
        $composer = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);

        $this->assertContains(YoksisServiceProvider::class, $composer['extra']['laravel']['providers']);
        $this->assertSame(Yoksis::class, $composer['extra']['laravel']['aliases']['Yoksis']);
    }
}

if (class_exists(Container::class)) {
    /**
     * Laravel Application'ın ServiceProvider'ın kullandığı küçük bir alt kümesi.
     */
    class LaravelTestApplication extends Container
    {
        public function configurationIsCached()
        {
            return false;
        }

        public function runningInConsole()
        {
            return false;
        }
    }
}
