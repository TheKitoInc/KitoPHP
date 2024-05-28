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
class FormSelect extends HTMLElement
{
    //    var $array=array();
    //    var $use_index=false;

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

        $this->tag = 'select';
        $this->closeMode = 0;

        $this->setAttr('onchange', 'blk_form_change(this); return blk_element_change(this);');
    }

    public function setList($array, $use_index = false)
    {
        //$this->array=$array;
        //$this->use_index=$use_index;

        foreach ($array as $key => $value) {
            if ($use_index === false) {
                $html = "<option value='".$value."' ".($value == $this->value ? 'selected' : '').">$value</option>";
            } else {
                $html = "<option value='".$key."' ".($key == $this->value ? 'selected' : '').">$value</option>";
            }

            $this->addChild($html);
        }
    }

    public function getHTML()
    {
        //$html="";
        //$html.="<select name='$this->name' id='".$this->id."' onchange='blk_form_change(this); return blk_element_change(this);'>";

        //$html.="</select>";
        //return $html;
        return $this->toHtml();
    }
}
