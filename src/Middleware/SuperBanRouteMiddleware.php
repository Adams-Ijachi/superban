<?php

declare(strict_types=1);

namespace PiusAdams\SuperBan\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use PiusAdams\SuperBan\Exceptions\UserBannedException;
use PiusAdams\SuperBan\Facades\SuperBan;


class SuperBanRouteMiddleware
{
    /**
     * @throws UserBannedException
     */
    public function handle(Request $request, Closure $next,
                           int     $max_number_of_attempts,
                           int     $number_of_minutes,
                           int     $minutes_to_ban )
    {

        $key = $request->user()?->id
            ?? $request->ip() ?? $request->user()?->email;

        if (SuperBan::isBanned($key)) {
            throw new UserBannedException('User is banned');
        }

        $executed = RateLimiter::attempt($key,$max_number_of_attempts - 1,
            function ()  {
                return true;
            },
            $number_of_minutes * 60
        );

        if (!$executed) {
            SuperBan::ban($key, $minutes_to_ban);
        }

        return $next($request);
    }

}
