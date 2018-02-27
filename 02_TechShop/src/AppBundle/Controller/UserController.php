<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("user/addToCart/{productId}", name="addProdToCart")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addToShoppingCart($productId)
    {
        $prodRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $product = $prodRepo->find($productId);

        if($product === null) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $user->addToShoppingCart($product);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

       return $this->showShoppingCart();
    }

    /**
     * @Route("user/removeFromCart/{productId}", name="removeFromCart")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeFromShoppingCart($productId)
    {
        $prodRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $product = $prodRepo->find($productId);

        if($product === null) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $user->removeProdFromCart($product);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->showShoppingCart();
    }

    /**
     * @Route("user/shoppingCart", name="shoppingCart")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showShoppingCart()
    {
        $user = $this->getUser();
        $user->clearShoppingCart(); //remove out of stock products

        return $this->render('user/shoppingCart.html.twig',
            array('shoppingCart' => $shoppingCart = $user->getProductsInShoppingCart()));
    }

    /**
     * @Route("user/wishlist/{productId}", name="addToWishlist")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addToWishlist($productId)
    {
        $prodRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $product = $prodRepo->find($productId);

        if($product === null) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $user->addToWishlist($product);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->showWishlist();
    }

    /**
     * @Route("user/removeFromWishlist/{productId}", name="removeFromWishlist")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeFromWishlist($productId)
    {
        $prodRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $product = $prodRepo->find($productId);

        if($product === null) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $user->removeProdFromWishlist($product);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->showWishlist();
    }

    /**
     * @Route("user/wishlist", name="wishlist")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showWishlist()
    {
        $user = $this->getUser();
        $user->clearWishlist(); //remove out of stock products

        return $this->render('user/wishlist.html.twig',
            array('wishlist' => $wishlist = $user->getProductsInWishlist()));
    }
}
