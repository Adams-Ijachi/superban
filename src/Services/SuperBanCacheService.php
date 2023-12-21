<?php

namespace PiusAdams\SuperBan\Services;

use Illuminate\Cache\CacheManager;

/**
 * Class SuperBanCacheService
 *
 * This class extends the CacheManager class and provides a custom implementation for the SuperBan package's cache service.
 */
class SuperBanCacheService extends CacheManager
{

    /**
     * Get the default cache driver for the SuperBan package.
     *
     * @return string The default cache driver.
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['superban.cache_driver'];
    }

}
