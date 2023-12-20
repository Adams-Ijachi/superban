<?php

namespace PiusAdams\SuperBan\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;


class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
          SuperBanServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'SuperBan' => \PiusAdams\SuperBan\Facades\SuperBan::class,
        ];
    }
}
