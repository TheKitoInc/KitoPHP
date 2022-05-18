<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 */

/**
 * repeater.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class Repeater
{
    public $repeater_containers = false;
    public $repeater_spacers = false;
    public $name = false;
    public $zone = false;

    public static function getRepeater($name)
    {
        static $cache = [];
        if (isset($cache[$name])) {
            return $cache[$name];
        }

        $cache[$name] = new Repeater($name);

        return $cache[$name];
    }

    public function __construct($repeater_name)
    {
        $repeater_name = strtoupper($repeater_name);

        $this->name = $repeater_name;

        $this->zone = getZone(getDesignZone()->driver, 'Repeaters', getDesignZone(), true);
        $this->zone = getZone($this->zone->driver, $repeater_name, $this->zone, false);

        $this->repeater_containers = $this->zone->getList('StructureContainers');
        $this->repeater_spacers = $this->zone->getList('StructureSpacers');
    }

    public function setTableMode($mode)
    {
        return $this->zone->set('TableMode', $mode);
    }

    public function doRepeat($list)
    {
        $elements = [];
        $first = true;
        foreach ($list as $element) {
            if ($first === false && $this->zone->get('TableMode', '0') == '0') {
                $StrName = $this->repeater_spacers->getNext();
                if ($StrName !== false) {
                    array_push($elements, Structure::getStructure($StrName)->doStructure());
                }
            }

            $StrName = $this->repeater_containers->getNext();
            if ($StrName === false) {
                if (is_array($element)) {
                    array_push($elements, $element['blkmain']);
                } else {
                    array_push($elements, $element);
                }
            } else {
                if (is_array($element)) {
                    array_push($elements, Structure::getStructure($StrName)->doStructure($element));
                } else {
                    array_push($elements, Structure::getStructure($StrName)->doStructure(['blkmain' => $element]));
                }
            }

            $first = false;
        }

        if ($this->zone->get('TableMode', '0') == '0') {
            return $elements;
        } else {
            return HTMLtable::autoTable($elements, $this->zone->get('TableMode', '0'));
        }
    }

    public function setContainer($structure)
    {
        return $this->repeater_containers->add($structure->getName());
    }

    public function unsetContainer($structure)
    {
        return $this->repeater_containers->remove($structure->getName());
    }

    public function setSpacer($structure)
    {
        return $this->repeater_spacers->add($structure->getName());
    }

    public function unsetSpacer($structure)
    {
        return $this->repeater_spacers->remove($structure->getName());
    }
}
