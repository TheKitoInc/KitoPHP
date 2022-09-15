<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kito;

/**
 * Description of App.
 *
 * @author Instalacion
 */
class App
{
    private $psr17Factory;
    private $request;
    private $response;

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response): void
    {
        $this->response = $response;
    }

    public function __construct()
    {
        ob_start();

        $this->psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
            $this->psr17Factory, // ServerRequestFactory
                $this->psr17Factory, // UriFactory
                $this->psr17Factory, // UploadedFileFactory
                $this->psr17Factory  // StreamFactory
        );

        $this->request = $creator->fromGlobals();
        $this->response = $this->psr17Factory->createResponse(418)->withBody($this->psr17Factory->createStream("I'm a teapot"));
    }

    public function __destruct()
    {
        //clear all buffers;
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        (new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($this->response);
        exit();
    }
}
