<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Kito\Session;

use Kito\Cryptography\AES256;

/**
 * Description of EncryptedSessionHandler
 *
 * @author Instalacion
 */
class EncryptedSessionHandler extends \SessionHandler
{
    private $crypto;

    public function __construct(AES256 $crypto)
    {
        $this->crypto = $crypto;
    }

    public function read($id)
    {
        $data = parent::read($id);

        if (!$data) {
            return "";
        } else {
            return $this->crypto->decrypt($data);
        }
    }

    public function write($id, $data)
    {
        $data = $this->crypto->encrypt($data);

        return parent::write($id, $data);
    }
}
