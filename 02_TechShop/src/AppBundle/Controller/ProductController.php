<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductPurchase;
use AppBundle\Form\SinglePurchase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends Controller
{
    /**
     * @Route("buy/singleProduct/{id}", name="buySingleProduct")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buySingleItem($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Product::class);
        $product = $repo->find($id);

        $purchase = new ProductPurchase();

        $form = $this->createForm(SinglePurchase::class, $purchase);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $product = $repo->find($id); //check for last moment out of quantity

            if($product->getQuantity() == 0) {
                return $this->redirectToRoute('outOfStock');
            }

            if($purchase->getQuantity() <= 0) {
                return $this->redirectToRoute('invalidQuantity');
            }

            if($product->getQuantity() - $purchase->getQuantity() < 0) {
                return $this->redirectToRoute('tooMuchQuantity');
            }

            if(!$purchase->cardNumberIsValid()) {
                return $this->redirectToRoute('invalidCardNumberFormat');
            }

            if(!$purchase->validThroughFormatIsValid()) {
                return $this->redirectToRoute('invalidCardDateFormat');
            }

            if(!$purchase->validMonth()) {
                return $this->redirectToRoute('invalidMonth');
            }

            if($purchase->cardHasExpired()) {
                return $this->redirectToRoute('expiredCard');
            }

            $product->setQuantity($product->getQuantity() - $purchase->getQuantity());
            $purchase->setProduct($product);

            $em->persist($product);
            $em->persist($purchase);
            $em->flush();

            return $this->redirectToRoute('afterPurchaseSuccess');
        }

        return $this->render('purchase/singlePurchase.html.twig',
            array('product' => $product,
                'form' => $form->createView()));
    }

    /**
     * @Route("validPurchase", name="afterPurchaseSuccess")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afterValidPurchase()
    {
        return $this->render('thankYouForPurchase.html.twig');
    }
}
