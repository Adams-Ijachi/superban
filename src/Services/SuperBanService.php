<?php
declare(strict_types=1);

namespace PiusAdams\SuperBan\Services;

use Exception;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Cache\Repository as Cache;
use PiusAdams\SuperBan\Exceptions\UserBannedException;
use PiusAdams\SuperBan\Contracts\SuperBanServiceContract;
use Psr\SimpleCache\InvalidArgumentException;

class SuperBanService implements SuperBanServiceContract
{
    use InteractsWithTime;
    /**
     * The cache store implementation.
     *
     * @var Cache
     */

    protected Cache $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Determine if the given key is banned.
     * @param $key
     * @return bool
     * @throws InvalidArgumentException
     */
    final public function isBanned($key): bool
    {
        $key = $this->getBannedObjectKey($key);

        $seconds_user_should_be_banned = $this->cache->get($key); // in unix

        if (!is_null($seconds_user_should_be_banned) >= $this->currentTime()) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    final public function ban($key, $minutes_to_ban): void
    {

        $key = $this->getBannedObjectKey($key);

        $added = $this->cache->add($key,
            $this->availableAt($minutes_to_ban * 60),
        $minutes_to_ban * 60);

        if (!$added) {
            throw new UserBannedException('User is banned', 403);
        }

    }

    /**
     * @throws InvalidArgumentException
     */
    final public function isExpired($key): bool
    {
        $seconds_user_should_be_banned = $this->cache->get($key); // in unix

        if ($seconds_user_should_be_banned === null) {
            return true;
        }

        if ($seconds_user_should_be_banned >= $this->currentTime()) {
            return true;
        }

        return false;
    }

    public function getBannedObjectKey($key): string
    {
        return 'superban_' . $key;
    }

}
