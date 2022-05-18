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

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class Dictionary
{
    private $zone;

    public function __construct($root_zone)
    {
        $z = $root_zone;
        $z = $z->get('Standard');
        $z = $z->get('Dictionary');
        $this->zone = $z;
    }

    private static function procWord($word)
    {
        $t = $word;
        $t = strtoupper($t);

        $t = str_replace('Á', 'A', $t);
        $t = str_replace('É', 'E', $t);
        $t = str_replace('Í', 'I', $t);
        $t = str_replace('Ó', 'O', $t);
        $t = str_replace('Ú', 'U', $t);

        return $t;
    }

    public function getZone($word)
    {
        $z = $this->zone->get($this->getKeysWord($word));

        foreach (str_split($word) as $l) {
            $z = $z->get($l);
        }

        $z->getAttribute('len')->set(strlen($word));

        return $z;
    }

    private function getKeysWord($word)
    {
        $word = self::procWord($word);
        $b = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $tmp = '';
        foreach ($b as $key) {
            if (strpos($word, $key) !== false) {
                $tmp .= $key;
            }
        }

        return $tmp;
    }

    public function getWord($zone)
    {
        $len = $zone->getAttribute('len')->get();

        if (!is_numeric($len)) {
            return null;
        }

        $word = '';
        $z = $zone;
        for ($i = 0; $i < $len; $i++) {
            $word = $z->getName().$word;
            $z = $z->getParent();
        }

        return $word;
    }

    private function getWords($zone, &$list = [])
    {
        foreach ($zone->getZones() as $subzone) {
            $this->getWords($subzone, $list);
        }

        $len = $zone->getAttribute('len')->get();

        if (is_numeric($len)) {
            array_push($list, $this->getWord($zone));
        }

        return $list;
    }

    public function search($word)
    {
        $list = [];
        $t = $this->getKeysWord($word);

        foreach ($this->zone->getZones() as $zone) {
            $total = strlen($t);
            $c1 = $zone->getName();

            foreach (str_split($t) as $l) {
                if (strpos($c1, $l) !== false) {
                    $total--;
                }
            }

            if ($total < strlen($t) / 3) {
                $this->getWords($zone, $list);
            }
        }

        return $list;
    }
}
