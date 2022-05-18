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

namespace Kito\DataBase\KeyValue;

/**
 * Proxy class for access Memcache or Memcached common functions.
 *
 * @author TheKito
 */
interface KeyValueInterface
{
    public function get(string $key);

    public function set(string $key, $var): bool;

    public function delete(string $key): bool;

    public function exists(string $key): bool;

    public function add(string $key, $var): bool;

    public function decrement(string $key, int $initial_value = 0): int;

    public function increment(string $key, int $initial_value = 0): int;
}
