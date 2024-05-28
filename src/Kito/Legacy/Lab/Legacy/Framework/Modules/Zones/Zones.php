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
 * @author TheKito <blankitoracing@gmail.com>
 */
class Zones extends Module
{
    //put your code here
    public function getIDEHtml($item)
    {
        include_once 'class.form.php';
        $form = new ZoneForm($item);

        return $form->getHTML();
    }

    public function getIDEMenu($zone = false, $max_level = false)
    {
        $a = [];

        if (is_numeric($max_level)) {
            $max_level--;
        }

        if (!is_numeric($max_level) || $max_level > 0) {
            if ($zone === false) {
                foreach (getRootZones() as $zone_) {
                    array_push($a, $this->getIDEMenu($zone_, $max_level));
                }
            } else {
                if ($zone->system) {
                    $a[$zone->id] = '<i><b>'.$zone->name.'</b></i>';
                } else {
                    $a[$zone->id] = $zone->name;
                }
                foreach ($zone->getChild() as $zone_) {
                    array_push($a, $this->getIDEMenu($zone_, $max_level));
                }
            }
        }

        return $a;
    }

    //        public static function getTree()
    //        {
    //            return "<iframe frameborder=0 style='width:100%;height:100%;' src='?Module=Zones&Tag=Tree'></iframe>";
    //        }

    public function __destruct()
    {
    }

    public function __construct()
    {
        getModule('Form');
        //            $HTML=getModule("HTML5");
        //            if(getParam("Module")=="Zones")
        //            $this->zoneGui(getParam("Tag"));
    }

    public function Form_Check($name, $value)
    {
        return true;

        return $name != 'parent_id';
    }

    public function Form_Save($params)
    {
        $zone = Zone::getZone($params['id'], getDBDriver('System'));

        foreach ($params as $key => $value) {
            if ($key == 'attttr') {
                if ($zone->set($value, '') === false) {
                }

                return false;
            } elseif ($key == 'parent_id') {
                if (!$zone->setParent(Zone::getZone($value, $zone->driver))) {
                    return false;
                }
            } elseif ($key == 'zzzzz') {
                if (getZone($zone->driver, $value, $zone) === false) {
                    return false;
                }
            } elseif ($key != 'id') {
                if (!$zone->set($key, $value)) {
                    return false;
                }
            }
        }

        return true;
    }

    //function zoneGui($mode)
    //{
    //    if ($mode=="Tree")
    //    {
    //        foreach (getRootZones() as $zona)
    //            $this->rama($zona);
    //    }
    //    else if ($mode=="Parent")
    //    {
    //    echo parents();
    //    }
    //}

    //function parents($select_zone=false)
    //{
    //    $html="<select>";
    //    $z=getViewZone();
    //    if($z!==false)
    //        foreach ($z->getParents() as $zone)
    //            if($select_zone!==false && $select_zone->id==$zone->id)
    //                $html.="<option selected value='$zone->id'>$zone->name</option>";
    //            else
    //                $html.="<option value='$zone->id'>$zone->name</option>";
    //    $html.="</select>";
    //    return $html;
    //}
    //
    //function rama($padre)
    //{
    //
    //        write ("<li>".  callFunction("HTML5", "A", array("?Zone=$padre->id&Module=".getParam("ToModule"),array($padre->name),array())));
    //
    //    //write ("<li><a target=info href=''>".$padre->name."</a>");
    //
    //$t=$padre->getAttributes();
    //
    ////if ($t!==false)
    ////    foreach ($t as $attr)
    ////        write ("<ol>$attr=>".$padre->get($attr,"unknow")."</ol>");
    //
    //    foreach ($padre->getChild() as $zona)
    //    {
    //
    //          write ("<ul>");
    //          $this->rama($zona);
    //          write ("</ul>");
    //
    //    }
    //     write ("</li>");
    //
    //
    //
    //}

    public function __load()
    {
        $zone = getViewZone();

        $html = getModule('HTML');
        $html->setStructure($zone->get('Structure', getSessionValue('Structure', getValue('Structure', 'Default'))));

        //        $html->write("<br>");$html->write("<br>");
        //        $html->write($zone->getText());
        //        $html->write("<br>");$html->write("<br>");

        $this->getChild($html->container);
    }

    public function getNav($container)
    {
        $zone = getViewZone();
        $a = [];
        $sub = new Repeater('Navigation');
        $this->doParents($a, $zone);
        $sub->doRepeat($a, $container);
    }

    private function doParents(&$parents, $zone)
    {
        if ($zone === false) {
            return;
        }

        $this->doParents($parents, $zone->getParent());

        array_push($parents, new HTMLa('?Module=Zones&Zone='.$zone->id, $zone->getTitle()));
    }

    public function getChild($container)
    {
        $zone = getViewZone();
        $a = [];
        $sub = new Repeater('Articles');
        $sub->setTableMode(0);
        foreach ($zone->getChild() as $s) {
            $az = [];
            $az['blktext'] = $s->getText();
            $az['blktitle'] = new HTMLa('?Module=Zones&Zone='.$s->id, $s->name);
            $az['blkimage'] = new HTMLimg('?Module='.getParam('Object')."&Tag=Image&Image=$key");
            array_push($a, $az);
        }
        $container->addChild($sub->doRepeat($a));
    }

    public function __unload()
    {
    }

    public function setup()
    {
        $nav = new Repeater('Navigation');
        $nav->setTableMode(0);

        $nav = new Repeater('Articles');
        $nav->setTableMode(0);
    }
}
