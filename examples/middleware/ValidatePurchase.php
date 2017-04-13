<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class ValidatePurchase implements MiddlewareInterface
{
    /**
     * @var PurchaseForm
     */
    private $form;
    /**
     * @var MiddlewareInterface
     */
    private $validationError;

    public function __construct(PurchaseForm $form, MiddlewareInterface $validationError)
    {
        $this->form = $form;
        $this->validationError = $validationError;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        $validationResult = $this->form->validate($request);
        $request = $request->withAttribute('validationResult', $validationResult);

        if (! $validationResult->isValid()) {
            return $this->validationError->process($request, $delegate);
        }

        return $delegate->process($request, $delegate);
    }
}
