# SuperBan

The package provides a middleware called `superban` that you can use to ban users from your application. The middleware checks if the user is banned and if so, it throws a `UserBannedException` which you can catch in your `app/Exceptions/Handler.php` file and redirect the user to a page of your choice.

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

You can run the tests with:

```bash
vendor/bin/phpunit
```
