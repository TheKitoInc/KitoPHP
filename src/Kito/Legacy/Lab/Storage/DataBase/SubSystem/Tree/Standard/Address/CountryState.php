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
class CountryState extends AddressCountry
{
    private $zone2;

    public function __construct($root_zone, $contry_code, $state_code)
    {
        parent::__construct($root_zone, $contry_code);
        $this->zone2 = parent::getParentZone()->get($state_code);
        $this->zone2->getAttribute('Type')->set('State');
    }

    public function getStateZone()
    {
        return $this->zone2;
    }

    public function getCities()
    {
        $list = [];

        foreach (self::getStateZone()->getZones() as $zone) {
            if ($zone->getAttribute('Type')->get() == 'City') {
                $list[$zone->getName()] = new StateCity(parent::getRootZone(), parent::getCountryZone()->getName(), self::getStateZone()->getName(), $zone->getName());
            }
        }

        return $list;
    }
}
