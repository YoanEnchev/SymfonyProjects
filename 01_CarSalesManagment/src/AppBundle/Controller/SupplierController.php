<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Supplier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SupplierController extends Controller
{
    /**
     * @param $origin
     * @Route("/suppliers/listByOrigin/{origin}", name="listSuppliers")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByOrigin($origin)
    {
        switch ($origin)
        {
            case 'local':
                $origin = 0;
                break;
            case 'foreign':
                $origin = 1;
                break;
        }

        if($origin === 0 || $origin === 1) {
            $repo = $this->getDoctrine()->getRepository(Supplier::class);
            $suppliers = $repo->getSuppliersByOrigin($origin);
            return $this->render('suppliers/listSuppliers.html.twig',
                ['suppliers' => $suppliers]);
        }
    }
}
