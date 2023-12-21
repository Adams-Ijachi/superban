<?php

declare(strict_types=1);

namespace PiusAdams\SuperBan\Providers;

use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;
use PiusAdams\SuperBan\Services\SuperBanCacheService;
use PiusAdams\SuperBan\Services\SuperBanService;
use Illuminate\Support\ServiceProvider as PackageServiceProvider;

/**
 * The SuperBanServiceProvider class is responsible for bootstrapping the SuperBan package.
 */
class SuperBanServiceProvider extends PackageServiceProvider
{

    public function boot(): void
    {
        $this->publishConfig();
        $this->app->singleton(SuperBanServiceContract::class, SuperBanService::class);
        $this->app->bind(SuperBanCacheService::class, function (){
            return new SuperBanCacheService($this->app);
        });

        $this->app['router']
            ->aliasMiddleware('superban',
                \PiusAdams\SuperBan\Middleware\SuperBanRouteMiddleware::class);

    }

    public function register()
    {
        $this->mergeConfig();
    }

    // publish config file
    protected function publishConfig(): void
    {
        $path = $this->getConfigPath();
        $this->publishes([
            $path => config_path('superban.php'),
        ], 'superban-config');
    }

    public function getConfigPath(): string
    {

        return  realpath(__DIR__ . '/../../config/superban.php');

    }

    // merge config file
    protected function mergeConfig(): void
    {
        $path = $this->getConfigPath();
        $this->mergeConfigFrom($path, 'superban');
    }


}
