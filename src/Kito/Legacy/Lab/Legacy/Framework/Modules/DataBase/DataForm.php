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
 * dataform.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
getModule('Form');
class DataForm extends HForm
{
    public $driver;
    public $table;
    public $pk;
    public $pk_name;
    public $cols;
    public $rs;
    public $data = [];

    public function __construct($driver, $table, $pk)
    {
        $this->driver = $driver;
        $this->table = $table;
        $this->pk = $pk;

        $this->pk_name = $this->driver->getPrimaryKey($this->table);
        $this->cols = $this->driver->getTableCols($this->table);

        if ($this->pk != 0) {
            $sql = '';
            foreach ($this->cols as $a => $b) {
                if ($sql != '') {
                    $sql .= ', ';
                }
                $sql .= $a;
            }
            $sql = 'select '.$sql.' from '.$this->table.' where '.$this->pk_name."='".$this->pk."'";
            $this->rs = $this->driver->query($sql);
            if ($this->rs->first()) {
                $this->data = $this->rs->get();
            }
        }
    }

    protected function getElements()
    {
        $r = [];

        array_push($r, new FormHidden(false, 'pk', $this->pk));
        array_push($r, new FormHidden(false, 'table', $this->table));
        array_push($r, new FormHidden(false, 'driver', $this->driver->zone->name));

        if (count($this->data) > 0) {
            foreach ($this->data as $col => $val) {
                array_push($r, new FormText($col, $col, $val));
            }
        } else {
            foreach ($this->cols as $col => $val) {
                array_push($r, new FormText($col, $col, ''));
            }
        }

        array_push($r, new FormSubmit(false, false, false));

        return $r;
    }

    protected function getFunction()
    {
        return 'Form';
    }

    protected function getModule()
    {
        return 'DataBase';
    }
}
