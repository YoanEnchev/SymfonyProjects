<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $prods = $repo->getNewestProds();

        return $this->render('default/index.html.twig', array(
            'prods' => $prods
        ));
    }
}
