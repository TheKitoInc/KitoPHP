<?php

define('APP_PATH_LIB', __DIR__ . '/src');

require_once APP_PATH_LIB . '/Kito/Loader/AbstractLoader.php';

require_once APP_PATH_LIB . '/Kito/Loader/PSR0Loader.php';

$localLoader = new \Kito\Loader\PSR0Loader(APP_PATH_LIB);
$localLoader->register();