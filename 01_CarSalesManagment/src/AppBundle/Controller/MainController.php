<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('index/index.html.twig');
    }

    /**
     *
     * @Route("/search", name="search")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function searchPage()
    {
        return $this->render('search/searchByMakeAndDiscount.html.twig');
    }
}
