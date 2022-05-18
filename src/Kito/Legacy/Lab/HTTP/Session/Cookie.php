<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 */

namespace Kito\HTTP\Session;

use Kito\Cryptography\Hash;
use Kito\Cryptography\SecureID;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Cookie
{
    private $name;
    private $key;
    private $hash;
    private $nameCookieASecure;
    private $nameCookieBSecure;
    private $nameCookieA;
    private $nameCookieB;
    private $sessionId;

    public function __construct(string $name, string $key, Hash $hash)
    {
        $this->name = strtoupper($name);
        $this->key = $key;
        $this->hash = $hash;
    }

    private function getNameCookieA(bool $secure)
    {
        $chr = $secure ? '_' : '-';

        return strtoupper($this->hash->calc($this->key.$chr.$this->name));
    }

    private function getNameCookieB(bool $secure)
    {
        return strtoupper($this->hash->calc($this->getNameCookieA($secure).'+'.$this->key));
    }

    public function renew()
    {
        $this->sessionId = SecureID::get();
        $this->sendCookies();
    }

    private function sendCookies()
    {
        setcookie($cA, $SID, time() + 365 * 24 * 60 * 60, '', '', $secure, true);
        setcookie($cB, strtoupper(sha1($hashKey.$SID)), time() + 365 * 24 * 60 * 60, '', '', $secure, true);
    }

    public function getSessio_nID($hashKey, $secure = false, $name = 'GSI')
    {
        $name = strtoupper($name);
        $hashKey = strtoupper($hashKey);

        if ($secure) {
            $cA = strtoupper(sha1($hashKey.'_'.$name));
        } else {
            $cA = strtoupper(sha1($hashKey.'-'.$name));
        }

        $cB = strtoupper(sha1($cA.'+'.$hashKey));

        static $cache = null;

        if (!is_array($cache)) {
            $cache = [];
        }

        if (isset($cache[$cA])) {
            return $cache[$cA];
        }

        if (isset($_COOKIE[$cA]) && isset($_COOKIE[$cB]) && strtoupper(sha1($hashKey.$_COOKIE[$cA])) == strtoupper($_COOKIE[$cB])) {
            $SID = $_COOKIE[$cA];
        } else {
            $SID = strtoupper(bin2hex(openssl_random_pseudo_bytes(20, $cstrong)));
        }

        setcookie($cA, $SID, time() + 365 * 24 * 60 * 60, '', '', $secure, true);
        setcookie($cB, strtoupper(sha1($hashKey.$SID)), time() + 365 * 24 * 60 * 60, '', '', $secure, true);

        $cache[$cA] = $SID;

        return $SID;
    }
}
