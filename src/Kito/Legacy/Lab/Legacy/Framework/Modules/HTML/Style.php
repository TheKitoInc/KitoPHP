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
class Style
{
    private $zone = false;
    private $path = false;

    public static function getStyle($name)
    {
        static $cache = [];
        if (isset($cache[$name])) {
            return $cache[$name];
        }

        $cache[$name] = new Style($name);

        return $cache[$name];
    }

    private function __construct($name)
    {
        $this->path = BASE.'/Styles/';
        if (!file_exists($this->path)) {
            if (!mkdir($this->path, 0777, true)) {
                trigger_error("Can not create $this->path", E_USER_ERROR);
            }
        }

        $z = getDesignZone();
        $z = Zone::getZoneByName('Styles', $z, true);
        $this->zone = Zone::getZoneByName($name, $z, false);
    }

    public function loadFromCSS($text)
    {
        return Style::loadCSSFile($text, $this->zone);
    }

    public function downloadFromCSS($force = false)
    {
        $r = $this->path.$this->zone->name.'.css';
        if (!file_exists($r) || $force) {
            $out = Style::downloadCSSFile($this->zone);
            file_put_contents($r, $out);

            return $out;
        } else {
            return file_get_contents($r);
        }
    }

    public static function downloadCSSFile($zone)
    {
        $cont = [];
        Style::writeCSS($zone, [], $cont, true);

        $out = '';
        foreach ($cont as $name => $value) {
            $out .= $name.'{'.$value.'}'.(DEBUG ? "\n" : '');
        }

        return $out;
    }

    private static function writeCSS($zone, $attr_base, &$array_cont, $first)
    {
        if ($first === false) {
            foreach ($zone->getAttributes(false, true) as $name => $value) {
                $attr_base[$name] = $value;
            }

            if (!isset($array_cont[$zone->name])) {
                $array_cont[$zone->name] = '';
            }

            $array_cont[$zone->name] .= ArrayToTags($attr_base, ':', ';', false, false);
        }

        foreach ($zone->getChild() as $sub) {
            Style::writeCSS($sub, $attr_base, $array_cont, false);
        }
    }

    public static function loadCSSFile($str_css, $zone)
    {
        $hash = crc32($str_css);
        if ($zone->get('Hash', '') == $hash) {
            return true;
        }

        $str_css2 = '';
        foreach (split("/\*", $str_css) as $coms) {
            $aux3 = split("\*/", $coms);
            $str_css2 .= $aux3[1];
        }

        $str_css2 = str_replace("\n", '', $str_css2);
        $str_css2 = str_replace("\r", '', $str_css2);

        foreach (split('}', $str_css2) as $grp) {
            $aux = split('{', $grp);
            if (trim($aux[0]) != '') {
                $zgrp = Zone::getZoneByName(trim($aux[0]), $zone);
                foreach (split(';', $aux[1]) as $sec) {
                    $sec = trim($sec);
                    if ($sec != '') {
                        $aux2 = split(':', $sec, 2);
                        $zgrp->set(trim($aux2[0]), trim($aux2[1]));
                    }
                }
            }
        }

        $zone->set('Hash', $hash);

        return true;
    }
}
