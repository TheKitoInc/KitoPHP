<?php

use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    public function testLoader()
    {
        $this->assertTrue(class_exists('Kito\Loader\Loader'));
    }

    public function testLoaderSources()
    {
        $this->assertTrue(class_exists('Kito\Loader\Sources'));
    }
}
