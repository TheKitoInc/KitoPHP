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
class MySql extends Module
{
    public function getDriver($params)
    {
        include_once 'class.driver.php';

        return new MySqlDriver($params);
    }

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function __load()
    {
    }

    public function __unload()
    {
    }

    public function authUser($name, $domain, $password)
    {
        $cnn = mysql_connect($domain, $name, $password);

        if ($cnn === false) {
            return false;
        }

        if (mysql_ping($cnn)) {
            mysql_close($cnn);

            return true;
        } else {
            return false;
        }
    }

    public function changeUserPassword($name, $domain, $password, $newpassword)
    {
        $cnn = mysql_connect($domain, $name, $password);

        if ($cnn === false) {
            return false;
        }

        if (mysql_ping($cnn)) {
            if (mysql_unbuffered_query("SET PASSWORD = PASSWORD('".$newpassword."')", $cnn) === false) {
                return false;
            }

            mysql_close($cnn);

            return true;
        } else {
            return false;
        }
    }
}
