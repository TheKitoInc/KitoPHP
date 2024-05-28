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
class User
{
    private $zone = false;
    private $groups_name = [];
    public $id = false;

    private static function calcHash($value, $mode)
    {
        if ($mode === false || $mode == '') {
            return $value;
        } else {
            return eval('return '.$mode."('".$value."');");
        }
    }

    private static function getById($id)
    {
        $z = Zone::getZone($id);

        if (!($z instanceof zone)) {
            return false;
        }

        if (!$z->getParent()->equals(getUsersZone())) {
            return false;
        }

        return new User($z);
    }

    private static function getByName($name, $domain, $create = true)
    {
        $z = User::getZone($name, $domain, $create);
        if ($z === false) {
            return false;
        } else {
            return new User($z);
        }
    }

    private static function getZone($name, $domain, $create = true)
    {
        if ($name == 'root' || $name == 'admin' || $name == 'webmaster') {
            $system = true;
        } else {
            $system = false;
        }

        return Zone::getZoneByName(md5($name).'@'.crc32($domain), getUsersZone(), $system, false, $create);
    }

    private function __construct($zone)
    {
        $this->zone = $zone;
        $this->id = $zone->id;
        $this->groups_name = $this->zone->getList('Groups');
    }

    private function getLocalPassword()
    {
        return $this->zone->get('AuthPassword', 'Unknow');
    }

    private function getLocalPasswordHash()
    {
        return $this->zone->get('AuthHash', 'sha1');
    }

    private function changeLocalPassword($password)
    {
        return $this->zone->set('AuthPassword', User::calcHash($password, $this->getLocalPasswordHash()));
    }

    private function setAuthMethod($module)
    {
        return $this->zone->set('AuthMethod', $module);
    }

    public function validLocalPassword($pass)
    {
        return $this->getLocalPassword() == User::calcHash($pass, $this->getLocalPasswordHash());
    }

    public function getAuthMethod()
    {
        return ($this->zone->get('AuthMethod', '') == '') ? false : $this->zone->get('Module', '');
    }

    public function addGroup($group)
    {
        return $this->groups_name->add($group->getName());
    }

    public function removeGroup($group)
    {
        return $this->groups_name->remove($group->getName());
    }

    public function getGroups()
    {
        $t = [];
        foreach ($this->groups_name as $name) {
            array_push($t, Group::getGroup($name));
        }
    }

    public static function auth($name, $domain, $password, $authModule = false)
    {
        if ($authModule !== false) {
            if (getModule($authModule)->authUser($name, $domain, $password)) {
                $u = User::getByName($name, $domain, true);
            } else {
                return false;
            }

            $u->setAuthMethod($authModule);
        } else {
            $u = User::getByName($name, $domain, false);
            if ($u === false) {
                return false;
            }

            if (!$u->validLocalPassword($password)) {
                return false;
            }
        }

        if (function_exists('setSessionValue')) {
            setSessionValue('AuthID', $u->id);
            setSessionValue('AuthName', $name);
            setSessionValue('AuthDomain', $domain);
            setSessionValue('AuthMethod', ($authModule === false) ? '' : $authModule);
        }

        return $u;
    }

    public static function exist($name, $domain)
    {
        return User::getByName($name, $domain, false) !== false;
    }

    public static function get()
    {
        if (!function_exists('getSessionValue')) {
            return false;
        }

        return User::getById(getSessionValue('AuthID', 0));
    }

    public static function changePassword($name, $domain, $password, $newpassword, $authModule = false)
    {
        if ($authModule !== false) {
            return getModule($authModule)->changeUserPassword($name, $domain, $password, $newpassword);
        } else {
            $u = User::getByName($name, $domain, false);

            if ($u === false) {
                return false;
            }

            if (!$u->validLocalPassword($password)) {
                return false;
            }

            return $u->changeLocalPassword($password);
        }
    }
}
