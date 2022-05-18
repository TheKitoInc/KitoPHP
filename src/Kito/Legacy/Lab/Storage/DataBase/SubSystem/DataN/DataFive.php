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
class DataFive extends DataN
{
    private $tableCol0;
    private $tableCol1;
    private $tableCol2;
    private $tableCol3;

    public function __construct(&$driver, $tableName, $tablePK, $tableCol0, $tableCol1, $tableCol2, $tableCol3)
    {
        parent::__construct($driver, $tableName, $tablePK);
        $this->tableCol0 = $tableCol0;
        $this->tableCol1 = $tableCol1;
        $this->tableCol2 = $tableCol2;
        $this->tableCol3 = $tableCol3;
    }

    public function getId($value0, $value1, $value2, $value3, $create = true)
    {
        return parent::getId([$this->tableCol0 => $value0, $this->tableCol1 => $value1, $this->tableCol2 => $value2, $this->tableCol3 => $value3], $create);
    }

    public function getValue0($id)
    {
        $rs = parent::getValue($id);

        return $rs[$this->tableCol0];
    }

    public function getValue1($id)
    {
        $rs = parent::getValue($id);

        return $rs[$this->tableCol1];
    }

    public function getValue2($id)
    {
        $rs = parent::getValue($id);

        return $rs[$this->tableCol2];
    }

    public function getValue3($id)
    {
        $rs = parent::getValue($id);

        return $rs[$this->tableCol3];
    }
}
