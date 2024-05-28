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
 * mysql.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class MySqlDriver extends Driver
{
    public $server = '127.0.0.1';
    public $database = 'test';
    public $user = 'test';
    public $password = '';
    public $cnn = false;
    public $calls = 0;

    public function getStats()
    {
        return 'Total Commands and Querys:'.$this->calls;
    }

    public function __construct($params)
    {
        if (isset($params['Server'])) {
            $this->server = $params['Server'];
        }

        if (isset($params['Database'])) {
            $this->database = $params['Database'];
        }

        if (isset($params['User'])) {
            $this->user = $params['User'];
        }

        if (isset($params['Password'])) {
            $this->password = $params['Password'];
        }

        $this->connect();
    }

    public function isConnected()
    {
        if ($this->cnn === false) {
            return false;
        } else {
            return mysql_ping($this->cnn);
        }
    }

    public function connect()
    {
        if ($this->isConnected()) {
            $this->close();
        }

        $this->cnn = mysql_connect($this->server, $this->user, $this->password);

        if (!$this->cnn) {
            trigger_error('Server Error '.$this->server, E_USER_WARNING);
            $this->close();

            return false;
        }

        if (!mysql_select_db($this->database, $this->cnn)) {
            trigger_error('DataBase Error '.$this->database.' on '.$this->server, E_USER_WARNING);
            $this->close();

            return false;
        }

        return true;
    }

    public function close()
    {
        if (!$this->isConnected()) {
            return true;
        }

        return !(mysql_close($this->cnn) === false);
    }

    public function query($sql)
    {
        callFunction('Logger', 'Log', ['DEBUG', 'MySql Query:'.$sql]);
        include_once 'class.resultset.php';
        $this->calls++;

        $Result = mysql_query($sql, $this->cnn);

        if ($Result === false) {
            trigger_error('MySQLQueryError;'.mysql_error(), E_USER_WARNING);

            return false;
        }
        callFunction('Logger', 'Log', ['DEBUG', 'MySql Query OK->'.$sql]);

        return new MySQLRS($Result);
    }

    public function gettext($Table_, $Row_, $Cond)
    {
        $tmp = $this->query('select '.$Row_.' from '.$Table_.' where '.$Cond.' limit 1;');
        if ($this->getRows($tmp) > 0) {
            while ($rowEmp = mysql_fetch_assoc($tmp)) {
                return $rowEmp[$Row_];
            }
        }

        return '';
    }

    public function command($SQL)
    {
        callFunction('Logger', 'Log', ['DEBUG', 'MySql Command:'.$SQL]);
        $this->calls++;
        $Result = mysql_unbuffered_query($SQL, $this->cnn);

        if ($Result === false) {
            trigger_error('command Error;'.mysql_error(), E_USER_WARNING);

            return false;
        } else {
            callFunction('Logger', 'Log', ['DEBUG', 'MySql Command OK->'.$SQL]);

            return true;
        }
    }

    //Structure
    public function getTables()
    {
        //debug_print_backtrace();
        $r = [];

        $tmp = $this->query('SHOW TABLES FROM '.$this->database);

        while ($tmp->next()) {
            $row = $tmp->get();
            array_push($r, $row['Tables_in_imo']);
        }

        return $r;
    }

    public function __destruct()
    {
        $this->close();
    }

    //    function getTableKeys($table)
    //    {
    //        $r=array();
    //
    //        $tmp=$this->query("SHOW COLUMNS FROM ".$table);
    //          if ($this->getRows($tmp)> 0)
    //                while ($rowEmp = mysql_fetch_row($tmp))
    //                    if(strtoupper($rowEmp[3])=="PRI")
    //                        array_push($r, $rowEmp[0]);
    //
    //
    //        return $r;
    //    }
    public function getTableCols($table)
    {
        $r = [];

        $tmp = $this->query('SHOW COLUMNS FROM '.$table);
        if ($tmp !== false && $tmp->first()) {
            while (true) {
                $row = $tmp->get();
                $r[$row['Field']] = $row;

                if (!$tmp->next()) {
                    break;
                }
            }
        }

        return $r;
    }

    public function existTable($table)
    {
        return in_array(strtoupper($table), $this->getTables());
    }

    public function createTable($table, $cols)
    {
        $sql = 'CREATE TABLE '.$table.'(';
        foreach (array_expression as $key => $value) {
            $sql .= $key.' '.$value['Type'].' '.($value['Null'] == 'NO' ? 'not null' : '').' '.$value['Extra'].',';
        }
        $sql .= ');';
    }

    public function editTable($table, $cols)
    {
    }

    public function alterTable($table, $cols)
    {
        $e = existTable($table);
        $table = strtoupper($table);

        if ($cols == null || !is_array($cols) || array_count_values($cols) == 0) {
            if ($e) {
                return $this->command('DROP TABLE IF EXISTS '.$table.';');
            } else {
                return true;
            }
        }

        if ($this->existTable($table)) {
            return $this->command(editTable($table, $cols));
        } else {
            return $this->command(createTable($table, $cols));
        }
    }

    public function getPrimaryKey($table)
    {
        foreach ($this->getTableCols($table) as $col => $data) {
            foreach ($data as $key => $value) {
                if ($key == 'Key' && $value == 'PRI') {
                    return $col;
                }
            }
        }

        return false;
    }
}
