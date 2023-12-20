<?php

declare(strict_types=1);

namespace PiusAdams\SuperBan;

use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;
use PiusAdams\SuperBan\Services\SuperBanService;
use Illuminate\Support\ServiceProvider as PackageServiceProvider;

class SuperBanServiceProviderTest extends PackageServiceProvider
{

    public function boot(): void
    {
        $this->app->singleton(SuperBanServiceContract::class, SuperBanService::class);

        $this->app['router']
            ->aliasMiddleware('superban',
                \PiusAdams\SuperBan\Middleware\SuperBanRouteMiddleware::class);

    }





}
