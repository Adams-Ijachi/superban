# SuperBan

The package provides a middleware called `superban` that you can use to ban users from your application. The middleware checks if the user is banned and if so, it throws a `UserBannedException` which you can catch in your `app/Exceptions/Handler.php` file and redirect the user to a page of your choice.

## Installation

You can install the package via composer:

```bash
composer require piusadams/superban
```

You can manually add the service provider to the `providers` array in `config/app.php`:

```php
'providers' => [
    // ...
    PiusAdams\SuperBan\Providers\SuperBanServiceProvider::class,
];
```

Discover the package:
You can publish the config file with:

```bash
php artisan vendor:publish --provider="PiusAdams\SuperBan\Providers\SuperBanServiceProvider"  
```
This is the contents of the published config file:

```php
return [
    'cache_driver' => env('SUPERBAN_CACHE_DRIVER', 'file'),
    'identity_key' => env('SUPERBAN_IDENTITY_KEY', 'ip'),
];
```

The `cache_driver` option is the cache store that will be used to store the keys. The default is `file` but you can change it to any of the cache stores that Laravel supports.

The `identity_key` option is the key that will be used to identify the user. The default is `ip` but you can change it to `email` or `id`, and if the user is logged in, the middleware will use the user's email or ID to identify the user.

## How it works

The laravel `RateLimiter` class is used by the middleware to count how many times the user tries to access the resource within a certain time period. If the user goes beyond the limit, the middleware creates a key based on the user's Email, ID or IP address and saves it in the cache for that time period. The middleware then looks for the key in the cache and if it finds it, it raises a `UserBannedException`.

## Example

The `superban` middleware takes 3 parameters:

1. The number of tries the user has before he gets banned
2. The number of minutes the user can try the route before he gets banned
3. The number of minutes the user is banned

```php
Route::get('/home', 'HomeController@index')->middleware('superban:2,3,5');
```

In the above example, the user can try to access the `/home` route 2 times in 3 minutes before he gets banned for 5 minutes.

## Testing

```bash
vendor/bin/phpunit
```

The command above will run the tests for the package.

1. `testSuperBanMiddlewareBansUsersAfterTries` - This test checks if the middleware bans the user after the number of tries is exceeded.
2. `testSuperBanMiddlewareLiftsBanAfterBanTime` - This test checks if the middleware lifts the ban after the ban time is exceeded.
3. `testSuperBanMiddlewareWorksForMultipleRoutes` - This test checks if the middleware works for multiple routes.
4. `testSuperBanMiddlewareWorksForMultipleRoutesAndLiftsBanAfterBanTime` - This test checks if the middleware works for multiple routes and lifts the ban after the ban time is exceeded.
5. `testCacheStoreIsSet` - This test checks if the cache store is set.
6. `testFileCacheStoreIsUsed` - This test checks if the file cache store is used.
7. `testSuperBanServiceIsBannedReturnsTrue` - This test checks if the `isBanned` method of the `SuperBanService` class returns true when the user is banned.
8. `testSuperBanServiceIsBannedReturnsFalse` - This test checks if the `isBanned` method of the `SuperBanService` class returns false when the user is not banned.

## Exceptions

The package throws 2 exceptions:

   1. `UserBannedException` - This exception is thrown when the user is banned with a status code of 403.
