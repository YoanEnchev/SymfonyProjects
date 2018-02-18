<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Tablet;
use AppBundle\Form\TabletProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TabletController extends Controller
{
    /**
     * @Route("/tablet", name="listAllTablets")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllTablets()
    {
        $repo = $this->getDoctrine()->getRepository(Tablet::class);
        $tablets = $repo->getAllTablets();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablet/create", name="createTablet")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTablet(Request $request)
    {
        $form = $this->createForm(TabletProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tablet = new Tablet($data['ram'], $data['capacity'], $data['displayDiagonal'], $data['processorFrequency'],
                $data['processorCores'], $data['operationSystem']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('tablet');
            $tablet->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($tablet);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('tablets/addTablet.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/tablet/{id}", name="viewTabletSpecifications")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id)
    {
        $repo = $this->getDoctrine()->getRepository(Tablet::class);
        $tablet = $repo->specificationsForOne($id);

        return $this->render('tablets/viewSpecifications.html.twig',
            array('tablet' => $tablet));
    }
}
