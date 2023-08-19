<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
function getParam($key)
{
    global $FORM_PARAMS;
    if (isset($FORM_PARAMS[$key])) {
        return $FORM_PARAMS[$key];
    } elseif (isset($FORM_PARAMS["blk_form_$key"])) {
        return $FORM_PARAMS["blk_form_$key"];
    } else {
        return false;
    }
}

function write($data)
{
    static $STROUT = [];
    global $output;

    if ($data != null) {
        if (isset($output) && is_object($output)) {
            return $output->write($data);
        } else {
            array_push($STROUT, $data);
        }
    } else {
        foreach ($STROUT as  $key => $value) {
            echo $value.(DEBUG ? "\n" : '');
        }
        $STROUT = [];
    }

    return true;
}

function getlocation()
{
    $URL = $_SERVER['REQUEST_URI'];

    return getDomain().substr($URL, 1, strpos($URL, '/', 1));
}
function getCookieName($Name)
{
    return 'BLK_'.md5($Name.crc32(GetValue('Title', 'BLK Application')));
}
function putCookie($Name, $Value)
{
    return setcookie(getCookieName($Name), $Value, 0, '/');
}
function delCookie($Name)
{
    putCookie($Name, '');
}
function getCookie($Name)
{
    return (isset($_COOKIE[getCookieName($Name)])) ? $_COOKIE[getCookieName($Name)] : false;
}

function getIP()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $client_ip =
          (!empty($_SERVER['REMOTE_ADDR'])) ?
             $_SERVER['REMOTE_ADDR']
             :
             ((!empty($_ENV['REMOTE_ADDR'])) ?
                $_ENV['REMOTE_ADDR']
                :
                'unknown');

        // los proxys van añadiendo al final de esta cabecera
        // las direcciones ip que van "ocultando". Para localizar la ip real
        // del usuario se comienza a mirar por el principio hasta encontrar
        // una dirección ip que no sea del rango privado. En caso de no
        // encontrarse ninguna se toma como valor el REMOTE_ADDR

        $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

        reset($entries);
        while (list(, $entry) = each($entries)) {
            $entry = trim($entry);
            if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
                // http://www.faqs.org/rfcs/rfc1918.html
                $private_ip = [
                    '/^0\./',
                    '/^127\.0\.0\.1/',
                    '/^192\.168\..*/',
                    '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                    '/^10\..*/', ];

                $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                if ($client_ip != $found_ip) {
                    $client_ip = $found_ip;
                    break;
                }
            }
        }
    } else {
        $client_ip =
          (!empty($_SERVER['REMOTE_ADDR'])) ?
             $_SERVER['REMOTE_ADDR']
             :
             ((!empty($_ENV['REMOTE_ADDR'])) ?
                $_ENV['REMOTE_ADDR']
                :
                'unknown');
    }

    return $client_ip;
}

function getBrowser()
{
    return (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : false;
}
function isIE()
{
    return stristr(getBrowser(), 'msie');
}

function isGoogleBot()
{
    if (eregi('googlebot', Client_GetBrowser())) {
        // it says it's the lovely google
        $ip = getIP();
        $name = gethostbyaddr($ip);
        // Now we have the name, look up the corresponding IP address.
        $host = gethostbyname($name);
        if (eregi('googlebot', strtolower($name)) && $host == $ip) {
            return true;
        }
    }

    return false;
}

function getDomain()
{
    $HOST_ = $_SERVER['HTTP_HOST'];
    $PROTOCOL_ = $_SERVER['HTTPS'];

    if ($PROTOCOL_ != '') {
        if ($PROTOCOL_ == 'off') {
            $PROTOCOL_ = 'http';
        } else {
            $PROTOCOL_ = 'https';
        }
    } else {
        $PROTOCOL_ = 'http';
    }

    return $PROTOCOL_.'://'.str_replace('//', '/', $HOST_.'/');
}

function proxyScript($module)
{
    $path = getModule($module)->path.'Scripts/';

    if (file_exists($path) && is_dir($path) && $handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                echo file_get_contents($path.$file);
            }
        }

        exit;
        closedir($handle);
    }
}
function proxyImage($module, $image)
{
    //ATAQUES DE ../

    if ($module != 'System') {
        $path = getModule($module)->path.'Images/'.$image;
    } else {
        $path = dirname(__FILE__).'/Images/'.$image;
    }

    if (file_exists($path) && is_file($path)) {
        $ext = substr($image, -3);
        // set the MIME type
        switch ($ext) {
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            default:
                $mime = false;
        }

        // if a valid MIME type exists, display the image
        // by sending appropriate headers and streaming the file
        if ($mime) {
            header('Content-type: '.$mime);
            header('Content-length: '.filesize($path));
            $file = @fopen($path, 'rb');
            if ($file) {
                fpassthru($file);
                exit;
            }
        }
    } elseif (!($module == 'System' && $image == 'default.png')) {
        return proxyImage('System', 'default.png');
    }
}
