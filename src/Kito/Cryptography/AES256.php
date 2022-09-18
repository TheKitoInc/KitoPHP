<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kito\Cryptography;

/**
 * Description of AES256.
 *
 * @author Instalacion
 */
class AES256
{
    private $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * decrypt AES 256.
     *
     * @param data $edata
     *
     * @return decrypted data
     */
    public function decrypt($edata)
    {
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3; // depends on key length
        $data00 = $this->password.$salt;
        $hash = [];
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv = substr($result, 32, 16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }

    /**
     * crypt AES 256.
     *
     * @param data $data
     *
     * @return base64 encrypted data
     */
    public function encrypt($data)
    {
        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
            $dx = hash('sha256', $dx.$this->password.$salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);

        return base64_encode($salt.$encrypted_data);
    }
}
