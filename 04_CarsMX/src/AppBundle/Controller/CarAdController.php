<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CarAd;
use AppBundle\Form\CarAdCreate;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CarAdController extends Controller
{
    /**
     * @Route("/addCarAd", name="addCarAd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $user = $this->getUser();

        if($user == null) {
            return $this->redirectToRoute('homepage');
        }

        $carAd = new CarAd();
        $form = $this->createForm(CarAdCreate::class, $carAd);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $carAd->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($carAd);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('addCar/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
