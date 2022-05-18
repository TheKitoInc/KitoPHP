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
class Form extends Module
{
    public function __construct()
    {
        getModule('HTML');
        include_once 'class.form.php';
        include_once 'class.hidden.php';
        include_once 'class.text.php';
        include_once 'class.submit.php';
        include_once 'class.select.php';
    }

    public function __destruct()
    {
    }

    public function __load()
    {
        if (getParam('Module') == 'Form' && getParam('Tag') == 'IForm') {
            $z = Form::getFormZone(getParam('token'));

            $module = $z->get('Module', '');

            $valids = [];
            $no_valids = [];
            $warn = [];
            global $FORM_PARAMS;
            foreach ($FORM_PARAMS as $name => $value) {
                if (!strEndsWith($name, '_BASE')) {
                    if (isset($FORM_PARAMS[$name.'_BASE'])) {
                        if ($FORM_PARAMS[$name.'_BASE'] != $value) {
                            $res = callFunction($module, 'Form_Check', [substr($name, 9), $value]);
                            if ($res === true) {
                                $valids[$name] = $value;
                            } elseif ($res === false) {
                                $no_valids[$name] = $FORM_PARAMS[$name.'_BASE'];
                            } else {
                                $no_valids[$name] = $FORM_PARAMS[$name.'_BASE'];
                                $warn[$name] = $res;
                            }
                        }
                    }
                }
            }

            $params = [];
            foreach ($valids as $name => $value) {
                $params[substr($name, 9)] = $value;
            }

            $n_token = Form::getToken();
            Form::setModule($n_token, $module);
            $zh = Form::getFormHiddenZone(getParam('token'));

            foreach ($zh->getAttributes() as $attr) {
                $params[substr($attr, 9)] = $zh->get($attr, '');
                Form::setHidden($n_token, $attr, $zh->get($attr, ''));
            }

            if (!callFunction($module, 'Form_Save', [$params])) {
                foreach ($valids as $name => $value) {
                    $no_valids[$name] = $FORM_PARAMS[$name.'_BASE'];
                }

                $valids = [];
            }

            write('<script language=javascript>');

            foreach ($valids as $name => $value) {
                write("update_form_element('".getParam('Target')."','".getParam('token')."','".$name."','".$value."','Y');");
            }

            foreach ($no_valids as $name => $value) {
                $res = 'N';
                if (isset($warn[$name])) {
                    $res = $warn[$name];
                }

                write("update_form_element('".getParam('Target')."','".getParam('token')."','".$name."','".$value."','".$res."');");
            }

            write("update_form_element('".getParam('Target')."','".getParam('token')."','".'blk_form_token'."','".$n_token."','');");

            write('</script>');

            $z->delete(true);
        }
    }

    private static function getFormsZone()
    {
        return getZone(getThisSessionZone()->driver, 'Forms', getThisSessionZone(), true);
    }

    private static function getFormZone($token)
    {
        return getZone(Form::getFormsZone()->driver, $token, Form::getFormsZone(), false);
    }

    private static function getFormHiddenZone($token)
    {
        return getZone(Form::getFormZone($token)->driver, 'Hidden', Form::getFormZone($token), true);
    }

    public static function getToken()
    {
        static $c = 0;
        $c++;
        $t = timeGetTime(true).$c;

        return $t;
    }

    public static function setHidden($token, $name, $value)
    {
        $z = Form::getFormHiddenZone($token);

        return $z->set($name, $value);
    }

    public static function setModule($token, $module)
    {
        $z = Form::getFormZone($token);

        return $z->set('Module', $module);
    }

    public function __unload()
    {
    }
}
