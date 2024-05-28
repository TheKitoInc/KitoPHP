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

namespace Kito\DataBase\NoSQL\Queue;

use Kito\DataBase\NoSQL\KeyValueInterface;
use Kito\DataBase\NoSQL\QueueInterface;

/**
 * Proxy class for access Memcache or Memcached common functions.
 *
 * @author TheKito
 */
abstract class KeyValue implements QueueInterface
{
    protected const mainCounter = 'Count';

    protected $backend;
    private $queueName;

    protected function getKey(string $suffix): string
    {
        return 'Kito/DataBase/NoSQL/Queue/KeyValueQueue/'.$this->queueName.'/'.$suffix;
    }

    protected function getKeyItem(int $itemPos): string
    {
        return $this->getKey('Item/'.$itemPos);
    }

    protected function getMainCounterName(): string
    {
        return $this->getKey(self::mainCounter);
    }

    public function __construct(KeyValueInterface $backend, string $queueName)
    {
        $this->backend = $backend;
        $this->queueName = $queueName;
    }

    public function enqueue($var): bool
    {
        if ($var === null) {
            return false;
        }

        $count = $this->backend->increment($this->getMainCounterName());

        return $this->backend->set($this->getKeyItem($count), $var);
    }

    public function count(): int
    {
        return $this->backend->get($this->getMainCounterName());
    }
}
