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
function getValue($key, $default)
{
    return getSystemZone()->get($key, $default);
}
function setValue($key, $value)
{
    return getSystemZone()->set($key, $value);
}
function timeGetTime($micro = true)
{
    list($useg, $seg) = explode(' ', microtime());

    return (float) ($micro ? $useg : 0) + (float) $seg;
}

function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!callFunction('Logger', 'Log', [($errno != 8 && $errno != 2048 && $errno != 2) ? 'ALERT' : 'ERROR', "$errno $errstr $errfile:$errline"])) {
        write("$errno $errstr $errfile:$errline<br>");
    }
}
function ArrayToTags($params, $equal = '=', $sep = ' ', $sep_ini = true, $non_com = true)
{
    if (!is_array($params)) {
        return ($sep_ini ? $sep : '').$params;
    }

    $out = '';
    foreach ($params as $key => $value) {
        $key = strtolower($key);

        if ($sep_ini) {
            $out .= $sep;
        }

        if ($value != '' && !is_numeric($key)) {
            if (strpos($value, ' ') === false || $non_com === false) {
                $out .= "$key$equal".$value;
            } else {
                $out .= $key.$equal."'".$value."'";
            }
        } else {
            $out .= is_numeric($key) ? $value : $key;
        }

        if (!$sep_ini) {
            $out .= $sep;
        }
    }

    return $out;
}
function init()
{
    error_reporting(E_ALL);
    set_error_handler('ErrorHandler');

    global $FORM_PARAMS;
    $FORM_PARAMS = [];
    foreach ($_GET as $key => $value) {
        $FORM_PARAMS[$key] = $value;
    }
    foreach ($_POST as $key => $value) {
        $FORM_PARAMS[$key] = $value;
    }

    include_once 'module.php';
    include_once 'zone.php';
    include_once 'database.php';
    include_once 'client.php';
    include_once 'session.php';
    include_once 'authentication.php';

    if (getParam('Tag') == 'Script' && getParam('Module') !== false) {
        proxyScript(getParam('Module'));
        exit;
    } elseif (getParam('Tag') == 'Image' && getParam('Module') !== false && getParam('Image') !== false) {
        proxyImage(getParam('Module'), getParam('Image'));
        exit;
    }

    if (getSessionValue('Setup', 'N') == 'N') {
        include_once 'compatibility.php';
    } else {
        getOutputModule();
    }

    //unload all modules
    foreach (getModule(null) as $key => $value) {
        unloadModule($value);
    }

    write(null);
}
function strEndsWith($FullStr, $EndStr)
{
    // Get the length of the end string
    $StrLen = strlen($EndStr);
    // Look at the end of FullStr for the substring the size of EndStr
    $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
    // If it matches, it does end with EndStr
    return $FullStrEnd == $EndStr;
}
function strStartsWith($FullStr, $StartStr)
{
    $StrLen = strlen($StartStr);
    $FullStrStart = substr($FullStr, 0, $StrLen);

    return $FullStrStart == $StartStr;
}
function autoLoadClasses($path)
{
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && strEndsWith($file, '.php') && strStartsWith($file, 'class.')) {
                include $path.$file;
            }
        }

        closedir($handle);
    }
}
    init();
