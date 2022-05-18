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

namespace Kito\KeyValue;

use Kito\LibraryNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Proxy class for access Memcache or Memcached common functions.
 *
 * @author TheKito
 */
class Memcache implements KeyValueInterface, ContainerInterface
{
    private $proxy = null;
    private $keyPrefix = null;

    public function __construct($keyPrefix = null)
    {
        if (class_exists('\Memcached', false)) {
            $this->proxy = new \Memcached();
        } elseif (class_exists('\Memcache', false)) {
            $this->proxy = new \Memcache();
        } else {
            throw new LibraryNotFoundException('Memcache|Memcached');
        }

        $this->keyPrefix = $keyPrefix;
    }

    private function parseKey(string $key): string
    {
        if (isset($this->keyPrefix)) {
            return $this->keyPrefix.$key;
        }

        return $key;
    }

    public function addServer(string $host, int $port = 11211): bool
    {
        return $this->proxy->addServer($host, $port);
    }

    public function flush(): bool
    {
        return $this->proxy->flush();
    }

    public function decrement(string $key, int $initial_value = 0): int
    {
        $_ = $this->parseKey($key);
        $this->proxy->add($_, $initial_value);

        return $this->proxy->decrement($_);
    }

    public function increment(string $key, int $initial_value = 0): int
    {
        $_ = $this->parseKey($key);
        $this->proxy->add($_, $initial_value);

        return $this->proxy->increment($_);
    }

    public function get(string $key): ?string
    {
        $_ = $this->proxy->get($this->parseKey($key));

        if ($_ === false) {
            return null;
        }

        return $_;
    }

    public function set(string $key, $var): bool
    {
        return $this->proxy->set($this->parseKey($key), $var);
    }

    public function delete(string $key): bool
    {
        return $this->proxy->delete($this->parseKey($key));
    }

    public function exists(string $key): bool
    {
        return $this->proxy->get($this->parseKey($key)) !== false;
    }

    public function add(string $key, $var): bool
    {
        return $this->proxy->add($this->parseKey($key), $var);
    }
}
