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
class Select extends Element
{
    public function __construct()
    {
        $this->closeMode = 0;
    }

    public static function autoSelect($elements, $element = false, $use_index = false)
    {
        $sl = new HTMLselect();
        foreach ($elements as $key => $value) {
            if ($use_index === false) {
                if ($value.'-' == $element.'-') {
                    $op = new HTMLoption(true);
                } else {
                    $op = new HTMLoption(false);
                }

                $op->setAttr('value', $value);
            } else {
                if ($key.'-' == $element.'-') {
                    $op = new HTMLoption(true);
                } else {
                    $op = new HTMLoption(false);
                }

                $op->setAttr('value', $key);
                //print_r($op);
            }

            $op->addChild($value);

            $sl->addChild($op);
        }

        return $sl;
    }
}
