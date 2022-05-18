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

namespace Kito\Network;

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class Tor
{
    public static function getNewTOR()
    {
        while (true) {
            try {
                $port = self::getFreePort();
                echo 'Creating TOR on port #'.$port."\n";
                $TOR = self($port);

                sleep(1);

                if ($TOR->isConnectionReady()) {
                    echo "TOR OK\n";
                    sleep(1);
                    $TOR->check();

                    return $TOR;
                }
                unset($TOR);
            } catch (Exception $ex) {
                echo 'TOR ERROR '.$ex->getMessage()."\n";
            }
        }
    }

    private $port = null;
    private $torPath = null;
    private $basePath = null;
    private $dataPath = null;
    private $logPath = null;
    private $configPath = null;
    private $lockPath = null;
    private $r_lock = null;
    private $command = null;

    public function check()
    {
        while (!$this->isConnectionReady()) {
            try {
                echo 'ReStarting TOR on port #'.$this->port."\n";
                exec('/bin/fuser -k '.$this->port.'/tcp');

                sleep(5);

                exec($this->command);
                $this->waitPortReady();
                $this->waitConnectionReady();
                echo "TOR OK\n";
            } catch (Exception $ex) {
                echo 'TOR ERROR '.$ex->getMessage()."\n";
            }
        }
    }

    private static function makeDir($path)
    {
        is_dir($path) || mkdir($path, 0700, true);
        chown($path, 'debian-tor');
        chgrp($path, 'debian-tor');
    }

    private static function makeFile($path)
    {
        is_file($path) || touch($path);
        chmod($path, 0700);
        chown($path, 'debian-tor');
        chgrp($path, 'debian-tor');
    }

    public function __construct($port)
    {
        $this->port = $port;

        $this->torPath = '/tmp/tor/';
        $this->basePath = $this->torPath."$port/";
        $this->dataPath = $this->basePath.'data/';

        $this->logPath = $this->basePath.'log';
        $this->configPath = $this->basePath.'config';
        $this->lockPath = $this->basePath.'lock';

        self::makeDir($this->torPath);
        self::makeDir($this->basePath);
        self::makeDir($this->dataPath);

        self::makeFile($this->logPath);
        self::makeFile($this->configPath);
        self::makeFile($this->lockPath);

        file_put_contents($this->configPath, "SocksPort $this->port\nLog notice file $this->logPath\nRunAsDaemon 1\nDataDirectory $this->dataPath\nNickname relay$this->port\nExitPolicy reject *:*");

        //        foreach(scandir('/var/lib/tor/') as $fname)
        //            if($fname!='.' && $fname!='..' && is_file('/var/lib/tor/'.$fname))// && stristr($fname, 'cached-microdescs')!==false)
        //            {
        //                copy ('/var/lib/tor/'.$fname, $this->dataPath.$fname);
        //                self::makeFile($this->dataPath.$fname);
        //            }

        passthru('cp -rv /var/lib/tor/cached-microdescs '.$this->dataPath);
        passthru('cp -rv /var/lib/tor/cached-certs '.$this->dataPath);
        $this->command = '/bin/su -c "/usr/sbin/tor -f '.$this->configPath.'" -s /bin/sh debian-tor';

        $this->r_lock = fopen($this->lockPath, 'r+');
        if (!flock($this->r_lock, LOCK_EX | LOCK_NB)) {
            throw new Exception('can not lock tor kill signal');
        }

        exec($this->command);

        $this->gc();
        $this->waitPortReady();
        $this->waitConnectionReady();
    }

    private static function getFreePort()
    {
        while (true) {
            $port = rand(10000, 19999);

            if (!self::isPortInUse($port)) {
                return $port;
            }
        }
    }

    private static function isPortInUse($port)
    {
        $connection = @fsockopen('127.0.0.1', $port);

        if (is_resource($connection)) {
            fclose($connection);

            return true;
        }

        return false;
    }

    private function waitPortReady()
    {
        $time = time();
        while (time() - $time < 1 * 60) {
            if (self::isPortInUse($this->port)) {
                return;
            }
        }

        throw new Exception('Port not ready');
    }

    public function isConnectionReady()
    {
        static $lt = 0;

        //        if(time()-$lt<10)
        //            return true;
        //
        //        sleep(1);

        $curlHandler = curl_init();
        $this->attachTor($curlHandler);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_URL, 'http://google.com/');
        curl_setopt($curlHandler, CURLOPT_TIMEOUT, 10);

        curl_exec($curlHandler);

        $lt = time();

        if (curl_errno($curlHandler) == 0) {
            curl_close($curlHandler);

            return true;
        }

        curl_close($curlHandler);

        return false;
    }

    public function waitConnectionReady()
    {
        $time = time();
        while (time() - $time < 1 * 90) {
            if ($this->isConnectionReady()) {
                return;
            }

            passthru('/usr/bin/tail '.$this->logPath);

            sleep(1);
        }

        throw new Exception('Connection not ready');
    }

    public function getPort()
    {
        return $this->port;
    }

    public function attachTor($curlHandler)
    {
        curl_setopt($curlHandler, CURLOPT_PROXY, '127.0.0.1:'.$this->port);
        curl_setopt($curlHandler, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }

    private function gcGetPortsLocked()
    {
        $output = [];

        exec('/usr/bin/lslocks', $output);

        $tmp = [];
        foreach ($output as $line) {
            $line = explode($this->torPath, $line);

            if (count($line) < 2) {
                continue;
            }

            $line = $line[1];

            $line = explode('/lock', $line);

            if (count($line) < 2) {
                continue;
            }

            if (!is_numeric($line[0])) {
                continue;
            }

            $tmp[] = (int) $line[0];
        }

        return $tmp;
    }

    private function gc()
    {
        $portsLocked = $this->gcGetPortsLocked();

        foreach (scandir($this->torPath) as $port) {
            if (!in_array($port, ['.', '..']) && is_numeric($port) && !in_array((int) $port, $portsLocked)) {
                if (self::isPortInUse($port)) {
                    exec('/bin/fuser -k '.$port.'/tcp');
                } else {
                    exec('/bin/rm -r '.$this->torPath.$port);
                }
            }
        }
    }
}
