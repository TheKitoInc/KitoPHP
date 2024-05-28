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
class ElementsList
{
    public $zone = false;
    public $name = false;
    public $list = [];
    public $attr = false;

    public static function getList($name, $zone)
    {
        static $cache = [];
        $cache_key = $zone->id.$name;
        if (isset($cache[$cache_key])) {
            return $cache[$cache_key];
        }

        $cache[$cache_key] = new ElementsList($zone, $name);

        return $cache[$cache_key];
    }

    private function __construct($zone, $name)
    {
        $this->zone = $zone;
        $this->name = $name;

        $this->attr = getAttr($this->zone->driver, $name);

        $rs = $this->zone->driver->query("select BLK_ZONE_LIST_VALUE from BLK_ZONE_LIST where BLK_ZONE_LIST_ATTR_ID='".$this->attr->id."' and BLK_ZONE_LIST_ZONE_ID='".$this->zone->id."';");

        if ($rs === false) {
            return false;
        }

        if ($rs->first()) {
            while (true) {
                $row = $rs->get();
                array_push($this->list, $row['BLK_ZONE_LIST_VALUE']);
                if (!$rs->next()) {
                    break;
                }
            }
        }
    }

    public function add($value)
    {
        foreach ($this->list as $key => $value_) {
            if ($value == $value_) {
                return true;
            }
        }

        if (!$this->zone->driver->command("insert into BLK_ZONE_LIST (BLK_ZONE_LIST_ATTR_ID,BLK_ZONE_LIST_ZONE_ID,BLK_ZONE_LIST_VALUE) values ('".$this->attr->id."','".$this->zone->id."','".$value."');")) {
            return false;
        }

        array_push($this->list, $value);

        return true;
    }

    public function remove($value)
    {
        if (!$this->zone->driver->command("delete from BLK_ZONE_LIST where BLK_ZONE_LIST_ATTR_ID='".$this->attr->id."' and BLK_ZONE_LIST_ZONE_ID='".$this->zone->id."' and BLK_ZONE_LIST_VALUE='".$value."');")) {
            return false;
        }

        foreach ($this->list as $key => $value_) {
            if ($value == $value_) {
                unset($this->list[$key]);
            }
        }

        return true;
    }

    public function get()
    {
        return $this->list;
    }

    private $cont = -1;

    public function getNext()
    {
        $this->cont++;
        if (!isset($this->list[$this->cont])) {
            $this->cont = 0;
        }

        if (!isset($this->list[$this->cont])) {
            return false;
        }

        return $this->list[$this->cont];
    }
}
