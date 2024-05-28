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

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class BLKHTTP
{
    public static function rel2abs($rel, $base)
    {
        $base_data = parse_url($base);

        if ($rel === null || $rel == '') {
            return $base;
        }

        if (stripos($rel, 'http://') === 0 || stripos($rel, 'https://') === 0) {
            return $rel;
        }

        if (strpos($rel, '//') === 0) {
            return parse_url($base, PHP_URL_SCHEME).':'.$rel;
        }

        if (strpos($rel, '#') === 0 || strpos($rel, '?') === 0) {
            return $base.$rel;
        }

        $server = $base_data['scheme'].'://';

        if (isset($base_data['user'])) {
            $server .= $base_data['user'];

            if (isset($base_data['pass'])) {
                $server .= ':'.$base_data['pass'];
            }

            $server .= '@';
        }

        $server .= $base_data['host'];

        if (isset($base_data['port'])) {
            $server .= ':'.$base_data['port'];
        }

        if (strpos($rel, '/') === 0) {
            return $server.$rel;
        }

        $path = parse_url($rel, PHP_URL_PATH);
        //$path = @preg_replace( '#/[^/]*$#', '', $path );
        // replace '//' or  '/./' or '/foo/../' with '/'
        $path = preg_replace("/(\/\.?\/)/", '/', $path);
        $path = preg_replace("/\/(?!\.\.)[^\/]+\/\.\.\//", '/', $path);

        var_dump($server.$path);

        return $server.'/'.$path;
    }

    private $cHandler = null;
    private $lastURL = null;
    private $lastResponse = null;
    private $cookies = [];
    private $workDir = null;
    private $retryTime = 0;

    //    private function makeCookieFile()
    //    {
    //        $PATH = dirname(__FILE__). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'COOKIES';
    //        @mkdir($PATH,0777,true);
    //
    //        $PATH.=DIRECTORY_SEPARATOR.uniqid();
    //
    //        $this->cookies[] = $PATH;
    //
    //        return $PATH;
    //    }
    private function clearCookies()
    {
        foreach ($this->cookies as $PATH) {
            if (file_exists($PATH)) {
                unlink($PATH);
            }
        }
    }

    public function startSession($sessionName = null)
    {
        $this->clearCookies();

        if ($sessionName === null) {
            $sessionName = 'tmp.'.uniqid();
        }

        //        if($sessionName===null)
        //            $PATH = $this->makeCookieFile();
        //        else
        //        {
        $PATH = $this->workDir.DIRECTORY_SEPARATOR.'COOKIES';
        @mkdir($PATH, 0777, true);
        $PATH .= DIRECTORY_SEPARATOR.$sessionName.'.cookie';
        //        }

        $this->setCURLOption(CURLOPT_COOKIEJAR, $PATH);
        $this->setCURLOption(CURLOPT_COOKIEFILE, $PATH);
    }

    public function __construct($sessionName = null, $workDir = '/tmp')
    {
        $this->workDir = $workDir;

        if (!file_exists($this->workDir)) {
            mkdir($this->workDir, 0777, true);
        }

        $this->cHandler = curl_init();

        $this->setCURLOption(CURLOPT_HEADER, true);
        $this->setCURLOption(CURLOPT_RETURNTRANSFER, true);
        $this->setCURLOption(CURLOPT_FOLLOWLOCATION, false);
        $this->setCURLOption(CURLOPT_SSL_VERIFYHOST, false);
        $this->setCURLOption(CURLOPT_SSL_VERIFYPEER, false);
        $this->setCURLOption(CURLOPT_FOLLOWLOCATION, false);
        $this->setCURLOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
        //        $this->setCURLOption(CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $this->startSession($sessionName);
    }

    public function __destruct()
    {
        $this->clearCookies();
    }

    public function setCURLOption($option, $value)
    {
        return curl_setopt($this->cHandler, $option, $value);
    }

    public function getCURLInfo($option)
    {
        return curl_getinfo($this->cHandler, $option);
    }

    public function getCURLHandler()
    {
        return $this->cHandler;
    }

    private static function parseResponse($raw)
    {
        $raw = explode("\r\n\r\n", $raw, 2);

        if (count($raw) != 2) {
            throw new Exception('Invalid HTTP Response');
        }

        $headers = explode("\r\n", $raw[0]);
        $data = $raw[1];
        unset($raw);

        if (count($headers) < 1) {
            throw new Exception('Invalid HTTP Response');
        }

        $httpCode = null;
        $aux = [];
        foreach ($headers as $header) {
            if ($httpCode === null) {
                $httpCode = $header;
            } else {
                $tmp = explode(':', $header, 2);
                if (count($tmp) == 2) {
                    $aux[strtolower(trim($tmp[0]))] = trim($tmp[1]);
                }
            }
        }
        unset($tmp);
        $headers = $aux;
        unset($aux);

        $httpCode = explode(' ', $httpCode, 3);

        if (count($httpCode) != 3) {
            echo $raw;

            throw new Exception('Invalid HTTP Response');
        }

        $httpCode = $httpCode[1];

        if ($httpCode > 99 && $httpCode < 200) {
            return self::parseResponse($data);
        }

        return [
            'httpCode'    => $httpCode,
            'httpHeaders' => $headers,
            'httpData'    => $data,
        ];
    }

    private function curlExec($url, $referer = null)
    {
        $this->setCURLOption(CURLOPT_REFERER, $referer);

        if ($referer !== null) {
            $url = self::rel2abs($url, $referer);
        }

        echo "CURL EXEC ($referer): ".$url."\n";

        $this->setCURLOption(CURLOPT_URL, $url);

        $result = curl_exec($this->cHandler);

        if ($result === false) {
            throw new Exception('CURL:'.curl_error($this->cHandler), curl_errno($this->cHandler));
        }

        $response = self::parseResponse($result);

        if (isset($response['httpHeaders']['location'])) {
            return $this->doCall($response['httpHeaders']['location'], [], false, 'application/x-www-form-urlencoded', $url);
        }

        $this->lastURL = $url;
        $this->lastResponse = $response;

        if ($response['httpCode'] == 429) {
            $this->retryTime = $this->retryTime + 5;
            var_dump('Error 429. Retry in: '.$this->retryTime);
            sleep($this->retryTime);

            return $this->curlExec($url, $referer);
        } elseif ($this->retryTime > 0) {
            $this->retryTime--;
        }

        if ($response['httpCode'] > 399 && $response['httpCode'] < 600) {
            HTTPException::throwResponseCodeException($response['httpCode']);
        }

        return $response['httpData'];
    }

    public function doCall($url, $params = [], $post = false, $enctype = 'application/x-www-form-urlencoded', $referer = null)
    {
        $this->setCURLOption(CURLOPT_CUSTOMREQUEST, $post ? 'POST' : 'GET');
        $this->setCURLOption(CURLOPT_POST, $post);

        if ($post) {
            if ($enctype == 'multipart/form-data') {
                $this->setCURLOption(CURLOPT_POSTFIELDS, $params);
            } elseif ($enctype == 'text/plain') {
                throw new Exception('Enctype text/plain not implemented');
            } elseif ($enctype == 'application/x-www-form-urlencoded') {
                $this->setCURLOption(CURLOPT_POSTFIELDS, http_build_query($params));
            } else {
                throw new Exception('Invalid Enctype value');
            }

            $this->setCURLOption(CURLOPT_HTTPHEADER, ['Content-Type: '.$enctype]);
        } else {
            if (count($params) > 0) {
                if (strpos($url, '?') !== false) {
                    $url .= '&'.http_build_query($params);
                } else {
                    $url .= '?'.http_build_query($params);
                }
            }
        }

        return $this->curlExec($url, $referer);
    }

    public function getReponseCode()
    {
        return $this->lastResponse['httpCode'];
    }

    public function getLastURL()
    {
        return $this->lastURL;
    }

    public function debug()
    {
        print_r([$this->lastURL, $this->lastResponse]);
    }

    public static function getHTTPResponseMessage($code)
    {
        $http_status_codes = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            208 => 'Already Reported',
            226 => 'IM Used',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Switch Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            419 => 'Authentication Timeout',
            420 => 'Enhance Your Calm',
            420 => 'Method Failure',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            424 => 'Method Failure',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            444 => 'No Response',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            451 => 'Redirect',
            451 => 'Unavailable For Legal Reasons',
            494 => 'Request Header Too Large',
            495 => 'Cert Error',
            496 => 'No Cert',
            497 => 'HTTP to HTTPS',
            499 => 'Client Closed Request',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            598 => 'Network read timeout error',
            599 => 'Network connect timeout error',
        ];

        if (isset($http_status_codes[$code])) {
            return $http_status_codes[$code];
        } else {
            return null;
        }
    }
}

class HTTPException extends Exception
{
    public function __construct($message, $code, $previous = null)
    {
        if (!$previous instanceof Exception) {
            parent::__construct($message, $code);
        } else {
            parent::__construct($message.' > '.$previous->getMessage(), $code);
        }
    }

    public static function throwResponseCodeException($code)
    {
        throw new HTTPException(BLKHTTP::getHTTPResponseMessage($code), $code);
    }
}
