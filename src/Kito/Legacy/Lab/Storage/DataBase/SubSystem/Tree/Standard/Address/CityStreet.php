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
class CityStreet extends StateCity
{
    private $zone4;

    public function __construct($root_zone, $contry_code, $state_code, $city_code, $street_code)
    {
        parent::__construct($root_zone, $contry_code, $state_code, $city_code);

        $this->zone4 = parent::getParentZone()->get($street_code);
        $this->zone4->getAttribute('Type')->set('Street');
    }

    public function getStreetZone()
    {
        return $this->zone4;
    }

    public function getNumbers()
    {
        $list = [];

        foreach (self::getStreetZone()->getZones() as $zone) {
            if ($zone->getAttribute('Type')->get() == 'Number') {
                $list[$zone->getName()] = new StreetNumber(parent::getRootZone(), parent::getCountryZone()->getName(), parent::getStateZone()->getName(), parent::getCityZone()->getName(), self::getStreetZone()->getName(), $zone->getName());
            }
        }

        return $list;
    }
}
