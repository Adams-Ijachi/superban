<?php

namespace PiusAdams\SuperBan\Tests\Feature;

use Orchestra\Testbench\TestCase;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;

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
        $this->assertEquals(config('cache.default'), $this->app['cache']->getDefaultDriver());
    }

    // if the cache store is set to file, test that the file cache store is used
    public function testFileCacheStoreIsUsed()
    {
        // set the cache store to file
        config(['cache.default' => 'file']);
        $this->assertEquals('file', $this->app['cache']->getDefaultDriver());
    }

}
