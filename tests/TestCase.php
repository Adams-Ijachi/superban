<?php

namespace PiusAdams\SuperBan\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Orchestra\Testbench\TestCase as Orchestra;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;

 class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }
}
