<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IsCoyote implements MiddlewareInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $notAllowed;

    public function __construct(MiddlewareInterface $notAllowed)
    {
        $this->notAllowed = $notAllowed;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        if (! $this->isCoyote($request)) {
            return $this->notAllowed->process($request, $delegate);
        }

        return $delegate->process($request);
    }

    private function isCoyote(ServerRequestInterface $request) : bool
    {
        // ...
    }
}
