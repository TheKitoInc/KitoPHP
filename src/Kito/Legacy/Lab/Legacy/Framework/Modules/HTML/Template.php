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
 * template.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class Template
{
    //
    //
    //    public static function getTemplate($name=false)
    //    {
    //        if($name===false)
    //        {
    //            $om=getOutputModule();
    //            if($om===false)
    //                $name=getSessionValue("Template", getValue("Template", "Default"));
    //            else
    //                $name="Module_".$om->name;
    //        }
    //
    //       static $cache=array();
    //        if (isset($cache[$name]))
    //            return $cache[$name];
    //
    //        $cache[$name]=new Template($name);
    //        return $cache[$name];
    //
    //    }
    //    public static function writeHTMLTemplate($zone,$type)
    //    {
    //        $code=Template::writeHTMLTemplateCode($zone,"\$input",true);
    //        return "function doTemplate".$type."(\$input){\n\$output=\$input;\n".$code."\nreturn \$output;\n}";
    //    }
    //    private static function writeHTMLTemplateCode($zone,$container,$is_first)
    //    {
    //        if($zone->name=="Style")
    //            return "";
    //
    //        $out="";
    //
    //        if(!$is_first)
    //        {
    //            $out.=Template::writeHTMLTemplateZone($zone, $container);
    //            $var_name=Template::getTemplateVar($zone);
    //        }
    //        else
    //            $var_name=$container;
    //
    //        $class_name=HTML::getClassNameFromZone($zone);
    //        if(class_exists($class_name) || $is_first)
    //            foreach ($zone->getChild() as $sub_zone)
    //                $out.=Template::writeHTMLTemplateCode($sub_zone,$var_name,false);
    //
    //        return $out;
    //
    //    }
    //    private static function getTemplateVar($zone)
    //    {
    //        $s=split(" ",$zone->name);
    //        $s[0]=preg_replace('/[^a-zA-Z0-9]/', '', $s[0]);
    //        return "\$z".$zone->id.$s[0];
    //    }
    //    private static function writeHTMLTemplateZone($zone,$container)
    //    {
    //
    //        $out="";
    //
    //        $var_name=Template::getTemplateVar($zone);
    //
    //        $var_define=$var_name."=";
    //        $var_use="";
    //
    //        $class_name=HTML::getClassNameFromZone($zone);
    //        if(class_exists($class_name))
    //        {
    //            $var_define.="new ".$class_name."();".(DEBUG?"\n":"");
    //
    //            foreach ($zone->getAttributes(false,true) as $name => $value)
    //                if(strtolower($name)=="iscontainer")
    //                {
    //                    if($value=="Y")
    //                        $var_use.="\$output=".$var_name.";".(DEBUG?"\n":"");
    //
    //                }
    //                elseif(strtolower($name)=="tag" || strtolower($name)=="function"){}
    //                elseif(strtolower($name)=="module")
    //                {
    //                    $var_use.="\$autoCont=new SPANContainer('z".$zone->id."');".(DEBUG?"\n":"");
    //                    $var_use.="getModule('".$value."')->".$zone->get("Function","")."(\$autoCont);".(DEBUG?"\n":"");
    //                    $var_use.=$var_name."->addChild(\$autoCont);".(DEBUG?"\n":"");
    //                }
    //                else
    //                    $var_use.=$var_name."->setAttr('".$name."','".$value."');".(DEBUG?"\n":"");
    //
    //            $z_style=getZone($zone->driver, "Style",$zone, true);
    //            foreach ($z_style->getAttributes(false,true) as $name => $value)
    //                $var_use.=$var_name."->setStyleAttr('".$name."','".$value."');".(DEBUG?"\n":"");
    //
    //        }
    //        else if(strStartsWith($zone->name, "##TEXT##"))
    //            $var_define.="Zone::getZone('".$zone->id."',getDBDriver('System'));".(DEBUG?"\n":"");
    //        else
    //            $var_define.="'".$zone->name."';".(DEBUG?"\n":"");
    //
    //        $add_cont=$container."->addChild(&".$var_name.");";
    //
    //        $out.=$var_define;
    //        $out.=$var_use;
    //        $out.=$add_cont.(DEBUG?"\n":"");
    //
    //        return $out;
    //
    //    }
    //    private static function loadHTMLFileloop($node,$zone,$is_first)
    //    {
    //
    //        if($node->nodeName=="#comment")
    //            return;
    //
    //        if($is_first===false)
    //        {
    //            $zn=$node->nodeName;
    //            if($zn=="#text")
    //            {
    //                $zn="##TEXT##";
    //                $tc=$node->textContent;
    //                $tc=str_replace("\n", "", $tc);
    //                $tc=str_replace("\r", "", $tc);
    //                $tc=trim($tc);
    //                if($tc=="")
    //                    return;
    //            }
    //            else
    //            {
    //                $class_name=HTML::getClassNameFromZone($zone);
    //                if(!class_exists($class_name))
    //                    trigger_error("Class not found:".$class_name, E_USER_ERROR);
    //            }
    //            $zn=HTML::getZoneNameFromTag($zn);
    //
    //            $sub_zone=getZone($zone->driver,$zn,$zone);
    //
    //            if(strStartsWith($zn, "##TEXT##"))
    //                $sub_zone->setText($tc);
    //            else
    //                $sub_zone->set("Tag",$node->nodeName);
    //
    //        }
    //        else
    //            $sub_zone=$zone;
    //
    //        if($node->hasAttributes())
    //            foreach ($node->attributes as $attr)
    //                $sub_zone->set($attr->name,$attr->value);
    //
    //        if($node->hasChildNodes())
    //            foreach ($node->childNodes as $sub_node)
    //                Template::loadHTMLFileloop($sub_node,$sub_zone,false);
    //
    //    }
    //
    //    public static function loadZoneFromHTMLElement($zone,$element)
    //    {
    //        $zone=Zone::getZoneByName(HTML::getZoneNameFromTag($element->getTag()), $zone);
    //        foreach ($element->getAttrs() as $key => $value)
    //            $zone->set($key,$value);
    //
    //        foreach ($element->getChilds() as $chl)
    //            Template::loadZoneFromHTMLElement($zone, $chl);
    //
    //        return true;
    //    }
}
