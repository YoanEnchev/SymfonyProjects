<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AdditionalImage;
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

        if ($user == null) {
            return $this->redirectToRoute('homepage');
        }

        $carAd = new CarAd();
        $form = $this->createForm(CarAdCreate::class, $carAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carAd->setUser($user);
            $em = $this->getDoctrine()->getManager();

            $make = $carAd->getMake();
            $transmission = $carAd->getTransmission();
            $fuel = $carAd->getFuel();
            $doors = $carAd->getDoors();
            $color = $carAd->getColor();


            if ($make != "Alfa Romeo" && $make != "Audi" && $make != "BMW" && $make != "Chevrolet" && $make != "Citroen"
                && $make != "Dacia" && $make != "Fiat" && $make != "Ford" && $make != "Great wall" && $make != "Honda"
                && $make != "Hyundai" && $make != "Infiniti" && $make != "Isuzu" && $make != "Jaguar" && $make != "Jeep"
                && $make != "Jeep" && $make != "Kia" && $make != "Lada" && $make != "Land Rover" && $make != "Lexus"
                && $make != "Mazda" && $make != "Mercedes-Benz" && $make != "Mini" && $make != "Mitsubishi" && $make != "Nissan"
                && $make != "Opel" && $make != "Peugeot" && $make != "Porsche" && $make != "Renault" && $make != "Seat"
                && $make != "Skoda" && $make != "Ssangyong" && $make != "Subaru" && $make != "Suzuki" && $make != "Toyota"
                && $make != "Volvo" && $make != "VW")
            {
                return $this->redirectToRoute('homepage');
            }
            if($transmission != "Manual" && $transmission != "Semiautomatic" && $transmission != "Automatic")
            {
                return $this->redirectToRoute('homepage');
            }
            if($fuel != "Gasoline" && $fuel != "Diesel" && $fuel != "Gas" && $fuel != "Electricity")
            {
                return $this->redirectToRoute('homepage');
            }
            if($doors != "2/3" && $doors != "4/5") {
                return $this->redirectToRoute('homepage');
            }
            if($color != "Black" && $color != "Blue" && $color != "Brown" && $color != "Cyan" && $color != "Green"
            && $color != "Magenta" && $color != "Orange" && $color != "Red" && $color != "Silver" && $color != "White"
            && $color != "Yellow")
            {
                return $this->redirectToRoute('homepage');
            }

                /** @var AdditionalImage $additionalImage */
                foreach ($carAd->getAdditionalImages() as $additionalImage) {
                    $additionalImage->setCarAd($carAd);
                    $em->persist($additionalImage);
                    $em->flush();
                }

            $em->persist($carAd);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('addCar/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
