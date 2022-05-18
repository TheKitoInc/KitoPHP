<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class.
 *
 * @author andresrg
 */
class Address
{
    private $zone0;
    private $root_zone;

    public function __construct($root_zone)
    {
        $this->root_zone = $root_zone;
        $z = $root_zone;
        $z = $z->get('Standard');
        $z = $z->get('Geographic');
        $this->zone0 = $z->get('Addresses');
    }

    protected function getParentZone()
    {
        return $this->zone0;
    }

    protected function getRootZone()
    {
        return $this->root_zone;
    }

    public function getCountries()
    {
        $list = [];

        foreach (self::getParentZone()->getZones() as $zone) {
            if ($zone->getAttribute('Type')->get() == 'Country') {
                $list[$zone->getName()] = new AddressCountry($this->getRootZone(), $zone->getName());
            }
        }

        return $list;
    }
}
