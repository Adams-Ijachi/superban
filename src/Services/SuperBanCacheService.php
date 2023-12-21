<?php

namespace PiusAdams\SuperBan\Services;

use Illuminate\Cache\CacheManager;

class SuperBanCacheService extends CacheManager
{
    public function getDefaultDriver()
    {
        return $this->app['config']['superban.cache_driver'];
    }

}
