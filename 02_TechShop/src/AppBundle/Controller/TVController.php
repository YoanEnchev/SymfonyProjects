<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\TV;
use AppBundle\Form\TVProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TVController extends Controller
{
    /**
     * @Route("/tvs", name="listAllTVs")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllTVs()
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $tvs = $repo->getAllTVs();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tv/create", name="createTV")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTV(Request $request)
    {
        $form = $this->createForm(TVProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tv = new TV($data['screenDiagonalSize'], $data['isSmart'], $data['hasUSBPort'], $data['resolution'],
                $data['powerConsummation'], $data['weight'], $data['color']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('tv');
            $tv->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($tv);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('tvs/addTV.html.twig',
            array('form' => $form->createView()));
    }
}
