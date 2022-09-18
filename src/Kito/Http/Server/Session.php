<?php

namespace Kito\Http\Server;

use \Kito\DataBase\KeyValue\KeyValueInterface;

class Session
{

    private $sessionIdController;
    private $sessionStorageController;

    public function __construct(UIDInterface $sessionIdController, KeyValueInterface $sessionStorageController)
    {
        $this->sessionIdController = $sessionIdController;
        $this->sessionStorageController = $sessionStorageController;
    }

}
