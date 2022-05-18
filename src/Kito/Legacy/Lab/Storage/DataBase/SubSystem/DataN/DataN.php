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
abstract class DataN
{
    private $driver;
    private $tableName;
    private $tablePK;
    private $cache = [];

    private static function compareArray($data0, $data1)
    {
        foreach ($data0 as $key0 => $value0) {
            if (!array_key_exists($key0, $data1)) {
                return false;
            }

            if ($data1[$key0] != $value0) {
                return false;
            }
        }

        return true;
    }

    protected function __construct(&$driver, $tableName, $tablePK)
    {
        $this->driver = $driver;
        $this->tableName = $tableName;
        $this->tablePK = $tablePK;
    }

    protected function getId($data, $create = true)
    {
        foreach ($this->cache as $key => $value) {
            if (self::compareArray($data, $value) === true) {
                return $key;
            }
        }

        $rows = [];
        array_push($rows, $this->tablePK);
        foreach ($data as $key => $tmp) {
            array_push($rows, $key);
        }

        $rs = $this->driver->autoTable($this->tableName, $data, $rows, $create);

        if ($rs == null) {
            return null;
        }

        $pk = $rs[$this->tablePK];
        unset($rs[$this->tablePK]);

        $this->cache[$pk] = $rs;

        return $pk;
    }

    protected function exists($data)
    {
        foreach ($this->cache as $key => $value) {
            if (self::compareArray($data, $value) === true) {
                return true;
            }
        }

        $rows = [];
        array_push($rows, $this->tablePK);
        foreach ($data as $key => $tmp) {
            array_push($rows, $key);
        }

        $rs = $this->driver->select($this->tableName, $rows, $data, 1);

        if (count($rs) == 0) {
            return false;
        }

        $rs = $rs[0];

        $pk = $rs[$this->tablePK];
        unset($rs[$this->tablePK]);

        $this->cache[$pk] = $rs;

        return true;
    }

    protected function getValue($id)
    {
        if ($id === null) {
            return null;
        }

        if (!is_numeric($id) && !is_string($id)) {
            SqlToolException::throwInvalidPkException($this->tableName, $this->tablePK, $id);
        }

        if (isset($this->cache[$id])) {
            return $this->cache[$id];
        }

        $rs = $this->driver->select($this->tableName, [], [$this->tablePK => $id], 1);

        if (count($rs) == 0) {
            SqlToolException::throwPkNotFoundException($this->tableName, $this->tablePK, $id);
        }

        $rs = $rs[0];
        unset($rs[$this->tablePK]);

        $this->cache[$id] = $rs;

        return $rs;
    }

    public function delete($id)
    {
        $this->driver->delete($this->tableName, [$this->tablePK => $id], 0);
        unset($this->cache[$id]);

        return true;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getTablePK()
    {
        return $this->tablePK;
    }

    public function select($col = [], $where = [], $limit = 100)
    {
        return $this->driver->select($this->tableName, $col, $where, $limit);
    }

    public function count($where = [])
    {
        return $this->driver->count($this->tableName, $where);
    }
}
