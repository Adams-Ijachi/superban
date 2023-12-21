<?php

namespace PiusAdams\SuperBan\Contracts;

use Exception;
use Illuminate\Http\Request;

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
    public function ban($key, int $banTimeInSeconds): void;


    public function getResolvedKey(Request $request): string;

}
