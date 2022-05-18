<?php

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

namespace Kito\Router;

use Kito\Type\Path;

/**
 * @author TheKito < blankitoracing@gmail.com >
 */
class ProxyRouter
{
    public static function getFromGlobals()
    {
        return new ProxyRouter(
            Path::getFromString($_SERVER['DOCUMENT_ROOT']),
            Path::getFromString($_SERVER['REQUEST_URI']),
            Path::getFromString('index.php'),
        );
    }

    private $documentROOT;
    private $requestURI;
    private $routerPath;

    public function __construct(Path $documentROOT, Path $requestURI, Path $routerPath)
    {
        $this->setDocumentROOT($documentROOT);
        $this->setRequestURI($requestURI);
        $this->setRouterPath($routerPath);
    }

    public function getDocumentROOT(): Path
    {
        return $this->documentROOT;
    }

    public function getRequestURI(): Path
    {
        return $this->requestURI;
    }

    public function setDocumentROOT(Path $documentROOT): void
    {
        $this->documentROOT = $documentROOT;
    }

    public function setRequestURI(Path $requestURI): void
    {
        $intPos = strpos($requestURI->getName(), '?');

        if ($intPos !== false) {
            $requestURI->setName(substr($requestURI->getName(), 0, $intPos));
        }

        $this->requestURI = $requestURI;
    }

    public function getPath(): Path
    {
        return $this->documentROOT->combine($this->requestURI);
    }

    public function getRouterPath(): Path
    {
        return $this->routerPath;
    }

    public function setRouterPath(Path $routerPath): void
    {
        $this->routerPath = $routerPath;
    }

    public function route()
    {
        $path = $this->getPath();

        while ($path->getDeep() > $this->documentROOT->getDeep()) {
            $routerPath = $path->combine($this->routerPath);

            if (file_exists($routerPath)) {
                require_once $routerPath;

                return;
            }

            $path = $path->getParent();
        }
    }
}
