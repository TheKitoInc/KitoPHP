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

namespace Kito\System;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class Executable
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;

        if (!file_exists($this->filename)) {
            $this->throwBinaryException('not exists');
        }

        if (!is_file($this->filename)) {
            $this->throwBinaryException('not is file');
        }

        if (!is_readable($this->filename)) {
            $this->throwBinaryException('not is readable');
        }

        if (!is_executable($this->filename)) {
            $this->throwBinaryException('not is executable');
        }
    }

    private function throwBinaryException($message): void
    {
        throw new \Exception("$this->filename binary $message");
    }

    public function executeAndWait(array $args): string
    {
        $command = $this->filename.' '.implode(' ', $args);

        return shell_exec($command);
    }

    //    private function openProcess($cmd)
    //    {
    //        $descriptorspec = array(
    //            0 => array("pipe", "r"),  // stdin
    //            1 => array("pipe", "w"),  // stdout
    //            2 => array("pipe", "w"),  // stderr
    //        );
    //
    //        $pipes = array();
    //
    //        proc_open($cmd, $descriptorspec, $pipes);
    //    }
}
