<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends Controller
{
    /**
     * @param $order
     * @Route("/customers/all/{order}", name="listCustomersOrdered")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCustomers($order)
    {
        if($order == "ASC" || $order == "DESC") {
            $repo = $this->getDoctrine()->getRepository(Customer::class);
            $customers = $repo->getCustomersByGivenOrder($order);
            return $this->render('customers/listCustomers.html.twig',
                ['customers' => $customers]);
        }
    }
}
