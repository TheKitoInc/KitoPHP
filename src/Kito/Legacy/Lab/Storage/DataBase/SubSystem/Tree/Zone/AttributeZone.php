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
class AttributeZone extends DataThree
{
    private static $tableName = 'BLK_ZONE_ATTRIBUTE';
    private static $tablePk = 'ZONE_ATTRIBUTE_ID';
    private static $tableValue0 = 'ZONE_ATTRIBUTE_ZONE_ID';
    private static $tableValue1 = 'ZONE_ATTRIBUTE_ATTRIBUTE_ID';
    private $a;

    public function __construct($cnn)
    {
        parent::__construct($cnn, self::$tableName, self::$tablePk, self::$tableValue0, self::$tableValue1);
        $this->a = new Attribute($cnn);
    }

    public function set($idz, $name, $value)
    {
        $lid = $this->getIdMagic($idz, $name);

        if ($this->getValue($lid) == $value) {
            return true;
        }

        $this->getId($idz, $name, $value);
        $this->delete($lid);
    }

    public function get($idz, $name, $default = null)
    {
        $lid = $this->getIdMagic($idz, $name);
        if ($lid != null) {
            return $this->getValue($lid);
        } else {
            if ($default != null) {
                $this->getId($idz, $name, $value);
            }

            return $default;
        }
    }

    public function remove($idz, $name)
    {
        return $this->delete($this->getIdMagic($idz, $name));
    }

    private function getIdMagic($idz, $name)
    {
        $rs = $this->getCnn()->select(self::$tableName, [self::$tablePk, self::$tableValue1], [self::$tableValue0 => $idz], 0);

        foreach ($rs as $row) {
            if ($this->a->getName($row[self::$tableValue1]) == strtoupper($name)) {
                return $row[self::$tablePk];
            }
        }

        return null;
    }

    public function getAttributes($idz, $limit = null)
    {
        $a = [];
        $rs = $this->getCnn()->select(self::$tableName, [self::$tableValue1], [self::$tableValue0 => $idz], 0);

        foreach ($rs as $row) {
            if ($limit != null) {
                if ($limit <= 0) {
                    break;
                } else {
                    $limit--;
                }
            }

            array_push($a, $this->a->getName($row[self::$tableValue1]));
        }

        return $a;
    }

    protected function getId($idz, $name, $value)
    {
        return parent::getId($idz, $this->a->getId($name, $value));
    }

    private function getName($id)
    {
        if ($id == null) {
            return null;
        }

        return $this->a->getName(parent::getValue1($id));
    }

    protected function getValue($id)
    {
        if ($id == null) {
            return null;
        }

        return $this->a->getValue(parent::getValue1($id));
    }

    protected function delete($id)
    {
        if ($id == null) {
            return false;
        }

        $aid = parent::getValue1($id);

        if (!parent::delete($id)) {
            return false;
        }

        if (!parent::inUse(self::$tableValue1, $aid)) { //check node
            $this->a->delete($aid);
        }

        return true;
    }
}
