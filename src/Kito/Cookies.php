<?php

namespace Kito;

class Cookies
{

    public static function set(string $name, string $value, int $secondsTTL = 3600): void
    {
        setcookie($name, $value, time() + $secondsTTL, "/", ".", true, true);
    }

    public static function get(string $name): ?string
    {
        global $_COOKIE;
        if (isset($_COOKIE[$name])) {
            return  $_COOKIE[$name];
        } else {
            return null;
        }
    }

    public static function touch(string $name,int $secondsTTL = 3600): void
    {
        self::set($name, self::get($name), $secondsTTL);
    }
}
