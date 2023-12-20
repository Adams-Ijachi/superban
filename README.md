# A Laravel Package that let's you ban users from your applications

The package provides a middleware called `superban` that you can use to ban users from your application. The middleware checks if the user is banned and if so, it throws a `UserBannedException` which you can catch in your `app/Exceptions/Handler.php` file and redirect the user to a page of your choice.


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
