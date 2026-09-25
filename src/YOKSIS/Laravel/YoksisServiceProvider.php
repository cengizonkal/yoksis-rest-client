<?php

namespace Conkal\YOKSIS\Laravel;

use Conkal\YOKSIS\REST\YOK;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel entegrasyonu. Paket keşfi (package auto-discovery) ile otomatik kaydedilir.
 *
 * Config dosyasını yayınlamak için:
 *   php artisan vendor:publish --tag=yoksis-config
 */
class YoksisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(self::configPath(), 'yoksis');

        $this->app->singleton(YOK::class, function ($app) {
            $config = $app['config']->get('yoksis', []);

            $logger = null;
            if (!empty($config['log_channel']) && $app->bound('log')) {
                $log = $app->make('log');
                $logger = method_exists($log, 'channel') ? $log->channel($config['log_channel']) : $log;
            }

            return YOK::create(
                isset($config['base_uri']) ? $config['base_uri'] : YOK::TEST_URI,
                isset($config['username']) ? $config['username'] : null,
                isset($config['password']) ? $config['password'] : null,
                [
                    'timeout' => (float)(isset($config['timeout']) ? $config['timeout'] : 30),
                    'connect_timeout' => (float)(isset($config['connect_timeout']) ? $config['connect_timeout'] : 10),
                    'retries' => (int)(isset($config['retries']) ? $config['retries'] : 2),
                    'retry_delay' => (int)(isset($config['retry_delay']) ? $config['retry_delay'] : 500),
                    'logger' => $logger,
                ]
            );
        });

        $this->app->alias(YOK::class, 'yoksis');
    }

    public function boot()
    {
        $this->publishes([
            self::configPath() => $this->configTargetPath(),
        ], 'yoksis-config');
    }

    public function provides()
    {
        return [YOK::class, 'yoksis'];
    }

    private static function configPath()
    {
        return dirname(__DIR__, 3) . '/config/yoksis.php';
    }

    private function configTargetPath()
    {
        return function_exists('config_path') ? config_path('yoksis.php') : 'config/yoksis.php';
    }
}
