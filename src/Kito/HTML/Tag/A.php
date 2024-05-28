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
 * a.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class A extends Element
{
    public function __construct($href, $element)
    {
        $this->closeMode = 3; // </a>
        $this->setAttr('href', $href);
        $this->addChild($element);
        if (getSessionValue('Javascript', 'N') == 'Y') {
            $this->setAttr('onclick', 'return blk_html_a_onclick(this);');
        }
    }

    public function getHTML()
    {
        return $this->toHtml();
    }
}
