<?php

/**
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

/**
 *
 * @author The TheKito < blankitoracing@gmail.com >
 */
class BLKTouchTable
{
    private $SQLConnection = null;
    private $table = null;
    private $time = null;

    public function __construct($SQLConnection, $table)
    {
        if (!$SQLConnection instanceof MySql) {
            throw new Exception('Invalid MySql Object');
        }

        $this->SQLConnection = $SQLConnection;
        $this->table = $table;
    }

    public function getGCTime()
    {
        if ($this->time === null) {
            $this->time = time();
        }

        return $this->time;
    }

    public function touch($data)
    {
        $this->SQLConnection->autoUpdate($this->table, array('gc' => $this->getGCTime()), $data);
        $data['created'] = null;
        $this->SQLConnection->update($this->table, array('created' => time()), $data);
    }

    public function purge($filter = array())
    {
        $filter['!gc'] = $this->getGCTime();
        $this->SQLConnection->delete($this->table, $filter);
        $this->time = null;
    }
}
