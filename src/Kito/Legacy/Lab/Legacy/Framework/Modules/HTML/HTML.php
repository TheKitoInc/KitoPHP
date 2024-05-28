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
 * 
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class HTML extends Module
{

    var $html=false;
    var $head=false;
    var $body=false;
    var $container=false;    
    var $main_structure=false;
    public function setTitle($title)
    {
        $this->head->addChild(new HTMLtitle($title));

        $meta=new HTMLmeta($title);
        $meta->setAttr("name", "title");
        $this->head->addChild($meta);
    }

    public static function Link($href,$class,$body)
    {
        $a=new HTMLa($href, $body);
        return $a;
    }
    
    public function __construct()
    {
        include_once 'class.style.php';
        include_once 'class.element.php';
        include_once 'class.template.php';
        include_once 'class.repeater.php';
        include_once 'class.structure.php';

        autoLoadClasses(dirname(__FILE__).'/Elements/');
        autoLoadClasses(dirname(__FILE__).'/BlkElements/');
        
        $this->html=new HTMLhtml();
        $this->head=new HTMLhead();
        $this->body=new HTMLbody();

        $this->html->addChild(&$this->head);
        $this->html->addChild(&$this->body);

        $this->container=new SPANContainer("BLKMainContainer");       

        write("<!DOCTYPE html>");
        global $output;
        $output=$this;        
    }
    public function __destruct()
    {
    }
    public function __load()
    {
        if (getSessionValue("Iframe", "?")!="N") {
            $sysframename="iframe".uniqid();
            if(getParam("Tag")===false) {
                $iframe_sys=new HTMLiframe("./?Module=HTML&Tag=Iframe");
            } else if (getSessionValue("Javascript", "N")=="Y") {
                $iframe_sys=new HTMLiframe("about:blank");
            }
            $iframe_sys->setStyleAttr("display", "none");
            $iframe_sys->setAttr("name", $sysframename);
            $this->body->addChild($iframe_sys);
        }

        if (getSessionValue("Javascript", "N")=="Y") {
            $script=new HTMLscript();
            if (getSessionValue("Iframe", "?")!="N") {
                $script->addChild("var blk_html_frame_name='".$sysframename."';");
            }
            $this->head->addChild($script);
            foreach (getModule(null) as $mod => $val) {
                $this->head->addChild(new HTMLscript("?Module=$mod&Tag=Script"));
            }
        }

        if(getParam("Tag")==="Iframe") {
            setSessionValue("Iframe", "Y");
            $meta=new HTMLmeta((getSessionsZone()->get("MaxSeconds", 20)/2));
            $meta->setAttr("http-equiv", "refresh");
            $this->head->addChild($meta);
        }
        else {
            $this->setTitle(getValue("Title", "BLK Application")." | ".getViewZone()->getTitle());
        }


        include 'setup.php';

        //TEST AREA//
        $s=Style::getStyle("default_1");
        $sty=new HTMLstyle();
        $sty->addChild($s->downloadFromCSS());
        $this->head->addChild($sty);

        $a=new HTMLa("#");
        $a->addChild("Link");
        $t=new BLKMenu(array($a,$a,$a,array($a,$a,$a,$a,$a,array($a,$a,$a,$a)),$a,$a));
        $this->container->addChild($t);
        $this->container->addChild(' <input type="month"');
        /////////////
    }

    public function write($data)
    {
        return $this->container->addChild($data);
    }
    
    public function setStructure($name)
    {
        $this->main_structure=Structure::getStructure($name);
    }

    public function __unload()
    {
        global $output;
        $output=null;

        if($this->main_structure===false) {
            $this->main_structure=Structure::getStructure("Default");
        }

        $this->main_structure->setElement("blkmain", $this->container);

        $this->body->addChild($this->main_structure->doStructure());

        if (getParam("Mode")==="Preload" && getSessionValue("Javascript", "N")=="Y") {
            $script=new HTMLscript();
            foreach (SPANContainer::Containers() as $name) {
                $script->addChild("blk_html_span_update('".$name."');");
            }
            $this->html->addChild($script);
        }

        write($this->html->toHtml(true));

        if (getSessionValue("Iframe", "?")=="?") {
            setSessionValue("Iframe", "N");
        }
    }
    public static function getZoneNameFromTag($tag)
    {
        static $counts=array();
        if(!isset($counts[$tag])) {
            $counts[$tag]=0;
            return $tag;
        }
        else
        {
            $counts[$tag]++;
            return $tag.$counts[$tag];
        }
    }
    public static function getClassNameFromZone($zone)
    {
        return "HTML".strtolower($zone->get("Tag", $zone->name));
    }
    public static function removeDocType($htmltext)
    {
        return preg_replace('#</?'."!doctype".'(>|\s[^>]*>)#', '', $htmltext);;
    }
    public static function getVarFromZone($zone)
    {
        $s=split(" ", $zone->name);
        $s[0]=preg_replace('/[^a-zA-Z0-9]/', '', $s[0]);
        return "\$z".$zone->id.$s[0];
    }
    public static function loadTemplate($path)
    {
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle)))
            {
                $f=explode(".", strtolower($file));

                if ($f[0]=="structure" && $f[2]=="html") {
                    Structure::getStructure($f[1])->loadHTMLFile(file_get_contents($path.$file));
                } else if ($f[0]=="style" && $f[2]=="css") {
                    Style::getStyle($f[1])->loadFromCSS(file_get_contents($path.$file));
                }
            }
            closedir($handle);
        }
    }
}


?>
