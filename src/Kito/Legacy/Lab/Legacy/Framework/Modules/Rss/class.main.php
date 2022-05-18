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
function getRssChannelZone($channel)
{
    return getZone(getRssChannelsZone()->driver, $channel, getRssChannelsZone(), false);
}
class Rss extends Module
{
    public function getRssZone()
    {
        return getModuleZone('Rss');
    }

    public function getRssSourcesZone()
    {
        return getZone($this->getRssZone()->driver, 'Sources', $this->getRssZone(), true);
    }

    public function getRssSourceZone($source)
    {
        return getZone($this->getRssSourcesZone()->driver, $source, $this->getRssSourcesZone(), false);
    }

    //
    //    function getRssChannelsZone($source){return getZone($this->getRssSourceZone($source)->driver, "Channels",$this->getRssSourceZone($source),true);}
    //    function getRssChannelZone($source,$channel){return getZone($this->getRssChannelsZone($source)->driver, $channel,getRssChannelsZone($source),false);}

    private function loadRss($url, $name = false)
    {
        if ($name === false) {
            $name = $url;
        }

        $zsur = $this->getRssSourceZone($name);

        if (timeGetTime() - $zsur->get('Time', '0') < 3600) {
            return true;
        }

        include_once 'class.rss.php';
        $obj = new rssfile();

        $obj->keys['link'] = $zsur->get('link', 'link');
        $obj->keys['title'] = $zsur->get('title', 'title');
        $obj->keys['head'] = $zsur->get('head', 'description');
        $obj->keys['owner'] = $zsur->get('owner', 'dc:creator');
        $obj->keys['date'] = $zsur->get('date', 'pubDate');

        //$obj->keys["url_filter"]=parse_url($url,PHP_URL_HOST);

        $zsur->set('url', $url);
        if ($obj->load($url)) {
            $zsur->set('Time', timeGetTime());
            $zchans = getZone($zsur->driver, 'Channels', $zsur, true);
            foreach ($obj->getChannels() as $chn) {
                $zchn = getZone($zchans->driver, $chn->name, $zchans, false);
                foreach ($chn->items as $item) {
                    $zit = getZone($zchn->driver, $item->params['title'], $zchn, false);
                    if (count($zit->getAttributes(false)) >= 1) {
                        $zit->set('Time', timeGetTime());
                        foreach ($item->params as $name => $value) {
                            $zit->set($name, $value);
                        }

                        $zimg = getZone($zit->driver, 'Images', $zit, false);
                        foreach ($item->images as $name => $value) {
                            getZone($zimg->driver, "Image$name", $zimg, false)->set('url', $value);
                        }

                        $zcat = getZone($zit->driver, 'Categories', $zit, false);
                        foreach ($item->category as $name => $value) {
                            getZone($zcat->driver, $value, $zcat, false);
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }

    public function addSource($name, $url)
    {
        return $this->loadRss($url, $name);
    }

    public function delSource($name)
    {
        return getRssSourceZone($name)->delete(true);
    }

    public function getChannels()
    {
        $r = [];
        foreach ($this->getRssSourcesZone()->getChild() as $zsur) {
            foreach (getZone($zsur->driver, 'Channels', $zsur, true)->getChild() as $chn) {
                $r[$chn->id.'.'.$chn->name] = $chn;
            }
        }

        return $r;
    }

    public function __construct()
    {
        foreach ($this->getRssSourcesZone()->getChild() as $chn) {
            $this->loadRss($chn->get('url', '?'));
        }
    }

    public function __destruct()
    {
    }

    public function __load()
    {
    }

    public function getIDEMenu()
    {
        return getModule('Zones')->getIDEMenu($this->getRssZone(), 4);
    }

    public function __unload()
    {
    }

    public function setup()
    {
        $this->addSource('ALT1040', 'http://feeds.feedburner.com/alt1040');

        return true;
    }
}
