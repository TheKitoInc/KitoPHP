<?php

/*
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
 * @author The TheKito < blankitoracing@gmail.com >
 */
class Attribute extends DataThree
{
    private static $tableName = 'BLK_ATTRIBUTE';
    private static $tablePk = 'ATTRIBUTE_ID';
    private static $tableValue0 = 'ATTRIBUTE_NAME_ID';
    private static $tableValue1 = 'ATTRIBUTE_VALUE_ID';
    private $an;
    private $av;

    public function __construct($cnn)
    {
        parent::__construct($cnn, self::$tableName, self::$tablePk, self::$tableValue0, self::$tableValue1);
        $this->an = new AttributeName($cnn);
        $this->av = new AttributeValue($cnn);
    }

    public function getId($name, $value)
    {
        return parent::getId($this->an->getId($name), $this->av->getId($value));
    }

    public function getName($id)
    {
        return $this->an->getValue(parent::getValue0($id));
    }

    public function getValue($id)
    {
        return $this->av->getValue(parent::getValue1($id));
    }

    public function delete($id)
    {
        $anid = parent::getValue0($id);
        $avid = parent::getValue1($id);

        if (!parent::delete($id)) {
            return false;
        }

        if (!parent::inUse(self::$tableValue0, $anid)) {
            $this->an->delete($anid);
        }

        if (!parent::inUse(self::$tableValue1, $avid)) {
            $this->av->delete($avid);
        }

        return true;
    }
}
