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
 * form.
 *
 * @author TheKito <blankitoracing@gmail.com>
 */
getModule('HTML');
abstract class HForm extends HTMLElement
{
    public function getHTML()
    {
        $token = Form::getToken();
        Form::setModule($token, $this->getModule());

        $html = '';
        $html .= "<iframe style='display:none' name='form".$token."I'></iframe>";
        $html .= "<form method=post action='?Module=Form&Tag=IForm' target='form".$token."I'>";

        $token_ = new FormHidden('', 'token', $token);
        $html .= $token_->toHtml();

        $html .= '<table>';
        foreach ($this->getElements() as $element) {
            if ($element instanceof FormHidden) {
                Form::setHidden($token, $element->name, $element->value);
            } else {
                $html .= HForm::getHTMLElement($element);
            }
        }
        $html .= '</table>';
        $html .= '</form>';

        return $html;
    }

    private static function getHTMLElement($element)
    {
        $baseHTML = '';

        if (!($element instanceof FormSubmit) && !($element instanceof FormReset) && !($element instanceof FormHidden)) {
            $base = new FormHidden('', $element->base_name.'_BASE', $element->value);
            $baseHTML = $base->toHtml();
        }

        return '<tr><td>'.$element->title.'</td><td>'.$element->toHtml()."<span id='".$element->id."_MSG'></span>".$baseHTML.'</td></tr>';
    }

    abstract protected function getElements();

    abstract protected function getModule();
}
