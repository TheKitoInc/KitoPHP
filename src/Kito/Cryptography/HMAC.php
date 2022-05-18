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

namespace Kito\Cryptography;

use Kito\Cryptography\HMAC\HMACAlgorithmCalcException;
use Kito\Cryptography\HMAC\HMACAlgorithmNotFoundException;
use Kito\Cryptography\HMAC\InvalidHMACValueException;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class HMAC
{
    public static function getAlgorithms(): array
    {
        return hash_hmac_algos();
    }

    public static function getAlgorithm(string $name): Hash
    {
        $lowerName = strtolower($name);

        static $_ = null;

        if ($_ === null) {
            $_ = [];
        }

        if (!isset($_[$lowerName])) {
            $_[$lowerName] = new Hash($lowerName);
        }

        return $_[$lowerName];
    }

    private $name;
    private $example;

    private function __construct(string $name)
    {
        $this->name = $name;

        if (!in_array($this->name, self::getAlgorithms())) {
            throw new HMACAlgorithmNotFoundException($this->name);
        }

        $this->example = $this->calc('', '');
    }

    public function calc(string $data, string $secret): string
    {
        $t = hash_hmac($this->name, $data, $secret);

        if ($t === false) {
            throw new HMACAlgorithmCalcException($data);
        }

        return strtoupper($t);
    }

    public function check(string $hashValue, string $data, string $secret): bool
    {
        return $this->calc($data, $secret) == strtoupper($hashValue);
    }

    public function checkHMAC(string $value): bool
    {
        return strlen($value) == strlen($this->example);
    }

    public function validateHMAC(string $hashValue): void
    {
        if (!$this->checkHMAC($hashValue)) {
            throw new InvalidHMACValueException($hashValue);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
