<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 */

/**
 * @author The TheKito < blankitoracing@gmail.com >
 */
class Message
{
    protected $base_zone;

    private function __construct($container_zone, $message_id = null)
    {
        $z = $container_zone->get('Messages');

        if ($message_id == null) {
            $count = $z->getAttribute('Count')->get(0);
            $count++;

            if ($z->getAttribute('Count')->set($count) === false) {
                throw new Exception('Can not create new message id');
            }

            $this->base_zone = $z->get($count);
        } else {
            $this->base_zone = $z->get($message_id);
        }

        $this->base_zone->getAttribute('Type')->set('Message');
    }

    public function getText()
    {
        return $this->getData('Text');
    }

    public function setText($text)
    {
        return $this->setData('Text', $text);
    }

    public function getLink()
    {
        return $this->getData('Link');
    }

    public function setLink($link)
    {
        return $this->setData('Link', $link);
    }

    protected function setData($key, $value)
    {
        return $this->base_zone->getAttribute($key)->set($value);
    }

    protected function getData($key)
    {
        return $this->base_zone->getAttribute($key)->get();
    }

    public function newResponse()
    {
        return new Message($this->z);
    }

    public static function newMessage($container_zone)
    {
        return new Message($container_zone);
    }
}
