<?php

class PurchaseController extends Zend_Controller_Action
{
    public function purchaseAction()
    {
        if (! $this->isCoyote()) {
            return $this->notAllowed();
        }

        $form = new PurchaseForm();

        if (! $form->isValid($this->getPost())) {
            return $this->render(['form' => $form]);
        }

        $data = $form->getData();

        $product = $this->loadProduct($data['product']);
        $product->purchase(); // magic

        $this->mail($this->user(), 'Purchase completed');

        return $this->render(['success' => true]);
    }
}
