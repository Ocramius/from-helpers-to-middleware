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

        if (! $validationResult->isValid()) {
            return $this->validationError->process(
                $request->withAttribute('validationResult', $validationResult),
                $delegate
            );
        }

        return $delegate->process($request->withAttribute('validationResult', $validationResult));
    }
}
