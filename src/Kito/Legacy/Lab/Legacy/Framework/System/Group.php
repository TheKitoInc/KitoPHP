<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 */

/**
 * @author TheKito <blankitoracing@gmail.com>
 */
class Group
{
    public $id = false;
    private $zone = false;

    public static function getGroup($name)
    {
        /*static*/ $cache = []; /* $cache disable because zone and users list have one*/
        if (isset($cache[$name])) {
            return $cache[$name];
        }

        $cache[$name] = new Group($name);

        return $cache[$name];
    }

    private function __construct($name)
    {
        if ($name == 'Root' || $name == 'Admin' || $name == 'Designer' || $name == 'Developer') {
            $system = true;
        } else {
            $system = false;
        }

        $this->zone = Zone::getZoneByName($name, getGroupsZone(), $system);
        $this->id = $this->zone->id;
    }

    public function getName()
    {
        return $this->zone->name;
    }
}
