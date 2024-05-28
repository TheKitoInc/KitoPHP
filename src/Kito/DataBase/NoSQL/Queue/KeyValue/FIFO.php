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
 * @author TheKito
 */
class FIFO extends \Kito\DataBase\NoSQL\Queue\KeyValue
{
    protected const secondaryCounter = 'secondaryCount';

    protected function getSecondaryCounterName(): string
    {
        return $this->getKey(self::secondaryCounter);
    }

    public function dequeue()
    {
        if ($this->isEmpty()) {
            return null;
        }

        $id = $this->backend->increment($this->getSecondaryCounterName());
        $key = $this->getKeyItem($id);
        $item = $this->backend->get($key);
        $this->backend->delete($key);

        return $item;
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function count(): int
    {
        return $this->backend->get($this->getMainCounterName()) - $this->backend->get($this->getSecondaryCounterName());
    }
}
