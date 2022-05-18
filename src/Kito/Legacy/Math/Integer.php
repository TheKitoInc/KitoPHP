<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 */
declare(strict_types=1);

namespace Kito\Math;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Integer
{
    public static function mergeInteger(int $x, int $y): int
    {
        return ($x + $y) * ($x + $y + 1) / 2 + $y;
    }

    public static function splitInteger(int $z): array
    {
        $w = floor((sqrt(8 * $z + 1) - 1) / 2);
        $t = ($w * $w + $w) / 2;
        $y = $z - $t;
        $x = $w - $y;

        return [$x, $y];
    }
}
