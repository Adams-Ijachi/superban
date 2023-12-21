<?php
declare(strict_types=1);

namespace PiusAdams\SuperBan\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Cache\Repository as Cache;
use PiusAdams\SuperBan\Exceptions\UserBannedException;
use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;

/**
 * SuperBanService class
 *
 * This class provides functionality for banning and checking if a key is banned.
 * It implements the SuperBanServiceContract interface.
 *
 * @package PiusAdams\SuperBan\Services
 */

class SuperBanService implements SuperBanServiceContract
{
    use InteractsWithTime;
    /**
     * The cache store implementation.
     *
     * @var Cache
     */

    const EMAIL_KEY = 'email';
    const IP_KEY = 'ip';
    const USER_ID_KEY = 'id';

    private SuperBanCacheService $cache;


    public function __construct(SuperBanCacheService $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Determine if the given key is banned.
     * @param $key
     * @return bool
     */
    final public function isBanned($key): bool
    {
        $seconds_user_should_be_banned = $this->cache->get($key); // in unix


        if (!is_null($seconds_user_should_be_banned) >= $this->currentTime()) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    final public function ban($key,int $banTimeInSeconds): void
    {

        $added = $this->cache->add($key, $this->availableAt($banTimeInSeconds), $banTimeInSeconds);

        if (!$added) {
            throw new UserBannedException('User is banned', 403);
        }

    }


    final public function getResolvedKey(Request $request): string
    {
        $identity_key = config('superban.identity_key');

        if ($identity_key == self::EMAIL_KEY && $request->user()) {
            $identity_key = $request->user()->email;
        } elseif ($identity_key == self::USER_ID_KEY && $request->user()) {
            $identity_key = $request->user()->id;
        } elseif ($identity_key == self::IP_KEY) {
            $identity_key = $request->getClientIp();
        }

        $key = $identity_key ."_". $request->route()->uri() ."_". $request->method();

        return md5($key);

    }
}
