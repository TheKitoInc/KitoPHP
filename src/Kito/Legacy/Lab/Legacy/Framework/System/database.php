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
require_once 'class.resultset.php';
require_once 'class.driver.php';
function getDataZone()
{
    return getZone(getDBDriver('System'), 'Data', getSystemZone(), true);
}
function getDataSourcesZone()
{
    return getZone(getDBDriver('System'), 'Sources', getDataZone(), true);
}
function getDataLinksZone()
{
    return getZone(getDBDriver('System'), 'Links', getDataZone(), true);
}
function getDataSourceZone($name)
{
    return getZone(getDBDriver('System'), $name, getDataSourcesZone(), true);
}
function getDataLinkZone($name)
{
    return getZone(getDBDriver('System'), $name, getDataLinksZone(), true);
}

function loadDataBase()
{
    if (defined('SYSTEM_DATABASE_MASTER_DRIVER') && defined('SYSTEM_DATABASE_MASTER_DRIVER_PARAMS')) {
        if (getDBDriver('System', SYSTEM_DATABASE_MASTER_DRIVER, unserialize(SYSTEM_DATABASE_MASTER_DRIVER_PARAMS)) === false) {
            trigger_error('SYSTEM_DATABASE_MASTER_DRIVER error to load driver', E_USER_ERROR);
        }
    } else {
        trigger_error('SYSTEM_DATABASE_MASTER_DRIVER not found', E_USER_ERROR);
    }
}
function getDBDriver($name, $driver_module = null, $params = null)
{
    static $databases = []; //Internal DB Name -> IDriver;

    if (isset($databases[$name])) {
        return $databases[$name];
    }

    if ($name != 'System') {
        $zone = getDataSourceZone($name);
        if ($zone != false) {
            //            foreach ($zone->getAttributes() as $attr)
            //                echo $attr;
        }
    }

    if ($driver_module == null) {
        return false;
    }

    $databases[$name] = getModule($driver_module)->getDriver($params);

    if ($databases[$name] !== false) {
        $databases[$name]->zone = getDataSourceZone($name);
        $databases[$name]->zone->set('Module', $driver_module);
        foreach ($params as $key => $value) {
            $databases[$name]->zone->set($key, $value);
        }
    }

    return $databases[$name];
}

loadDataBase();
