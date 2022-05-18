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
 * rss.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class item
{
    public $category = [];
    public $params = [];
    public $images = [];
    public $keys;

    public function __construct($item, &$keys)
    {
        $this->keys = &$keys;
        foreach ($item->childNodes as $element) {
            if (!isset($element->tagName) || $element->tagName == '') {
            } elseif ($element->tagName == 'category') {
                array_push($this->category, $element->textContent);
            } elseif ($element->tagName == 'content:encoded') {
                $this->images = $this->getImages($element->textContent);
            } else {
                $this->params[$element->tagName] = $element->textContent;
            }
        }
    }

    public function getImages($xml)
    {
        $list = [];
        $xml_dom = new DOMDocument();
        $xml_dom->loadHTML($xml);
        //$xml_dom->loadHTML(str_ireplace($xml, "&amp;", "&"));
        foreach ($this->explore($xml_dom->getElementsByTagName('*'), 'img') as $lop) {
            $url = $lop->getAttribute('src');
            if (!isset($this->keys['url_filter']) || stripos($url, $this->keys['url_filter']) !== false) {
                array_push($list, $url);
            }
        }

        return $list;
    }

    public function explore($list_elements, $to_find)
    {
        $list = [];
        foreach ($list_elements as $element) {
            if (strtolower($element->tagName) == strtolower($to_find)) {
                array_push($list, $element);
            }
            //  foreach ($this->explore($element->getElementsByTagName("*"),$to_find) as $elementos)
            //      array_push($list, $elementos);
        }

        return $list;
    }

    public function toHTML()
    {
        if (!loadModule('Gui')) {
            trigger_error('Gui module not found', E_USER_ERROR);

            return '';
        }

        $html = '<p class=blk_rss_post>';
        $html .= callFunction('Gui', 'A', [$this->params[$this->keys['link']], [$this->params[$this->keys['title']]], ['class' => 'blk_rss_post_title']]);
        $html .= '<br>';

        $alt = false;
        foreach ($this->images as $image) {
            $alt = !$alt;
            $class = $alt ? 'blk_rss_post_image_a' : 'blk_rss_post_image_b';

            $html .= callFunction('Gui', 'Img', [$image, ['class' => $class]]);
        }

        $html .= callFunction('Gui', 'Font', [[$this->params[$this->keys['head']]], ['class' => 'blk_rss_post_header']]);
        $html .= callFunction('Gui', 'Font', [[$this->params[$this->keys['owner']]], ['class' => 'blk_rss_post_owner']]);
        $html .= callFunction('Gui', 'Font', [[$this->params[$this->keys['date']]], ['class' => 'blk_rss_post_date']]);
        $html .= '</p>';

        return $html;
    }

    public function toXML()
    {
        $html = '';
        $html .= '<item>';
        $html .= '<'.$this->keys['title'].'>'.$this->params[$this->keys['title']].'</'.$this->keys['title'].'>';
        $html .= '<'.$this->keys['link'].'>'.$this->params[$this->keys['link']].'</'.$this->keys['link'].'>';
        $html .= '<guid>'.$this->params[$this->keys['link']].'</guid>';
        $html .= '<'.$this->keys['date'].'>'.$this->params[$this->keys['date']].'</'.$this->keys['date'].'>';
        $html .= '<'.$this->keys['head'].'>[CDATA[ '.$this->params[$this->keys['head']].' ]]</'.$this->keys['head'].'>';
        $html .= '<content:encoded>';

        foreach ($this->images as $image) {
            $html .= "<img src='".$image."'>";
        }

        $html .= '</content:encoded>';
        $html .= '</item>';

        return $html;
    }
}

class channel
{
    public $keys;
    public $name = null;
    public $items = null;

    public function __construct($name, $items, &$keys)
    {
        $this->keys = &$keys;
        $this->name = $name;
        $this->items = $this->getItems($items);
    }

    public function getItems($items_)
    {
        $items = [];
        foreach ($items_ as $ite) {
            array_push($items, new item($ite, $this->keys));
        }

        return $items;
    }

    public function toHTML()
    {
        $HTML = '';
        foreach ($this->items as $chn) {
            $HTML .= $chn->toHTML().'<br>';
        }

        return $HTML;
    }

    public function toXML()
    {
        $HTML = '<channel><title>'.$this->name.'</title>';
        foreach ($this->items as $chn) {
            $HTML .= $chn->toXML();
        }

        return $HTML.'</channel>';
    }
}
class rssfile
{
    public $keys;
    public $xml = null;
    public $channels = null;

    public function __construct()
    {
        $this->xml = new DOMDocument();
    }

    public function load($url)
    {
        $r = $this->xml->load($url);
        $this->channels = $this->getChannels();

        return $r;
    }

    public function getChannels()
    {
        $channels = [];
        $chns = $this->xml->getElementsByTagName('channel');
        foreach ($chns as $chn) {
            array_push($channels, new channel($chn->getElementsByTagName('title')->item(0)->textContent, $chn->getElementsByTagName('item'), $this->keys));
        }

        return $channels;
    }

    public function toHTML()
    {
        $HTML = '';
        foreach ($this->channels as $chn) {
            $HTML .= $chn->toHTML().'<br>';
        }

        return $HTML;
    }

    public function toXML()
    {
        $HTML = "<xml version='1.0' encoding='utf-8'?><rss version='2.0'>";
        foreach ($this->channels as $chn) {
            $HTML .= $chn->toXML();
        }

        return $HTML.'</rss>';
    }
}
