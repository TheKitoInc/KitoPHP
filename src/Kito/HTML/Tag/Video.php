<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace Kito\HTML\Tag;

/**
 * @author TheKito <blankitoracing@gmail.com>
 */
class Video extends Element
{
    public function __construct($src, $controls = true)
    {
        $this->closeMode = 3;

        $this->addChild('Your browser does not support HTML5 video element.');
        $this->setAttr('src', $src);

        if ($controls) {
            $this->setAttr('controls', 'controls');
        }
    }
}
