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
abstract class Driver
{
    public $zone = false;

    public function getTablesZone()
    {
        return getZone(getDBDriver('System'), 'Tables', $this->zone, true);
    }

    public function getColZone($table, $col)
    {
        return getZone(getDBDriver('System'), $col, getTableZone($table), true);
    }

    public function getTableZone($table)
    {
        if (!$this->existTable($table)) {
            return false;
        }

        return getZone(getDBDriver('System'), $table, $this->getTablesZone(), true);
    }

    public function autoTable($table, $cols, $create = true)
    {
        $query = '';

        $insert = '';
        $insert2 = '';
        foreach ($cols as $name => $value) {
            if ($insert != '') {
                $insert .= ',';
            }
            $insert .= $name;

            if ($insert2 != '') {
                $insert2 .= ',';
            }
            $insert2 .= "'".$value."'";

            if ($query != '') {
                $query .= ' and ';
            } else {
                $query .= ' ';
            }

            $query .= $name."='".$value."'";
        }
        $query = "select * from $table where".$query.';';
        $insert = "insert into $table (".$insert.') values ('.$insert2.');';

        $rs = $this->query($query);
        if ($rs === false) {
            return false;
        }

        if ($rs->first()) {
            return $rs->get();
        }

        if ($create) {
            if ($this->command($insert)) {
                return $this->autoTable($table, $cols);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Data

    /**
     * execute query without any resultset.
     *
     * @return bool
     */
    abstract public function command($query);

    /**
     * execute quer.
     *
     * @return IResultSet
     */
    abstract public function query($query);

    //Structure

    /**
     * List database tables.
     *
     * @return array<string> tables name
     */
    abstract public function getTables();

    /**
     * List table cols.
     *
     * @return array<string,array<string,string>> col name, {Attribute,Value}
     */
    abstract public function getTableCols($table);

    /**
     * Create/Update/Remove table.
     *
     * @param  tablename, array<String,array<Attribute,Value>> Cols
     *
     * @return bool
     */
    abstract public function alterTable($table, $cols);

    /**
     * check if exist table.
     *
     * @param  tablename
     *
     * @return bool
     */
    abstract public function existTable($table);

    abstract public function getStats();

    abstract public function getPrimaryKey($table);
}
