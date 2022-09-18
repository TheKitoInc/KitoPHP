<?php

declare(strict_types=1);
/**
 * KitoApp.
 *
 * php version 7.2.
 *
 * @category Request/Response PSR Handler
 *
 * @author  The Kito <thekito@blktech.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU GPL
 *
 * @link https://github.com/TheKito/KitoPHP
 */

namespace Kito;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
        $this->responseEmitter = new Http\Server\Response\SapiEmitter();

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
        $this->responseEmitter->emit($this->response);
        exit();
    }
}
