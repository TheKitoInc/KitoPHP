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
function doCheck()
{
    if (getParam('Tag') == 'Iframe') {
        //setSessionValue("Iframe", "Y");
        write('<html>');
        write('<head>');
        write('</head>');
        write('<body>');
        write('<script language=javascript>');
        write("var loc='./?Tag=Javascript&Session=".getSessionId().'&Params='.getParam('Params')."';");
        write('parent.location.href=loc;');
        write('</script>');
        write('</body>');
        write('</html>');

        return;
    }

    if (getParam('Tag') == 'Javascript') {
        setSessionValue('Javascript', 'Y');
    }

    if (getParam('Tag') == 'Meta') {
        setSessionValue('Meta', 'Y');
    }

    if (getParam('Tag') == 'Javascript' || getParam('Tag') == 'Meta') {
        $url = './?Session='.getSessionId();
        setSessionValue('Setup', 'Y');
        $p = unserialize(base64_decode(urldecode(getParam('Params'))));
        if (is_array($p)) {
            $u = './?';
            $p['Session'] = getSessionId();
            foreach ($p as $key => $value) {
                $u .= $key.'='.$value.'&';
            }
        }

        header('Location: '.$url);

        return;
    }

    $params = urlencode(base64_encode(serialize($_GET)));
    write('<html>');
    write('<head>');
    write('<title>Loading...</title>');
    write("<meta http-equiv='refresh' content='10;url=./?Tag=Meta&Module=Compatibility&Session=".getSessionId().'&Params='.$params."' />");
    write('</head>');
    write('<body>');
    write('Loading...');
    write('<span style=display:none>');
    foreach (getApplicationZone()->getChild() as $zone) {
        write($zone->getLink());
    }
    write('</span>');
    write('<iframe style=display:none src="./?Tag=Iframe&Module=Compatibility&Session='.getSessionId().'&Params='.$params.'"></iframe>');
    write('<script language=javascript>');
    write("var loc='./?Tag=Javascript&Module=Compatibility&Session=".getSessionId().'&Params='.$params."';");
    write("setTimeout('document.location.href=loc;',5000);");
    write('</script>');
    write('</body>');
    write('</html>');
}
doCheck();
