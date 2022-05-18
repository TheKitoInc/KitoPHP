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
class StateCity extends CountryState
{
    private $zone3;

    public function __construct($root_zone, $contry_code, $state_code, $city_code)
    {
        parent::__construct($root_zone, $contry_code, $state_code);
        $this->zone3 = parent::getParentZone()->get($city_code);
        $this->zone3->getAttribute('Type')->set('City');
    }

    public function getCityZone()
    {
        return $this->zone3;
    }

    public function getStreets()
    {
        $list = [];

        foreach (self::getCityZone()->getZones() as $zone) {
            if ($zone->getAttribute('Type')->get() == 'Street') {
                $list[$zone->getName()] = new CityStreet(parent::getRootZone(), parent::getCountryZone()->getName(), parent::getStateZone()->getName(), self::getCityZone()->getName(), $zone->getName());
            }
        }

        return $list;
    }
}
