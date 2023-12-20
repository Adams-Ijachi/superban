<?php

namespace PiusAdams\SuperBan\Tests\Feature;

use Carbon\Carbon;
use Orchestra\Testbench\TestCase;
use PiusAdams\SuperBan\Exceptions\UserBannedException;
use PiusAdams\SuperBan\Providers\SuperBanServiceProvider;
use Throwable;

class SuperBanMiddlewareTest extends TestCase
{

    protected function tearDown(): void
    {
        parent::tearDown();

        Carbon::setTestNow(null);
    }
    protected function getPackageProviders($app): array
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }



    public function testSuperBanMiddlewareBansUsersAfterTries()
    {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 0, 0));

        $this->app['router']->get('/test', function () {
            return 'test';
        })->middleware('superban:2,1,5');

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 2, 0));

        try {
             $this->withoutExceptionHandling()->get('/test');
        } catch (Throwable $e) {

            $this->assertInstanceOf(UserBannedException::class, $e);
            $this->assertEquals(403, $e->getCode());
        }
    }

    // test that ban is lifted after ban time
    public function testSuperBanMiddlewareLiftsBanAfterBanTime()
    {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 0, 0));

        $this->app['router']->get('/test', function () {
            return 'test';
        })->middleware('superban:2,1,5');

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        // fast-forward 5 minutes
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 6, 0));

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());
    }

    // test that it works with multiple routes
    public function testSuperBanMiddlewareWorksForMultipleRoutes()
    {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 0, 0));

        $this->app['router']->get('/test', function () {
            return 'test';
        })->middleware('superban:2,1,5');

        $this->app['router']->get('/test2', function () {
            return 'test2';
        })->middleware('superban:2,1,9');

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 2, 0));

        try {
             $this->withoutExceptionHandling()->get('/test');
        } catch (Throwable $e) {

            $this->assertInstanceOf(UserBannedException::class, $e);
            $this->assertEquals(403, $e->getCode());
        }

        try {
             $this->withoutExceptionHandling()->get('/test2');
        } catch (Throwable $e) {

            $this->assertInstanceOf(UserBannedException::class, $e);
            $this->assertEquals(403, $e->getCode());
        }
    }

    // test that it works with multiple routes and it lifts ban after ban time
    public function testSuperBanMiddlewareWorksForMultipleRoutesAndLiftsBanAfterBanTime()
    {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 0, 0));

        $this->app['router']->get('/test', function () {
            return 'test';
        })->middleware('superban:2,1,5');

        $this->app['router']->get('/test2', function () {
            return 'test2';
        })->middleware('superban:2,1,9');

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        // fast-forward 5 minutes
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 6, 0));

        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        // fast-forward 9 minutes
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 15, 0));

        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
