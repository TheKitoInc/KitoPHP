<?php
/*
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
class Logger extends Module
{
    //$TotalTTL=0;
    public function Log($Name, $Value)
    {
        static $handle = false;
        static $handle2 = false;
        if (!$handle) {
            $handle = fopen('myfile.txt', 'a');
        }

        if (!$handle2) {
            $handle2 = fopen('longlog.txt', 'a');
            fwrite($handle2, "=========================================================================\n");
        }

        static $LastTTL = 0;
        $type = $Name;
        //        if ($Name=="ERROR")
        //            $Name="<font color=red>".$Name."</font>";
        //        elseif ($Name=="ALERT")
        //            $Name="<font color=yellow>".$Name."</font>";
        //        else
        //            $Name="<font color=green>".$Name."</font>";

        //        if ($this->DebugMode==true && $_GET["Frame"]!="Sitemap" && $_GET["Frame"]!="JavaScript")
        //        {
        if ($LastTTL == 0) {
            $LastTTL = timeGetTime();
        }

        if ((number_format((timeGetTime()) - $LastTTL, 5)) > 0.09) {
            if ($handle2) {
                if (!fwrite($handle2, 'Logger('.(number_format((timeGetTime()) - $LastTTL, 5)).'): '.$type.': '.$Value."\n")) {
                    exit("couldn't write to file.");
                }
            }
        } else {
            if ($handle) {
                if (!fwrite($handle, 'Logger('.(number_format((timeGetTime()) - $LastTTL, 5)).'): '.$type.': '.$Value."\n")) {
                    exit("couldn't write to file.");
                }
            }
        }

        // if($type!="DEBUG")

        $LastTTL = timeGetTime();
        //        }
        //        else
        //            if ($Name=="ERROR")
        //                if ($this->SYSMailer->SendMail(GetValue("DebugMail","blksoft@gmail.com"),"BLK Debugger: ".$Name.": ".$Value)===false)
        //                    echo "<br>BLK Debugger: ".$Name.": ".$Value;

        return true;
    }

    public function __destruct()
    {
        $this->Log('DEBUG', 'SQL: '.getDBDriver('System')->getStats());
        global $TotalTTL;

        return $this->Log('DEBUG', 'Generation time: '.(timeGetTime() - $TotalTTL));
    }

    public function Logger_ErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($errno != 8 && $errno != 2048 && $errno != 2) {
            Logger_Log('ERROR', $errstr."($errno) File:".$errfile.' Line:'.$errline);
        } else {
            Logger_Log('ALERT', $errstr."($errno) File:".$errfile.' Line:'.$errline);
        }
    }

    public function __construct()
    {
        global $TotalTTL;
        $TotalTTL = timeGetTime();
    }

    public function __load()
    {
    }

    public function __unload()
    {
    }
}
