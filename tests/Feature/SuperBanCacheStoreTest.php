<?php

namespace PiusAdams\SuperBan\Tests\Feature;

use Orchestra\Testbench\TestCase;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;
use PiusAdams\SuperBan\Services\SuperBanCacheService;

class SuperBanCacheStoreTest extends TestCase
{

    protected function getPackageProviders($app): array
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }


    // test that the cache store is set to what is in the config file
    public function testCacheStoreIsSet()
    {
        $this->assertEquals(config('superban.cache_driver'),
            app(SuperBanCacheService::class)->getDefaultDriver());
    }

    // if the cache store is set to file, test that the file cache store is used
    public function testFileCacheStoreIsUsed()
    {
        // set the cache store to file
        config(['superban.cache_driver' => 'array']);
        $this->assertEquals('array',  app(SuperBanCacheService::class)->getDefaultDriver());
    }

}
