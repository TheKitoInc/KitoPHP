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

namespace Kito\Minifier;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
abstract class Minifier
{
    protected $maxLineSize = 8000;

    public function __construct(int $maxLineSize = 8000)
    {
        $this->maxLineSize = $maxLineSize;
    }

    public function getMaxLineSize(): int
    {
        return $this->maxLineSize;
    }

    public function setMaxLineSize($maxLineSize): void
    {
        $this->maxLineSize = $maxLineSize;
    }

    abstract protected function minifyLine($codeLine);

    public function parseFromString(string $code): string
    {
        $_ = '';

        foreach (explode("\n", str_replace("\r", "\n", $code)) as $codeLine) {
            $codeLine = trim($codeLine);

            if (empty($codeLine)) {
                continue;
            }

            $_ .= $this->minifyLine($codeLine);
        }

        return $_;
    }

    public function parseFromFile(string $filePathSource, ?string $filePathMinified = null, bool $forceReWrite = false): void
    {
        if ($filePathMinified === null) {
            $filePathMinified = pathinfo($filePathSource, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.pathinfo($filePathSource, PATHINFO_FILENAME).'.min.'.pathinfo($filePathSource, PATHINFO_EXTENSION);
        }

        if (file_exists($filePathMinified) && filemtime($filePathMinified) >= filemtime($filePathSource) && $forceReWrite == false) {
            return;
        }

        $sourceFileDescriptor = fopen($filePathSource, 'r');
        $destinationFileDescriptor = fopen($filePathMinified, 'w');

        foreach (fgets($sourceFileDescriptor, $this->maxLineSize) as $codeLine) {
            $codeLine = trim($codeLine);

            if (empty($codeLine)) {
                continue;
            }

            fwrite($destinationFileDescriptor, $this->minifyLine($codeLine));
        }

        fclose($destinationFileDescriptor);
        fclose($sourceFileDescriptor);
    }
}
