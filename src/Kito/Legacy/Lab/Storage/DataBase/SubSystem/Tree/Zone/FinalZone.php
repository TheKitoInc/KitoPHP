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

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class FinalZone
{
    private $z;
    private $id;

    public function getId()
    {
        return $this->id;
    }

    private function __construct(&$z, $id)
    {
        $this->z = $z;
        $this->id = $id;
    }

    public static function getRoot($cnn)
    {
        return new FinalZone(new Zone($cnn), null);
    }

    public function get($name)
    {
        return new FinalZone($this->z, $this->z->getId($this->id, $name));
    }

    public static function getGlobalMant($parent)
    {
        if ($parent === false) {
            return false;
        }

        $t = $parent;
        $last = $parent;

        $count = 999999999;

        while ($t !== false) {
            //            if($t->getAttribute("CountMant")->get(0)<$count)
            //            {
            $last = $t;
            //                $count=$t->getAttribute("CountMant")->get(0);
            //            }

            $t = $t->getMant();
        }

        return $last;
    }

    public function getMant()
    {
        $t = $this->getZones();

        $lzone = false;
        $ltime = round(microtime(true), 0) + 10;

        foreach ($t as $zzz) {
            $time = $zzz->getAttribute('TimeMant')->get(0);

            if ($time < $ltime) {
                $ltime = $time;
                $lzone = $zzz;
            }
        }

        if ($lzone !== false) {
            $lzone->getAttribute('TimeMant')->set(round(microtime(true), 0));
        }

        return $lzone;
    }

    public function getParent()
    {
        if ($this->id == null) {
            return null;
        }

        return new FinalZone($this->z, $this->z->getPid($this->id));
    }

    public function getName()
    {
        if ($this->id == null) {
            return null;
        }

        return $this->z->getName($this->id);
    }

    public function getZones($limit = null)
    {
        $t = [];

        foreach ($this->z->getZoneIds($this->id) as $sid) {
            if ($limit != null) {
                $limit--;
            }

            if ($limit != null && $limit >= 0) {
                $t[$sid] = new FinalZone($this->z, $sid);
            }
        }

        return $t;
    }

    public function delete($recursive = true)
    {
        if ($this->id == null) {
            return false;
        }

        return $this->z->delete($this->id, $recursive);
    }

    public function getAttribute($name)
    {
        if ($this->id == null) {
            return null;
        }

        return new FinalAttribute($this->z->getAZ(), $this->id, $name);
    }

    public function getAttributes($limit = null)
    {
        if ($this->id == null) {
            return [];
        }

        return $this->z->getAZ()->getAttributes($this->id, $limit);
    }

    public function link($other_zone)
    {
        if ($this->id == null) {
            return false;
        }

        return $this->z->getZL()->set($this->id, $other_zone->id) != 0;
    }

    public function unLink($other_zone)
    {
        if ($this->id == null) {
            return false;
        }

        return $this->z->getZL()->delete($this->id, $other_zone->id);
    }

    public function getLinks()
    {
        $t = [];

        if ($this->id == null) {
            foreach ($this->z->getZL()->gets($this->id) as $idz) {
                $t[$idz] = new FinalZone($this->z, $idz);
            }
        }

        return $t;
    }
}
