<?php


if (!function_exists('covert_minutes_to_seconds')) {

    function covert_minutes_to_seconds($minutes): float|int
    {
        return $minutes * 60;
    }

}
