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
class FormText extends HTMLElement
{
    public $title;
    public $value;
    public $base_name;

    public function __construct($title, $name, $value)
    {
        $this->base_name = $name;

        $this->title = $title;
        $this->setAttr('title', $this->title);
        $this->setAttr('alt', $this->title);

        $this->setAttr('name', 'blk_form_'.$this->base_name);

        $this->value = $value;
        $this->setAttr('value', $this->value);

        $this->tag = 'input';
        $this->closeMode = 0;
        $this->setAttr('type', 'text');

        $this->setAttr('onblur', 'return blk_form_change(this);');
        $this->setAttr('onchange', 'return blk_element_change(this);');
    }

    public function getHTML()
    {
        return $this->toHtml();
        //return "<input type=text name='$this->name' onblur='return blk_form_change(this);' onchange='return blk_element_change(this);' value='".$this->value."' id='".$this->id."' />";
    }
}
