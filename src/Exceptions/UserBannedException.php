<?php

namespace PiusAdams\SuperBan\Exceptions;

use Exception;

class UserBannedException extends Exception
{
    protected $code = 403;

}
