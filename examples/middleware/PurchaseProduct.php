<?php

namespace App\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use App\Helper\GetProduct;
use App\Helper\Notifications;

final class PurchaseProduct implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var GetProduct
     */
    private $getProduct;
    /**
     * @var Notifications
     */
    private $notifications;

    public function __construct(Renderer $renderer, GetProduct $getProduct, Notifications $notifications)
    {
        $this->renderer = $renderer;
        $this->getProduct = $getProduct;
        $this->notifications = $notifications;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        $this->getProduct->get($request->getAttribute('validationResult')->get('product'));

        $product->purchase(); // still magic - not for this talk

        $this->notifications->notify($request->getAttribute('session')['user'], 'Purchase completed');

        return new HtmlResponse($this->renderer->render(
            'product-purchased',
            ['product' => $product]
        ));
    }
}
