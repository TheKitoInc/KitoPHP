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
class StreetNumber extends CityStreet
{
    private $zone5;

    public function __construct($root_zone, $contry_code, $state_code, $city_code, $street_code, $number)
    {
        parent::__construct($root_zone, $contry_code, $state_code, $city_code, $street_code);
        $this->zone5 = parent::getParentZone()->get($number);
        $this->zone5->getAttribute('Type')->set('Number');
    }

    protected function getParentZone()
    {
        return $this->zone5;
    }
}
