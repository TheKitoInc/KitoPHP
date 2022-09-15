<?php

declare(strict_types=1);
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 */

namespace Kito;

use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Description of App.
 *
 * @author Instalacion
 */
class App
{

    private $request;
    private $response;

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    protected function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function __construct()
    {
        ob_start();

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
                $psr17Factory, // ServerRequestFactory
                $psr17Factory, // UriFactory
                $psr17Factory, // UploadedFileFactory
                $psr17Factory  // StreamFactory
        );

        $this->setRequest($creator->fromGlobals());
        $this->setResponse($psr17Factory->createResponse(418)->withBody($psr17Factory->createStream("I'm a teapot")));
    }

    public function __destruct()
    {
        //clear all buffers;
        while (ob_get_level() > 0)
        {
            ob_end_clean();
        }

        (new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($this->response);
        exit();
    }
}
