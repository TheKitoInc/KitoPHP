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
function getAuthZone()
{
    return Zone::getZoneByName('Authentication', getSystemZone(), true);
}
function getUsersZone()
{
    return Zone::getZoneByName('Users', getAuthZone(), true);
}
function getGroupsZone()
{
    return Zone::getZoneByName('Groups', getAuthZone(), true);
}

require_once 'class.user.php';
require_once 'class.group.php';

function doAuthBasic($realm, $authModule = false)
{
    return setSessionValue('Realm', $realm) && setSessionValue('AuthMethod', $authModule) && header('WWW-Authenticate: Basic realm="'.$realm.'"');
}
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER'] != '') {
    $mod = getSessionValue('AuthMethod', false);
    if ($mod == '') {
        $mod = false;
    }

    $user = split('@', $_SERVER['PHP_AUTH_USER'], 2);
    if (!User::auth($user[0], $user[1], $_SERVER['PHP_AUTH_PW'], $mod)) {
        doAuthBasic(getSessionValue('Realm', ''), $mod);
    }
}

Group::getGroup('Root');
Group::getGroup('Admin');
Group::getGroup('Developer');
Group::getGroup('Designer');
