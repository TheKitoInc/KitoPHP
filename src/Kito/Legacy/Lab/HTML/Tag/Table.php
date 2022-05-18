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
class Table extends Element
{
    public function __construct()
    {
        $this->closeMode = 0;
        $this->setStyleAttr('width', '100%');
        $this->setStyleAttr('height', '100%');
    }

    public static function autoTable($elements, $col)
    {
        $tbl = new HTMLtable();

        if ($col > 0) {
            $tr = new HTMLtr();
        } else {
            $tr = [];
        }

        $count = abs($col);
        foreach ($elements as $element) {
            $td = new HTMLtd();
            $td->addChild($element);

            if ($col > 0) {
                $tr->addChild($td);
            } else {
                if (!isset($tr[$count])) {
                    $tr[$count] = new HTMLtr();
                }

                $tr[$count]->addChild($td);
            }

            $count--;

            if ($count == 0) {
                if ($col > 0) {
                    $tbl->addChild($tr);
                    $tr = new HTMLtr();
                }

                $count = abs($col);
            }
        }

        if ($col < 0) {
            foreach ($tr as $tr_) {
                $tbl->addChild($tr_);
            }
        } else {
            $tbl->addChild($tr);
        }

        return $tbl;
    }
}
