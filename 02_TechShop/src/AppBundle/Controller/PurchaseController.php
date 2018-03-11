<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductInCart;
use AppBundle\Entity\ProductPurchase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PurchaseController extends Controller
{
    /**
     * @Route("admin/purchases", name="listAllPurchases")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllPurchases()
    {
        $repo = $this->getDoctrine()->getRepository(ProductPurchase::class);
        $purchases = $repo->findAll();

        return $this->render('purchase/purchases.html.twig', array(
            'purchases' => $purchases
        ));
    }
}
