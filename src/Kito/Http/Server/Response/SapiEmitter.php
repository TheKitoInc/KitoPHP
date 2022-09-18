<?php

declare(strict_types=1);
/**
 * KitoPHP.
 *
 * php version 7.2.
 *
 * @category Response PSR Handler
 *
 * @author  The Kito <thekito@blktech.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU GPL
 *
 * @link https://github.com/TheKito/KitoPHP
 */

namespace Kito\Http\Server\Response;

use Psr\Http\Message\ResponseInterface;

class SapiEmitter extends \Laminas\HttpHandlerRunner\Emitter\SapiEmitter
{
    public function __construct()
    {
        //create buffer for catch all before main response
        ob_start();
    }

    /**
     * Emits a response for a PHP SAPI environment.
     *
     * Emits the status line and headers via the header() function, and the
     * body content via the output buffer.
     */
    public function emit(ResponseInterface $response): bool
    {
        //clear all buffers;
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        return parent::emit($response);
    }
}
