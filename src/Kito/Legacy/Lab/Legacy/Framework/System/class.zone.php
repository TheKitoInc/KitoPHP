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
class Zone
{
    public $id;
    private $id_parent;
    public $name;
    public $system;

    public $driver;

    public $attr_zone;

    public $path = false;

    public static function getZoneByName($name, $parent_zone, $create_as_system = false, $driver = false, $create = true)
    {
        if ($parent_zone != null && !($parent_zone instanceof zone)) {
            return false;
        }

        if ($driver === false) {
            if ($parent_zone != null) {
                $driver = $parent_zone->driver;
            } else {
                $driver = getDBDriver('System');
            }
        }

        if ($driver === false) {
            return false;
        }

        $name = substr($name, 0, 50);

        if ($parent_zone != null) {
            $row = $driver->autoTable('BLK_ZONE', ['ZONE_PARENT_ID' => $parent_zone->id, 'ZONE_NAME' => $name, 'ZONE_SYSTEM' => ($create_as_system ? 'Y' : 'N')], $create);
        } else {
            $row = $driver->autoTable('BLK_ZONE', ['ZONE_PARENT_ID' => '0', 'ZONE_NAME' => $name, 'ZONE_SYSTEM' => 'Y'], $create);
        }

        if ($row === false) {
            return false;
        }

        return Zone::getZone($row['ZONE_ID'], $driver, $row);
    }

    public static function getZone($id, $driver = false, $rs_row = false)
    {
        static $cache = [];
        if (isset($cache[$id])) {
            return $cache[$id];
        }

        if ($driver === false) {
            $driver = getDBDriver('System');
        }

        $cache[$id] = new Zone($id, $driver, $rs_row);

        return $cache[$id];
    }

    private function __construct($id, $driver, $rs_row = false)
    {
        $path = BASE.'/Zones/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->path = $path.'z'.$id.'.';

        $this->driver = $driver;
        if ($rs_row === false) {
            $rs = $this->driver->query("select * from BLK_ZONE where ZONE_ID='".$id."';");

            if ($rs === false) {
                return false;
            }

            if (!$rs->first()) {
                return false;
            }

            $rs_row = $rs->get();
        }

        $this->id = $rs_row['ZONE_ID'];
        $this->id_parent = $rs_row['ZONE_PARENT_ID'];
        $this->name = $rs_row['ZONE_NAME'];
        $this->system = $rs_row['ZONE_SYSTEM'] == 'Y';

        $rs = $this->driver->query("select ZONE_ATTR_VALUE,ZONE_ATTR_ID_ATTR from BLK_ZONE_ATTR where ZONE_ATTR_ID_ZONE='".$this->id."';");

        if ($rs === false) {
            return false;
        }

        if ($rs->first()) {
            while (true) {
                $row = $rs->get();
                //                if(strStartsWith($row["ZONE_ATTR_ID_ATTR"], "S_"))
                //                    $this->attr_zone[$row["ZONE_ATTR_ID_ATTR"]]=unserialize($row["ZONE_ATTR_VALUE"]);
                //                else
                $this->attr_zone[$row['ZONE_ATTR_ID_ATTR']] = $row['ZONE_ATTR_VALUE'];
                if (!$rs->next()) {
                    break;
                }
            }
        }
    }

    private function getFile($type)
    {
        return $this->path.$type;
    }

    public function getText()
    {
        if (!file_exists($this->getFile('txt'))) {
            return false;
        }

        return file_get_contents($this->getFile('txt'));
    }

    public function setText($text)
    {
        return file_put_contents($this->getFile('txt'), $text);
    }

    public function getParent()
    {
        if ($this->id_parent == 0) {
            return false;
        }

        return Zone::getZone($this->id_parent, $this->driver);
    }

    public function getParents()
    {
        $list = [];
        $list[0] = '[NO PARENT]';
        foreach (getRootZones() as $zone) {
            $this->parentloop($zone, $list);
        }

        return $list;
    }

    private function parentloop($zone, &$list)
    {
        if ($zone->id == $this->id) {
            return;
        }

        $list[$zone->id] = $zone;

        foreach ($zone->getChild() as $ch_zone) {
            $this->parentloop($ch_zone, $list);
        }
    }

    public function getChild()
    {
        $rs = $this->driver->query("select * from BLK_ZONE where ZONE_PARENT_ID='".$this->id."';");

        if ($rs === false) {
            return false;
        }

        $ch = [];

        if ($rs->first()) {
            while (true) {
                $row = $rs->get();
                $ch[$row['ZONE_ID']] = Zone::getZone($row['ZONE_ID'], $this->driver);

                if (!$rs->next()) {
                    break;
                }
            }
        }

        return $ch;
    }

    public function getAttributes($all = false, $pair = false)
    {
        if ($all) {
            if ($this->system) {
                $rs = $this->driver->query("select ATTR_ID,ATTR_NAME from BLK_ATTR where ATTR_SYSTEM='Y';");
            } else {
                $rs = $this->driver->query('select ATTR_ID,ATTR_NAME from BLK_ATTR;');
            }
        } else {
            $query = '';
            $rs = $this->driver->query("select ZONE_ATTR_ID_ATTR from BLK_ZONE_ATTR where ZONE_ATTR_ID_ZONE='".$this->id."';");
            if ($rs === false) {
                return false;
            }

            if ($rs->first()) {
                while (true) {
                    if ($query != '') {
                        $query .= ' or';
                    }

                    $row = $rs->get();
                    $query .= " ATTR_ID='".$row['ZONE_ATTR_ID_ATTR']."'";

                    if (!$rs->next()) {
                        break;
                    }
                }
            }

            if ($query == '') {
                return [];
            }
            $rs = $this->driver->query("select ATTR_ID,ATTR_NAME from BLK_ATTR where $query;");
        }

        if ($rs === false) {
            return false;
        }

        $attr = [];

        if ($rs->first()) {
            while (true) {
                $row = $rs->get();
                if ($pair) {
                    $attr[$row['ATTR_NAME']] = $this->get($row['ATTR_NAME'], null);
                } else {
                    $attr[$row['ATTR_ID']] = $row['ATTR_NAME'];
                }

                if (!$rs->next()) {
                    break;
                }
            }
        }

        return $attr;
    }

    public function get($Attribute, $default)
    {
        $attr = getAttr($this->driver, $Attribute, true);
        $attr_id = $attr->id;

        if (isset($this->attr_zone[$attr_id])) {
            return $this->attr_zone[$attr_id];
        } else {
            $this->set($Attribute, $default);

            return $default;
        }
    }

    public function set($Attribute, $value)
    {
        //     if(!is_string($value))
        //     {
        //         $Attribute="S_".$Attribute;
        //         $value=serialize($value);
        //     }

        $attr = getAttr($this->driver, $Attribute, true);
        $attr_id = $attr->id;

        if (isset($this->attr_zone[$attr_id]) && $this->attr_zone[$attr_id] == $value) {
            return true;
        }

        if (isset($this->attr_zone[$attr_id])) {
            $cmd = "update BLK_ZONE_ATTR set ZONE_ATTR_VALUE='".$value."' where ZONE_ATTR_ID_ATTR='".$attr_id."' and ZONE_ATTR_ID_ZONE='".$this->id."';";
        } else {
            $cmd = "insert into BLK_ZONE_ATTR (ZONE_ATTR_ID_ATTR,ZONE_ATTR_ID_ZONE,ZONE_ATTR_VALUE) values ('".$attr_id."','".$this->id."','".$value."');";
        }

        if ($this->driver->command($cmd)) {
            $this->attr_zone[$attr_id] = $value;

            return true;
        }

        //     if($this->driver->command("delete from BLK_ZONE_ATTR where ZONE_ATTR_ID_ATTR='".$attr_id."' and ZONE_ATTR_ID_ZONE='".$this->id."';"))
        //        if($this->driver->command("insert into BLK_ZONE_ATTR (ZONE_ATTR_ID_ATTR,ZONE_ATTR_ID_ZONE,ZONE_ATTR_VALUE) values ('".$attr_id."','".$this->id."','".$value."');"))
        //        {
        //            $this->attr_zone[$attr_id]=$value;
        //            return true;
        //        }
        return false;
    }

    public function delete($recursive = false)
    {
        if (!$recursive) {
            if (!$this->driver->command("update BLK_ZONE set ZONE_PARENT_ID='".$this->id_parent."' where ZONE_PARENT_ID='".$this->id."';")) {
                return false;
            } else {
                foreach ($this->getChild() as $zc) {
                    if (!$zc->delete(true)) {
                        return false;
                    }
                }
            }
        }

        if (!$this->driver->command("delete from BLK_ZONE_ATTR where ZONE_ATTR_ID_ZONE='".$this->id."';")) {
            return false;
        }

        if (!$this->driver->command("delete from BLK_ZONE where ZONE_ID='".$this->id."';")) {
            return false;
        }

        return true;
    }

    public function getLink()
    {
        $title = $this->getTitle();

        return "<a class='zone' href='./?Zone=".$this->id.'&Session='.getSessionId()."' rel='$title'>".$title.'</a>';
    }

    public function __toString()
    {
        if (file_exists($this->getFile('txt'))) {
            return $this->getText();
        } else {
            return $this->name;
        }
    }

    public function setParent($parent_zone)
    {
        if (!$this->driver->command("update BLK_ZONE set ZONE_PARENT_ID='".$parent_zone->id."' where ZONE_ID='".$this->id."'")) {
            return false;
        }

        $this->id_parent = $parent_zone->id;

        return true;
    }

    public function getList($name)
    {
        return ElementsList::getList($name, $this);
    }

    public function getTitle()
    {
        return $this->get('Title', $this->name);
    }

    public function equals($object)
    {
        if (!($object instanceof zone)) {
            return false;
        }

        return $object->id = $this->id;
    }
}
