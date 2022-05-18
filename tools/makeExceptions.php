<?php

define('base', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
define('baseDir', base.'src');
define('baseVendor', 'Kito');

scan(baseDir.DIRECTORY_SEPARATOR);
//file_put_contents(base . 'tests' .DIRECTORY_SEPARATOR .'ExceptionTest.php', $out);
function scan($path)
{
    checkEx($path.'Exception.php');

    foreach (scandir($path) as $name) {
        if ($name == '.') {
            continue;
        }
        if ($name == '..') {
            continue;
        }

        $subPath = $path.$name;

        if (is_dir($subPath)) {
            scan($subPath.DIRECTORY_SEPARATOR);
        }
    }
}

function checkEx($path)
{
    if (file_exists($path)) {
        return;
    }

    $ns = dirname(str_replace('/', '\\', baseVendor.substr(substr($path, 0, -4), strlen(baseDir))));

    file_put_contents($path, getCode($ns)); 
}

function getCode($ns)
{
    return '<?php

/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 */

namespace '.$ns.';

/**
 *
 * @author TheKito < blankitoracing@gmail.com >
 */
class Exception extends \\'.dirname($ns).'\\Exception {
    
}
';
}
