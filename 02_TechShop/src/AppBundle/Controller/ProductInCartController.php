<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductInCart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductInCartController extends Controller
{
    /**
     * @Route("shoppingCart/{id}/setQuantity/{newQuantity}", name="setQuantity")
     * @param $id
     * @param $newQuantity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setQuantity($id, $newQuantity)
    {
        $repo = $this->getDoctrine()->getRepository(ProductInCart::class);
        $prodInCart = $repo->find($id);

        /** @var Product $product */
        $product = $prodInCart->getProduct();
        $limitQuantity = $product->getQuantity();

        if($prodInCart == null) {
            return $this->redirectToRoute('notFoundProd');
        }
        else if ((!(int) $newQuantity == $newQuantity) || $newQuantity < 0) {
            return $this->redirectToRoute('invalidQuantity');
        }
        else if($newQuantity > $limitQuantity) {
            return $this->redirectToRoute('tooMuchQuantity');
        }

        $prodInCart->setUserRequiredQuantity($newQuantity);
        $em = $this->getDoctrine()->getManager();
        $em->persist($prodInCart);
        $em->flush();

        return $this->redirectToRoute('shoppingCart');
    }
}
