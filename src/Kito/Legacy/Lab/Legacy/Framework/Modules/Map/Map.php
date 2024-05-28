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
 * main.
 *
 * @author sebastian
 */
class Map extends Module
{
    //put your code here

    public function loadMap($x, $y, $z)
    {
        write('<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.es/?ie=UTF8&amp;ll='.$x.','.$y.'&amp;spn=9.368034,19.753418&amp;z='.$z.'&amp;output=embed"></iframe>');
    }

    public function loadMapWithMarker($x, $y)
    {
        write('<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.es/maps?f=d&amp;source=s_d&amp;saddr='.$x.','.$y.'&amp;daddr=&amp;hl=es&amp;geocode=&amp;mra=dme&amp;mrcr=0&amp;mrsp=0&amp;sz=6&amp;sll='.$x.','.$y.'&amp;sspn='.$x.','.$y.'&amp;ie=UTF8&amp;ll='.$x.','.$y.'&amp;spn='.$x.','.$y.'&amp;output=embed"></iframe>');
    }

    public function __construct()
    {
        getModule('HTML');
        if (getParam('Module') == 'Map') {
            $this->loadMapWithMarker(-34.916534, -56.155822);
        }
    }

    public function __destruct()
    {
    }

    public function __load()
    {
    }

    public function __unload()
    {
    }
}
