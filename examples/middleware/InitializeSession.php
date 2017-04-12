<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class InitializeSession implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return $delegate->process($request->withAttribute('session', $this->loadSession($request)));
    }

    private function loadSession(ServerRequestInterface $request) : array
    {
        // ...
    }
}
