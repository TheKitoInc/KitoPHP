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
class FormHidden extends HTMLElement
{
    public $title;
    public $name;
    public $value;
    public $base_name;

    public function __construct($title, $name, $value)
    {
        $this->base_name = $name;

        $this->title = $title;

        $this->name = 'blk_form_'.$this->base_name;
        $this->setAttr('name', $this->name);

        $this->value = $value;
        $this->setAttr('value', $this->value);

        $this->tag = 'input';
        $this->closeMode = 0;
        $this->setAttr('type', 'hidden');
    }

    public function getHTML()
    {
        return $this->toHtml();
        //        return "<input type=hidden name='$this->name' value='".$this->value."' id='".$this->id."' />";
    }
}
