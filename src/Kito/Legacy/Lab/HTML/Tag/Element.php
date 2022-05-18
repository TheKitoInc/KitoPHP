<?php

/**
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

namespace Kito\HTML\Tag;

/**
 * 
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
abstract class Element
{

    private $childs = array();
    private $attr = array();
    var $styleattr = array();
    var $closeMode = 0; // 0 </tag>, 1 NO CLOSE, 2 <tag ... />, 3 auto (0 or 2 if elements)
    var $id;
    var $name;

    public static function getName() 
    {
        $_ = explode('\\', get_called_class());
        return strtolower(array_pop($_));                
    }
    
    public function getAttr($name)
    {
        return $this->attr[strtoupper($name)];
    }

    public function getTag()
    {
        return self::getName();
    }

    public function getAttrs()
    {
        return $this->attr;
    }

    public function getChilds()
    {
        return $this->childs;
    }

    private static function getId($name)
    {
        static $a = array();

        if (!isset($a[$name])) {
            $a[$name] = 0;
            return $name;
        }

        $a[$name] ++;

        return $name . $a[$name];
    }

    public function __toString()
    {
        return $this->toHtml();
    }

    public function toHtml($direct_write = false)
    {
        // $this->doDBUpdate();

        $style = ArrayToTags($this->styleattr, ":", ";", false);
        if ($style != "") {
            $this->setAttr("style", $style);
        }

        $out = "";

        $out .= "<" . self::getName() . ArrayToTags($this->attr, "=", " ", true) . ($this->closeMode == 2 || ($this->closeMode == 3 && count($this->childs) == 0) ? " /" : "") . ">";

        if ($direct_write) {
            write($out);
            $out = "";
        }

        if ($this->closeMode == 0 || $this->closeMode == 3) {
            foreach ($this->childs as $child) {
                if ($direct_write && !method_exists($this, "proxyElement")) {
                    if ($child instanceof Element) {
                        $out .= $child->toHtml(true);
                    } else {
                        $out .= $child;
                    }
                } else {
                    //                    if($child instanceof Element)
                    //                        $html=$child->toHtml(false);
                    //                    else
                    $html = $child;

                    if (method_exists($this, "proxyElement")) {
                        $out .= $this->proxyElement($html);
                    } else {
                        $out .= $html;
                    }
                }

                //$out=" ".$out;

                if ($direct_write) {
                    write($out);
                    $out = "";
                }
            }
        }

        if ($this->closeMode == 0 || ($this->closeMode == 3 && count($this->childs) > 0)) {
            $out .= "</" . self::getName() . ">";
        }

        if ($direct_write) {
            write($out);
            $out = "";
        }


        return $out;
    }

    public function addChild($element)
    {
        //Element
        if (is_array($element)) {
            foreach ($element as $e) {
                array_push($this->childs, $e);
            }
            return true;
        } else {
            return array_push($this->childs, &$element);
        }
    }

    public function setAttr($name, $value)
    {
        $name = strtoupper($name);
        if ($name == "NAME") {
            $this->name = $value;
            $this->setAttr("id", Element::getId($value));
        }

        if ($name == "ID") {
            $this->id = $value;
        }


        if ($name == "WIDTH" || $name == "HEIGHT") {
            $this->setStyleAttr($name, $value);
        }

        if ($name == "src" || $name == "href") {
            $value = Element::getUrl($value);
        }

        $this->attr[$name] = $value;
    }

    public function setStyleAttr($name, $value)
    {
        $name = strtolower($name);
        $this->styleattr[$name] = $value;
    }

    public static function getUrl($url)
    {
        $href_ = explode("?", $url, 2);


        //GET PARAMS READ
        $new_params = array();
        foreach ($_GET as $key => $value) {
            $new_params[$key] = $value;
        }

        if (array_count_values($href_) > 1) {
            foreach (explode("&", $href_[1]) as $key => $value) {
                $pair = explode("=", $value, 2);
                if (array_count_values($pair) > 1) {
                    $new_params[$pair[0]] = $pair[1];
                } else {
                    $new_params[$pair[0]] = "";
                }
            }
        }

        //GET PARAMS WRITE
        $str_params = "";
        foreach ($new_params as $key => $value) {
            if ($str_params == "") {
                $str_params .= "?" . urlencode($key) . "=" . urlencode($value);
            } else {
                $str_params .= "&" . urlencode($key) . "=" . urlencode($value);
            }
        }

        return $href_[0] . $str_params;
    }

    private function doDBUpdate()
    {
        $zone = getModuleZone("HTML");
        $zone = getZone($zone->driver, "Elements", $zone, true);
        $zone = getZone($zone->driver, strtoupper($this->tag), $zone, false);
        $zone->get("CloseMode", $this->closeMode);
        $zone = getZone($zone->driver, "Attributes", $zone, true);

        foreach ($this->attr as $key => $value) {
            $zone->get($key, $value);
        }

        $zone = getModuleZone("HTML");
        $zone = getZone($zone->driver, "Style", $zone, true);
        $zone = getZone($zone->driver, "Attributes", $zone, true);
        foreach ($this->styleattr as $key => $value) {
            $zone->get($key, $value);
        }
    }

}
