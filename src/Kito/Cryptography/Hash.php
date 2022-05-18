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

use Kito\Cryptography\Hash\HashAlgorithmCalcException;
use Kito\Cryptography\Hash\HashAlgorithmNotFoundException;
use Kito\Cryptography\Hash\InvalidHashValueException;


/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Hash
{
    public static function getAlgorithms(): array
    {
        return hash_algos();
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
            throw new HashAlgorithmNotFoundException($this->name);
        }

        $this->example = $this->calc('');
    }

    public function calc(string $data): string
    {
        $t = hash($this->name, $data);

        if ($t === false) {
            throw new HashAlgorithmCalcException($data);
        }

        return strtoupper($t);
    }

    public function check(string $hashValue, string $data): bool
    {
        return $this->calc($data) == strtoupper($hashValue);
    }

    public function checkHash(string $hashValue): bool
    {
        return strlen($hashValue) == strlen($this->example);
    }

    public function validateHash(string $hashValue): void
    {
        if (!$this->checkHash($hashValue)) {
            throw new InvalidHashValueException($hashValue);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
