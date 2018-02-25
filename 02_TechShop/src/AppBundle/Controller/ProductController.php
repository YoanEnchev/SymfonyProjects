<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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

        $form = $this->createForm(SinglePurchase::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('purchase/singlePurchase.html.twig',
            array('product' => $product,
                'form' => $form->createView()));
    }
}
