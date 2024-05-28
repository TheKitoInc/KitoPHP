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

namespace Kito\Storage\DataBase\SQL\SubSystem\DataN;

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class DataPair extends DataN
{
    private $tableCol;

    public function __construct(&$driver, $tableName, $tablePK, $tableCol)
    {
        parent::__construct($driver, $tableName, $tablePK);
        $this->tableCol = $tableCol;
    }

    public function getId($value, $create = true)
    {
        return parent::getId([$this->tableCol => trim($value)], $create);
    }

    public function exists($value)
    {
        return parent::exists([$this->tableCol => trim($value)]);
    }

    public function getValue($id)
    {
        $rs = parent::getValue($id);

        return $rs[$this->tableCol];
    }

    public function getItems()
    {
        $RS = parent::getDriver()->select(parent::getTableName(), [parent::getTablePK(), $this->tableCol]);

        $RESULT = [];

        foreach ($RS as $ROW) {
            $RESULT[$ROW[parent::getTablePK()]] = $ROW[$this->tableCol];
        }

        return $RESULT;
    }
}
