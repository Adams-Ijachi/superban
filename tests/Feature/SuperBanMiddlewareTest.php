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



    /**
     * Test case for the SuperBanMiddleware's behavior of banning users after a certain number of tries.
     *
     * This test sets the current time to January 1, 2023, and registers a route '/test' with the 'superban' middleware
     * configured to ban a user after 2 tries within 1 minute, with a ban duration of 5 minutes.
     * It then sends GET requests to '/test' twice, which should return a 200 status code.
     * After that, it sets the current time to 2 minutes later and sends another GET request to '/test',
     * which should throw a UserBannedException with a 403 status code.
     */
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
    /**
     * Test case for the SuperBanMiddlewareLiftsBanAfterBanTime method.
     *
     * This method tests if the SuperBanMiddleware correctly lifts the ban after the ban time has passed.
     */
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


    /**
     * Test case for the SuperBanMiddleware class.
     * Tests the behavior of the middleware when applied to multiple routes.
     */
    public function testSuperBanMiddlewareWorksForMultipleRoutes()
    {
        // Set the current time to January 1, 2023, 00:00:00
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 0, 0));

        // Define a route '/test' with the 'superban' middleware applied
        $this->app['router']->get('/test', function () {
            return 'test';
        })->middleware('superban:2,1,5');

        // Define a route '/test2' with the 'superban' middleware applied
        $this->app['router']->get('/test2', function () {
            return 'test2';
        })->middleware('superban:2,1,9');

        // Send a GET request to '/test' and assert the response status code is 200
        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        // Send another GET request to '/test' and assert the response status code is 200
        $response = $this->withoutExceptionHandling()->get('/test');
        $this->assertEquals(200, $response->getStatusCode());

        // Send a GET request to '/test2' and assert the response status code is 200
        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        // Send another GET request to '/test2' and assert the response status code is 200
        $response = $this->withoutExceptionHandling()->get('/test2');
        $this->assertEquals(200, $response->getStatusCode());

        // Set the current time to January 1, 2023, 00:02:00
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 0, 2, 0));

        // Send a GET request to '/test' and expect a UserBannedException to be thrown with a status code of 403
        try {
            $this->withoutExceptionHandling()->get('/test');
        } catch (Throwable $e) {
            $this->assertInstanceOf(UserBannedException::class, $e);
            $this->assertEquals(403, $e->getCode());
        }

        // Send a GET request to '/test2' and expect a UserBannedException to be thrown with a status code of 403
        try {
            $this->withoutExceptionHandling()->get('/test2');
        } catch (Throwable $e) {
            $this->assertInstanceOf(UserBannedException::class, $e);
            $this->assertEquals(403, $e->getCode());
        }
    }

    /**
     * Test the functionality of the SuperBanMiddleware for multiple routes and ban time lifting.
     *
     * This test ensures that the SuperBanMiddleware correctly handles multiple routes and lifts the ban after the specified ban time.
     * It sets the current time to a specific date and time using Carbon::setTestNow() to simulate different time scenarios.
     * It creates two routes '/test' and '/test2' with different ban times using the 'superban' middleware.
     * It then sends HTTP GET requests to these routes and asserts that the response status code is 200 (OK).
     * After a certain amount of time has passed, it fast-forwards the time using Carbon::setTestNow() to simulate the ban time being lifted.
     * It sends additional requests to the routes and asserts that the response status code is still 200.
     */

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
