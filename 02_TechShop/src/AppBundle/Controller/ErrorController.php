<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ErrorController extends Controller
{
    /**
     * @Route("error/invalidQuantity", name="invalidQuantity")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidQuantity()
    {
        return $this->render('error/invalidQuantity.html.twig');
    }

    /**
     * @Route("error/outOfStock", name="outOfStock")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function outOfStock()
    {
        return $this->render('error/outOfStock.html.twig');
    }

    /**
     * @Route("error/tooMuchQuantity", name="tooMuchQuantity")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tooMuchQuantity()
    {
        return $this->render('error/tooMuchQuantity.html.twig');
    }

    /**
     * @Route("error/invalidCardNumberFormat", name="invalidCardNumberFormat")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidCardNumberFormat()
    {
        return $this->render('error/invalidCardNumberFormat.html.twig');
    }

    /**
     * @Route("error/invalidCardDateFormat", name="invalidCardDateFormat")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidCardDateFormat()
    {
        return $this->render('error/invalidCardDateFormat.html.twig');
    }

    /**
     * @Route("error/expiredCard", name="expiredCard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expiredCard()
    {
        return $this->render('error/expiredCard.html.twig');
    }

    /**
     * @Route("error/invalidMonth", name="invalidMonth")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidMonth()
    {
        return $this->render('error/invalidMonth.html.twig');
    }

    /**
     * @Route("error/invalidUsername", name="invalidUsername")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidUsername()
    {
        return $this->render('error/invalidUsername.html.twig');
    }

    /**
     * @Route("error/invalidPassword", name="invalidPassword")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function invalidPassword()
    {
        return $this->render('error/invalidPassword.html.twig');
    }


    /**
     * @Route("error/notFoundProd", name="notFoundProd")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notFoundProduct()
    {
        return $this->render('error/notFoundProd.html.twig');
    }

    /**
     * @Route("error/tooMuchQtyInCartForProd", name="tooMuchQtyInCartForProd")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tooMuchQtyInCartForProd()
    {
        return $this->render('error/tooMuchQtyInCartForProd.html.twig');
    }
}