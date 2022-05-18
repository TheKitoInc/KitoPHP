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
function getModulesZone()
{
    return getZone(getDBDriver('System'), 'Modules', getSystemZone(), true);
}
function getModuleZone($name)
{
    return getZone(getDBDriver('System'), $name, getModulesZone(), true);
}

function getOutputModule()
{
    $param = getParam('Module');
    if ($param !== false) {
        return getModule($param);
    } else {
        return getModule(getSessionValue('Module', getApplicationZone()->get('Module', getValue('Module', 'Zones'))));
    }
}

abstract class Module
{
    public $zone = false;
    public $path = false;
    public $name = false;

    abstract public function __destruct();

    abstract public function __construct();

    abstract public function __load();

    abstract public function __unload();
}
function getModule($name, $path = null)
{
    static $modules = [];

    if ($name == null) {
        return $modules;
    }

    if (isset($modules[$name])) {
        return $modules[$name];
    }

    if ($path == null) {
        $path = dirname(__FILE__).'/../Modules/';
    }

    $base_path = $path.$name.'/';
    $path .= $name.'/class.main.php';

    if (!file_exists($path)) {
        trigger_error('Module: '.$name.' Not exist', E_USER_ERROR);

        return false;
    }

    include_once $path;

    if (!class_exists($name)) {
        trigger_error("Class $name not fount in ".$path, E_USER_ERROR);

        return false;
    }

    $modules[$name] = false; //por orden de parada
    $modules[$name] = new $name();

    $modules[$name]->path = $base_path;
    $modules[$name]->name = $name;
    $modules[$name]->zone = getModuleZone($name);

    if (method_exists($modules[$name], 'setup') && $modules[$name]->zone->get('Setup', 'N') == 'N' && $modules[$name]->setup()) {
        $modules[$name]->zone->set('Setup', 'Y');
    }

    $modules[$name]->__load();

    return $modules[$name];
}
function callFunction($mod_name, $function, $params)
{
    $module = getModule($mod_name);

    if ($module === false && !($mod_name == 'Logger' && $function == 'Log')) {
        trigger_error("Module $mod_name not exist", E_USER_WARNING);
    }

    if (method_exists($module, $function)) {
        $params_str = '';
        $arr_size = count($params);
        for ($i = 0; $i < $arr_size; $i++) {
            if ($params_str != '') {
                $params_str .= ',';
            }

            $params_str .= "\$params[$i]";
        }
        $command = 'return $module->'.$function.'('.$params_str.');';

        return eval($command);
    } else {
        trigger_error("Function $function on module $mod_name not exist", E_USER_WARNING);
    }

    return false;
}
function unloadModule($module)
{
    if ($module->zone === false) {
        $module->zone = getModuleZone($module->name);
    }

    $module->__unload();
}
