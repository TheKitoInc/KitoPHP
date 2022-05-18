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

namespace Kito\Http\Client;

use Kito\Exception\LibraryNotFoundException;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Curl
{
    private $curl;

    public function __construct()
    {
        if (!function_exists('\curl_init')) {
            throw new LibraryNotFoundException('php-curl');
        }

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/3.0.0.13');
    }

    public function setSOCKS5Proxy(string $proxyHost, int $proxyPort)
    {
        return $this->setProxy(CURLPROXY_SOCKS5, $proxyHost, $proxyPort);
    }

    public function setHTTPProxy(string $proxyHost, int $proxyPort)
    {
        return $this->setProxy(CURLPROXY_HTTP, $proxyHost, $proxyPort);
    }

    public function setHTTPSProxy(string $proxyHost, int $proxyPort)
    {
        return $this->setProxy(CURLPROXY_HTTPS, $proxyHost, $proxyPort);
    }

    private function setProxy(int $proxyType, string $proxyHost, int $proxyPort)
    {
        curl_setopt($this->curl, CURLOPT_PROXYTYPE, $proxyType);
        curl_setopt($this->curl, CURLOPT_PROXY, $proxyHost.':'.$proxyPort);
        $this->curlSetCookiePath($this->curl, sys_get_temp_dir().'/'.sha1($proxyType.'://'.$proxyHost.':'.$proxyPort));
    }

    private function curlSetCookiePath($path)
    {
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $path);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $path);
    }

    private function setHeaders(array $headers = [])
    {
        $h = [];
        foreach ($headers as $name => $values) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    $h[] = $name.': '.$value;
                }
            } else {
                $h[] = $name.': '.$values;
            }
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $h);
    }

    public function basicRequest(string $url, string $method = 'GET', array $headers = [], string $payload = null)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_URL, ltrim($url, '/'));
        $this->setHeaders($headers);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $payload);

//        if (curl_getinfo($this->curl, CURLINFO_HTTP_CODE) > 399)
//            throw new Exception('HTTP ' . curl_getinfo($this->curl, CURLINFO_HTTP_CODE));

        return curl_exec($this->curl);
    }
}
