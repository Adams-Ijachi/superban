<?php

namespace PiusAdams\SuperBan\Tests\Feature;

use Orchestra\Testbench\TestCase;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;
use PiusAdams\SuperBan\Services\SuperBanService;

class SuperBanServiceTest extends TestCase
{

    protected SuperBanService $superbanService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superbanService = $this->app->make(SuperBanService::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }

    public function testSuperBanServiceIsBannedReturnsTrue()
    {
        $this->superbanService->ban('testKey', 1);
        $this->assertTrue($this->superbanService->isBanned('testKey'));
    }

    public function testSuperBanServiceIsBannedReturnsFalse()
    {

        $this->assertFalse($this->superbanService->isBanned('testKey'));
    }


}
