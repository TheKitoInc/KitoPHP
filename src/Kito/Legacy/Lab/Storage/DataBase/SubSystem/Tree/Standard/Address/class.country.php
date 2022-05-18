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
class AddressCountry extends Address
{
    private $zone1;

    public function __construct($root_zone, $contry_code)
    {
        parent::__construct($root_zone);
        $this->zone1 = parent::getParentZone()->get($contry_code);
        $this->zone1->getAttribute('Type')->set('Country');
    }

    public function getCountryZone()
    {
        return $this->zone1;
    }

    public function getStates()
    {
        $list = [];

        foreach (self::getCountryZone()->getZones() as $zone) {
            if ($zone->getAttribute('Type')->get() == 'State') {
                $list[$zone->getName()] = new CountryState(parent::getRootZone(), self::getCountryZone()->getName(), $zone->getName());
            }
        }

        return $list;
    }
}
