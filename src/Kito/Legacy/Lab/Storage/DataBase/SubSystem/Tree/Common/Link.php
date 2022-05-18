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
class Link extends DataThree
{
    private $tableName;
    private $tablePk;
    private $tableValue0;
    private $tableValue1;

    public function __construct($cnn, $tableName, $tablePk, $tableValue0, $tableValue1)
    {
        parent::__construct($cnn, $tableName, $tablePk, $tableValue0, $tableValue1);
        $this->tableName = $tableName;
        $this->tablePk = $tablePk;
        $this->tableValue0 = $tableValue0;
        $this->tableValue1 = $tableValue1;
    }

    public function set($id_a, $id_b)
    {
        if ($id_a < $id_b) {
            return parent::getId($id_a, $id_b);
        } elseif ($id_a > $id_b) {
            return parent::getId($id_b, $id_a);
        } else {
            return 0;
        }
    }

    public function gets($idAorB)
    {
        $t = [];
        $rs = parent::getCnn()->select($this->tableName, [$this->tableValue0], [$this->tableValue1 => $idAorB], 0);
        foreach ($rs as $row) {
            if (!in_array($row[$this->tableValue0], $t)) {
                array_push($t, $row[$this->tableValue0]);
            }
        }

        $rs = parent::getCnn()->select($this->tableName, [$this->tableValue1], [$this->tableValue0 => $idAorB], 0);
        foreach ($rs as $row) {
            if (!in_array($row[$this->tableValue1], $t)) {
                array_push($t, $row[$this->tableValue1]);
            }
        }

        return $t;
    }

    public function delete($id_a, $id_b)
    {
        if ($id_a < $id_b) {
            return parent::getCnn()->delete($this->tableName, [$this->tableValue0 => $id_a, $this->tableValue1 => $id_b]);
        } elseif ($id_a > $id_b) {
            return parent::getCnn()->delete($this->tableName, [$this->tableValue0 => $id_b, $this->tableValue1 => $id_a]);
        } else {
            return false;
        }
    }
}
