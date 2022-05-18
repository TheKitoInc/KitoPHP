<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 */

namespace Kito\Http\Server;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Cookies
{
    public static function set(string $name, string $value, int $secondsTTL = 3600): void
    {
        setcookie($name, $value, time() + $secondsTTL, '/', '', true, true);
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

    public static function touch(string $name, int $secondsTTL = 3600): void
    {
        self::set($name, self::get($name), $secondsTTL);
    }
}
