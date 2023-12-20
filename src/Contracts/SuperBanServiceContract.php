<?php

namespace PiusAdams\SuperBan\Contracts;

use Exception;

interface SuperBanServiceContract
{

    /**
     * Determine if the given key is banned.
     * @param $key
     * @return bool
     */
    public function isBanned($key): bool;

    /**
     * @throws Exception
     */
    public function ban($key, $minutes_to_ban): void;


    public function isExpired($key): bool;

    public function getBannedObjectKey($key): string;

}
