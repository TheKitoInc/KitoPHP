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
class Zone extends DataThree
{
    private static $tableName = 'BLK_ZONE';
    private static $tablePk = 'ZONE_ID';
    private static $tableValue0 = 'ZONE_PARENT_ID';
    private static $tableValue1 = 'ZONE_NAME_ID';
    private $az;
    private $zn;
    private $zl;

    public function __construct($cnn)
    {
        parent::__construct($cnn, self::$tableName, self::$tablePk, self::$tableValue0, self::$tableValue1);
        $this->az = new AttributeZone($cnn);
        $this->zn = new Name($cnn);
        $this->zl = new ZoneLink($cnn);
    }

    public function getId($pid, $name)
    {
        return parent::getId($pid, $this->zn->getId($name));
    }

    public function getName($id)
    {
        return $this->zn->getValue(parent::getValue1($id));
    }

    public function getPid($id)
    {
        return parent::getValue0($id);
    }

    public function delete($id, $recursive = true)
    {
        $znid = parent::getValue1($id);

        if ($recursive) {
            foreach ($this->getZoneIds($id) as $subid) {
                if (!$this->delete($subid, $recursive)) {
                    return false;
                }
            }
        } else {
            //UPDATE
        }

        foreach ($this->getAZ()->getAttributes($id) as $a_name) {
            if (!$this->getAZ()->remove($id, $a_name)) {
                return false;
            }
        }

        foreach ($this->getZL()->getIds($id) as $id_linked_zone) {
            if (!$this->getZL()->delete($id, $id_linked_zone)) {
                return false;
            }
        }

        if (!parent::delete($id)) {
            return false;
        }

        if (!parent::inUse(self::$tableValue1, $znid)) { //check name node
            $this->zn->delete($znid);
        }

        return true;
    }

    public function getZoneIds($id)
    {
        $t = [];
        $rs = parent::getCnn()->select(self::$tableName, [self::$tablePk], [self::$tableValue0 => $id], 0);
        foreach ($rs as $row) {
            array_push($t, $row[self::$tablePk]);
        }

        return $t;
    }

    public function getAZ()
    {
        return $this->az;
    }

    public function getZL()
    {
        return $this->zl;
    }
}
