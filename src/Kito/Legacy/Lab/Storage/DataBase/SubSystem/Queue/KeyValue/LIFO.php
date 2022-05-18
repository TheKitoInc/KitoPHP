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

namespace Kito\DataBase\NoSQL\Queue\KeyValue;

/**
 * Proxy class for access Memcache or Memcached common functions.
 *
 * @author TheKito
 */
class LIFO extends \Kito\DataBase\NoSQL\Queue\KeyValue
{
    public function dequeue()
    {
        if ($this->isEmpty()) {
            return null;
        }

        $id = $this->backend->decrement($this->getMainCounterName()) + 1;

        return $this->backend->get($this->getKeyItem($id));
    }

    public function isEmpty(): bool
    {
        return parent::count() == 0;
    }
}
