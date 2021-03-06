<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class NotAllowed implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return new HtmlResponse('You are not allowed here', 403);
    }
}
