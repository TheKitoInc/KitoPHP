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
 * @author TheKito <blankitoracing@gmail.com>
 */
class ZoneForm extends HForm
{
    public $zone;

    public function __construct($zone_id)
    {
        $this->zone = Zone::getZone($zone_id, getDBDriver('System'));
    }

    protected function getElements()
    {
        $a = [];
        array_push($a, new FormHidden('', 'id', $this->zone->id));

        $combo = new FormSelect('Parent', 'parent_id', $this->zone->getParent()->id);
        $combo->setList($this->zone->getParents(), true);
        array_push($a, $combo);

        foreach ($this->zone->getAttributes() as $name) {
            array_push($a, new FormText($name, $name, $this->zone->get($name, '')));
        }

        array_push($a, new FormText('Create Sub Zone', 'zzzzz', ''));
        array_push($a, new FormText('AddAtr', 'attttr', ''));

        return $a;
    }

    protected function getModule()
    {
        return 'Zones';
    }
}
