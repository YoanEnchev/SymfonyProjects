<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductInCart;
use AppBundle\Entity\ProductPurchase;
use AppBundle\Entity\User;
use AppBundle\Form\CartPurchase;
use AppBundle\Form\UsernameFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            return $this->redirectToRoute('notFoundProd');
        }

        $em = $this->getDoctrine()->getManager();

        $prodInCart = new ProductInCart();
        $prodInCart->setProduct($product);
        $em->persist($prodInCart);
        $em->flush();

        $user = $this->getUser();
        $user->addToShoppingCart($prodInCart);
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
        $repo = $this->getDoctrine()->getManager()->getRepository(ProductInCart::class);
        $productInCart = $repo->find($productId);

        if($productInCart === null) {
            return $this->redirectToRoute('notFoundProd');
        }

        $user = $this->getUser();
        $user->removeProdFromCart($productInCart);

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
        /** @var User $user */
        $user = $this->getUser();
        $user->clearShoppingCart(); //remove out of stock products
        $totalCost = $user->totalCostOfShoppingCart();

        /** @var User $user */
        return $this->render('user/shoppingCart.html.twig',
            array('shoppingCart' => $shoppingCart = $user->getProductsInShoppingCart(),
                'totalCost' => $totalCost));
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
            return $this->redirectToRoute('notFoundProd');
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
            return $this->redirectToRoute('notFoundProd');
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

    /**
     * @Route("/admin/users", name="listUsers")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );


        $form = $this->createForm(UsernameFilter::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $username = $form["username"]->getData();
            $user = $repo->findBy(array('username' => $username));
            return $this->render('user/listUsers.html.twig',
                array('users' => $user,
                    'form' => $form->createView()));
        }

        return $this->render('user/listUsers.html.twig',
            array('users' => $result,
                'form' => $form->createView()));
    }

    /**
     * @Route("/admin/users/banUser/{id}", name="banUser")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function banUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('listUsers');
    }

    /**
     * @Route("/buyProdsFromCart", name="buyProdsFromCart")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buyAllProdsFromCart(Request $request)
    {
        $form = $this->createForm(CartPurchase::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $currentUser */
            $currentUser = $this->getUser();
            if($currentUser->allProdsFromCartAvaliable()) {

                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();

                $purchaseFormDataOnly = new ProductPurchase();
                $purchaseFormDataOnly->setUserInfo($data['phone'], $data['city'], $data['address'], $data['creditCardNumber'], $data['inputDateValidThrough']);

                //userdata validation
                if(!$purchaseFormDataOnly->cardNumberIsValid()) {
                    return $this->redirectToRoute('invalidCardNumberFormat');
                }

                if(!$purchaseFormDataOnly->validThroughFormatIsValid()) {
                    return $this->redirectToRoute('invalidCardDateFormat');
                }

                if(!$purchaseFormDataOnly->validMonth()) {
                    return $this->redirectToRoute('invalidMonth');
                }

                if($purchaseFormDataOnly->cardHasExpired()) {
                    return $this->redirectToRoute('expiredCard');
                }

                /** @var ProductInCart $prodInCart */
                foreach ($currentUser->getProductsInShoppingCart() as $prodInCart) {
                    $purchase = new ProductPurchase();
                    $purchase->setUserInfo($data['phone'], $data['city'], $data['address'], $data['creditCardNumber'], $data['inputDateValidThrough']);
                    $purchase->setProduct($prodInCart->getProduct());
                    $purchase->setQuantity($prodInCart->getUserRequiredQuantity());

                    $em->persist($purchase);
                    $em->persist($prodInCart);
                    $prodInCart->getProduct()->setQuantity($prodInCart->getProduct()->getQuantity() - $prodInCart->getUserRequiredQuantity());
                    $em->flush();
                }

                $currentUser->setProductsInShoppingCart(new ArrayCollection()); //empty shopping cart
                $em->persist($currentUser);
                $em->flush();

                return $this->redirectToRoute('afterPurchaseSuccess');
            }

            return $this->redirectToRoute('tooMuchQtyInCartForProd');
        }

        return $this->render('purchase/purchaseShoppingCart.html.twig',array(
            'form' => $form->createView()));
    }
}
