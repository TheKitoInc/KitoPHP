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
class FormSubmit extends HTMLElement
{
    public $title;
    public $name;
    public $value;
    public $base_name;

    public function __construct($title, $name, $value)
    {
        $this->base_name = $name;

        $this->title = $title;
        $this->setAttr('title', $this->title);
        $this->setAttr('alt', $this->title);

        $this->name = 'blk_form_'.$this->base_name;
        $this->setAttr('name', $this->name);

        $this->value = $value;
        $this->setAttr('value', $this->value);
    }

    public function getHTML()
    {
        $id = HTMLElement::getId('blk_submit');

        if (getSessionValue('Javascript', 'N') == 'N') {
            return "<input type=submit value='".$this->title."'/>";
        } else {
            return "<input value='".$this->title."' type=button onclick='blk_submit_form(blk_get_form(this));' id='".$id."' />".'<script language=javascript>document.getElementById("'.$id.'").style.display="none";</script>';
        }
    }
}
