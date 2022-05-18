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
class IDE extends Module
{
    public function __load()
    {
        getModule('HTML')->loadTemplate('IDE');
        if (getParam('Tag') === false) {
            $table = new HTMLtable();
            $table->setAttr('border', '1');
            $table->setAttr('cellpadding', '0');
            $table->setAttr('cellspacing', '0');
            $table->setStyleAttr('width', '100%');
            $table->setStyleAttr('height', '100%');

            $tr_menu = new HTMLtr();
            $tr_menu->setStyleAttr('height', '50px');
            $table->addChild($tr_menu);

            $tr_main = new HTMLtr();
            $table->addChild($tr_main);

            $tr_list = new HTMLtr();
            $tr_list->setStyleAttr('height', '150px');
            $table->addChild($tr_list);

            $td_menu = new HTMLtd();
            $tr_menu->addChild($td_menu);

            $td_main = new HTMLtd();
            $tr_main->addChild($td_main);

            $td_list = new HTMLtd();
            $tr_list->addChild($td_list);

            $td_menu->addChild('menu');
            $td_main->addChild("<iframe name=body frameborder=0 style='width:100%;height:100%' src='?Module=IDE&Tag=Body'></iframe>");
            $td_list->addChild("<iframe frameborder=0 style='width:100%;height:100%' src='?Module=IDE&Tag=List'></iframe>");
            write($table);
        } elseif (getParam('Tag') == 'Body') {
            $zm = getModulesZone();
            foreach ($zm->getChild() as $zm1) {
                $a = new HTMLa('?Module=IDE&Tag=Module&Object='.$zm1->name.'');

                $a->addChild("<img border=0 style='width:40px;height:40px;' src='?Module=".$zm1->name."&Tag=Image&Image=icon.png'>");
                $a->addChild($zm1->name);

                write($a);
            }

            include_once 'class.form.php';
            $a = new ModuleForm();
            write($a->getHTML());
        } elseif (getParam('Tag') == 'Module') {
            write("<table style='width:100%;height:100%' border=0 cellpadding=0 cellspacing=0>");
            write('<tr>');
            write("<td style='width:300px;'>");
            write("<iframe name=menu frameborder=0 style='width:100%;height:100%' src='?Module=IDE&Tag=Menu&Object=".getParam('Object')."'></iframe>");
            write('</td>');
            write('<td>');
            write("<table style='width:100%;height:100%' border=0 cellpadding=0 cellspacing=0>");
            write("<tr style='height:40px;'>");
            write('<td>');
            write("<table style='width:100%;height:100%' border=0>");
            write('<tr>');
            write("<td style='width:40px;' valign=top>");
            write(HTML::Link('?Module=IDE&Tag=Body', '', "<img border=0 style='width:40px;height:40px;' src='?Module=IDE&Tag=Image&Image=back.png'>"));
            write('</td>');
            write('<td class=title align=center>');
            write("<img border=0 style='width:60px;height:60px;' src='?Module=".getParam('Object')."&Tag=Image&Image=icon.png'>&nbsp;".getParam('Object'));
            write('</td>');
            write("<td style='width:40px;'></td>");
            write('</tr>');
            write('</table>');
            write('</td>');
            write('</tr>');
            write('<tr>');
            write('<td>');
            write("<iframe name=proxy frameborder=0 style='width:100%;height:100%' src='?Module=IDE&Tag=Proxy&Object=".getParam('Object')."'></iframe>");
            write('</td>');
            write('</tr>');
            write('</table>');
            write('</td>');
            write('</tr>');
            write('</table>');
        } elseif (getParam('Tag') == 'Proxy') {
            $res = callFunction(getParam('Object'), 'getIDEHtml', [getParam('Item')]);
            if ($res !== false) {
                write($res);
            } else {
                write('UPS! Error to get Module Panel ('.getParam('Object').')');
            }
        } elseif (getParam('Tag') == 'Menu') {
            $res = callFunction(getParam('Object'), 'getIDEMenu', [getParam('Item')]);
            if ($res !== false) {
                IDE::writeMenu($res);
            } else {
                write('UPS! Error to get Module Menu ('.getParam('Object').')');
            }
        }
    }

    private static function writeMenu($items)
    {
        write("<table style='width:100%;' border=0>");
        foreach ($items as $key => $value) {
            write('<tr valign=top>');
            write("<td style='width:20px;' valign=top>");
            if (!is_array($value)) {
                write("<img border=0 style='width:20px;height:20px;' src='?Module=".getParam('Object')."&Tag=Image&Image=$key'>");
            } else {
                write('');
            }
            write('</td>');
            write('<td valign=top>');
            if (is_array($value)) {
                IDE::writeMenu($value);
            } else {
                write("<a target=proxy href='?Module=IDE&Tag=Proxy&Object=".getParam('Object')."&Item=$key'>".$value.'</a>');
            }
            write('</td>');
            write('</tr>');
        }
        write('</table>');
    }

    public function __destruct()
    {
    }

    public function __construct()
    {
        getModule('Form');
        getModule('HTML');
    }

    public function Form_Check($name, $value)
    {
        if ($name == 'Module' || $name == 'Module2') {
            return getModule($value) !== false;
        }
    }

    public function Form_Save($params)
    {
        return true;
    }

    public function setup()
    {
        foreach (scandir(dirname(__FILE__).'/../') as $name) {
            if ($name != '..' && $name != '.' && is_dir(dirname(__FILE__)."/../$name")) {
                getModule($name);
            }
        }

        return true;
    }

    public function __unload()
    {
    }
}
