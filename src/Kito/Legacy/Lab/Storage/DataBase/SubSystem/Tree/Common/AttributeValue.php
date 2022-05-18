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
class AttributeValue extends DataPair64
{
    private static $tableName = 'BLK_ATTRIBUTE_VALUE';
    private static $tablePk = 'ATTRIBUTE_VALUE_ID';
    private static $tableValue = 'ATTRIBUTE_VALUE_VALUE';

    public function __construct($cnn)
    {
        parent::__construct($cnn, self::$tableName, self::$tablePk, self::$tableValue);
    }

    public function getId($value)
    {
        return parent::getId($value);
    }

    public function getValue($id)
    {
        return parent::getValue($id);
    }
}
