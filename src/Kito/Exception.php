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

namespace Kito;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Exception extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        if ($code === 0) {
            $code = crc32(get_called_class());
        }

        parent::__construct($message, $code, $previous);
    }

    public static function throwException(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $class = get_called_class();

        throw new $class($message, $code, $previous);
    }
}
