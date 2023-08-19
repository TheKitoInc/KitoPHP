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
HTML::loadTemplate(dirname(__FILE__).'/DefaultTemplate/');
//Structure::getStructure("Default")->loadHTMLFile(file_get_contents(dirname(__FILE__)."/Default.html"));
//Style::getStyle("Default")->loadFromCSS(file_get_contents(dirname(__FILE__)."/Default.css"));

$r = Repeater::getRepeater('Articles');
$r->setContainer(Structure::getStructure('Article'));
