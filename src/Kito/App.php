<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kito;

use Psr\Http\Message\ResponseInterface;
/**
 * Description of App
 *
 * @author Instalacion
 */
class App
{

    public function emit(ResponseInterface $response)
    {
        while (ob_clean())
        {
            //clear all buffers;
        }; 

        (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);        
        exit();
    }

}
