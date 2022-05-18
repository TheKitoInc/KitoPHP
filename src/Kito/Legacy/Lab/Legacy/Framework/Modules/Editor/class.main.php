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
 * main.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
class Editor extends Module
{
    //put your code here
    public function __construct()
    {
        getModule('HTML');
    }

    public function __destruct()
    {
    }

    public function __load()
    {
        write(
            '<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>'
        );
        write('<form><textarea id=editor name=editor></textarea></form>');
    }

    public function __unload()
    {
    }
}
