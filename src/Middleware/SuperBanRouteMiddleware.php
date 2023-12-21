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
     * Handle the incoming request and perform super ban checks.
     *
     * @param Request $request The incoming request.
     * @param Closure $next The next middleware closure.
     * @param int $max_number_of_attempts The maximum number of attempts allowed within the specified interval.
     * @param int $number_of_minutes The interval in minutes within which the maximum number of attempts is allowed.
     * @param int $minutes_to_ban The duration in minutes for which the user should be banned if the maximum number of attempts is exceeded.
     * @return mixed
     * @throws UserBannedException If the user is banned.
     */

    /**
     * @throws UserBannedException
     */
    public function handle(Request $request, Closure $next,
                           int     $max_number_of_attempts = 60,
                           int     $number_of_minutes = 1,
                           int     $minutes_to_ban = 60 )
    {

        $rateLimiterKey = SuperBan::getResolvedKey($request);

        $superBanKey = $rateLimiterKey . '_superban';

        $interval_in_seconds = $number_of_minutes * 60;
        $ban_interval_in_seconds = $minutes_to_ban * 60;

        if (SuperBan::isBanned($superBanKey)) {
            throw new UserBannedException('User is banned');
        }

        $executed = RateLimiter::attempt($rateLimiterKey,$max_number_of_attempts , fn () => true,
            $interval_in_seconds
        );


        if (!$executed) {
            SuperBan::ban($superBanKey, $ban_interval_in_seconds);
        }

        return $next($request);
    }

}
