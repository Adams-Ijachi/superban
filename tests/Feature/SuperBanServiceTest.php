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

    /**
     * Test if the SuperBanService is able to correctly ban a key and return true when checking if it is banned.
     */
    public function testSuperBanServiceIsBannedReturnsTrue()
    {
        $this->superbanService->ban('testKey', 1);
        $this->assertTrue($this->superbanService->isBanned('testKey'));
    }

    /**
     * Test case for the `isBanned` method of the `SuperBanService` class.
     *
     * This test verifies that the `isBanned` method returns `false` when a given key is not banned.
     */
    public function testSuperBanServiceIsBannedReturnsFalse()
    {
        $this->assertFalse($this->superbanService->isBanned('testKey1'));
    }


}
