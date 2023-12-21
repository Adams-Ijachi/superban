<?php

declare(strict_types=1);

namespace PiusAdams\SuperBan\Facades;

use Illuminate\Support\Facades\Facade;
use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;

/**
 * @method static bool isBanned($ip)
 * @method static void ban($ip, $minutes_to_ban)
 * @method static getResolvedKey($request)
 * @see \PiusAdams\SuperBan\Services\SuperBanService
 */
class SuperBan extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return SuperBanServiceContract::class;
    }
}
