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

namespace Kito\SQL;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
abstract class SQL implements SQLInterface
{
    final public function getArray($table, $column, $where = []): array
    {
        $r = [];

        foreach ($this->select($table, [$column], $where) as $ROW) {
            array_push($r, $ROW[$column]);
        }

        return $r;
    }

    final public function getHashMap($table, $columnKey, $columnValue, $where = []): array
    {
        $r = [];

        foreach ($this->select($table, [$columnKey, $columnValue], $where) as $ROW) {
            $r[$ROW[$columnKey]] = $ROW[$columnValue];
        }

        return $r;
    }

    final public function getRow($table, $column = [], $where = []): array
    {
        $RS = $this->select($table, $column, $where, 2);

        if (count($RS) > 1) {
            throw new TooManyRowsException();
        }

        if (count($RS) == 0) {
            return null;
        }

        return $RS[0];
    }

    final public function getText($table, $column, $where = []): ?string
    {
        $ROW = $this->getRow($table, [$column], $where);

        if ($ROW == null) {
            return null;
        }

        return $ROW[$column];
    }

    final public function autoTable($table, $data, $column = [], $create = true): array
    {
        $rs = $this->select($table, $column, $data, 1);

        if (count($rs) > 0) {
            return $rs[0];
        } elseif ($create) {
            if ($this->insert($table, $data)) {
                $rs = $this->select($table, $column, $data, 1);

                if (count($rs) > 0) {
                    return $rs[0];
                } else {
                    throw new InsertException(print_r([$table, $data], true));
                }
            } else {
                throw new InsertException(print_r([$table, $data], true));
            }
        } else {
            return null;
        }
    }

    final public function autoUpdate($table, $data, $index): int
    {
        $UPDATES = 0;

        $ROW = $this->autoTable($table, $index);

        foreach ($ROW as $KEY => $VALUE) {
            unset($ROW[$KEY]);
            $ROW[strtolower($KEY)] = $VALUE;
        }

        foreach ($data as $KEY => $VALUE) {
            unset($data[$KEY]);
            $data[strtolower($KEY)] = $VALUE;
        }

        foreach ($ROW as $KEY => $VALUE) {
            if (array_key_exists($KEY, $data) && $VALUE != $data[$KEY]) {
                $this->update($table, [$KEY => $data[$KEY]], $index, 1);
                $UPDATES++;
            }
        }

        return $UPDATES;
    }

    final public function autoInsert($table, $data): bool
    {
        $rs = $this->select($table, [], $data, 1);

        if (count($rs) > 0) {
            return true;
        }

        if ($this->insert($table, $data)) {
            return true;
        }

        return false;
    }

    public function getTablesWithPrefix($prefix): array
    {
        $prefixLen = strlen($prefix);

        $_ = [];

        foreach ($this->getTables() as $table) {
            if (substr($table, 0, $prefixLen) == $prefix) {
                $_[] = $table;
            }
        }

        return $_;
    }
}
