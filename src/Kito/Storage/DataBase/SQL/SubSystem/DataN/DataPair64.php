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
class DataPair64 extends DataPair
{
    private $tableSrchCol;
    private $maxId = 0;

    public function __construct(&$driver, $tableName, $tablePK, $tableCol, $tableSrchCol = null)
    {
        parent::__construct($driver, $tableName, $tablePK, $tableCol);
        $this->tableSrchCol = $tableSrchCol;
    }

    public function getId($value, $create = true)
    {
        $id = parent::getId(base64_encode($value), $create);

        if ($create && $this->tableSrchCol != null && $id > $this->maxId) {
            try {
                $srchstr = $value;
                $srchstr = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $srchstr);
                $srchstr = addslashes($srchstr);
                if (parent::getDriver()->update(parent::getTableName(), [$this->tableSrchCol => $srchstr], [parent::getTablePK() => $id, $this->tableSrchCol => null], 1)) {
                    $this->maxId = $id;
                }
            } catch (SqlException $e) {
            }
        }

        return $id;
    }

    public function getValue($id)
    {
        return base64_decode(parent::getValue($id));
    }
}
