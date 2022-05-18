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
class FinalAttribute
{
    private $idz;
    private $name;
    private $az;

    public function __construct(&$az, $idz, $name)
    {
        $this->az = $az;
        $this->idz = $idz;
        $this->name = $name;
    }

    public function set($value)
    {
        return $this->az->set($this->idz, $this->name, $value);
    }

    public function get($default = null)
    {
        return $this->az->get($this->idz, $this->name, $default);
    }

    public function delete()
    {
        return $this->az->delete($this->idz, $this->name);
    }
}
