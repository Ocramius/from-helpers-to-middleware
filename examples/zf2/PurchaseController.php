<?php

namespace App;

use Zend\Mvc\Controller\AbstractActionController;
use App\Helper\IsCoyote;
use App\Helper\NotAllowed;
use App\Helper\PurchaseForm;
use App\Helper\GetProduct;
use App\Helper\Notifications;
use App\Helper\CurrentUser;

class PurchaseController extends AbstractActionController
{
    /**
     * @var IsCoyote
     */
    private $isCoyote;
    /**
     * @var NotAllowed
     */
    private $notAllowed;
    /**
     * @var PurchaseForm
     */
    private $form;
    /**
     * @var GetProduct
     */
    private $getProduct;
    /**
     * @var Notifications
     */
    private $notifications;
    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        IsCoyote $isCoyote,
        NotAllowed $notAllowed,
        PurchaseForm $form,
        GetProduct $getProduct,
        CurrentUser $currentUser,
        Notifications $notifications
    ) {
        $this->isCoyote = $isCoyote;
        $this->notAllowed = $notAllowed;
        $this->form = $form;
        $this->getProduct = $getProduct;
        $this->currentUser = $currentUser;
        $this->notifications = $notifications;
    }

    public function purchaseAction()
    {
        $request = $this->getRequest(); // magic mvc stuff :-(

        if (! $this->isCoyote->isCoyote($request)) {
            return $this->notAllowed->notAllowed($request);
        }

        $this->form->setData($request->getPost());

        if (! $this->form->isValid($this->getPost())) {
            return ['form' => $this->form];
        }

        $data = $this->form->getData();

        $product = $this->getProduct->get($data['product']);
        $product->purchase(); // magic

        $this->notifications->notify(
            $this->currentUser->get($request),
            'Purchase completed'
        );

        return ['success' => true];
    }
}
