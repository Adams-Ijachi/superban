<?php

declare(strict_types=1);

namespace PiusAdams\SuperBan;

use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;
use PiusAdams\SuperBan\Services\SuperBanService;
use Illuminate\Support\ServiceProvider as PackageServiceProvider;

/**
 * Class SuperBanServiceProviderTest
 *
 * This class is responsible for testing the SuperBanServiceProvider.
 */
class SuperBanServiceProviderTest extends PackageServiceProvider
{

    /**
     * Boot the service provider.
     *
     * This method is called when the application boots.
     * It registers the SuperBanService and the SuperBanRouteMiddleware.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(SuperBanServiceContract::class, SuperBanService::class);

        $this->app['router']
            ->aliasMiddleware('superban',
                \PiusAdams\SuperBan\Middleware\SuperBanRouteMiddleware::class);

    }

}
