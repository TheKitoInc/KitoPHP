<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserMessage.
 *
 * @author andresrg
 */
class UserMessage extends Message
{
    public static function newMessage($user_context)
    {
        return parent::newMessage($user_context);
    }

    public function newResponse($user_context)
    {
        $m = parent::newResponse();
        $m->base_zone->link($user_context->get('Messages'));

        return $m;
    }
}
