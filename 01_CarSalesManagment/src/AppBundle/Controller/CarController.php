<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class CarController extends Controller
{
    /**
     * @param $make
     * @Route("/cars/{make}", name="listByMake")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByMake($make)
    {
        $repo = $this->getDoctrine()->getRepository(Car::class);
        $cars = $repo->filterByMake($make);
        return $this->render('cars/listCars.html.twig',
            ['cars' => $cars]);
    }

    /**
     * @param $id
     * @Route("/cars/{id}/parts", name="listParts")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCarParts($id)
    {
        $repo = $this->getDoctrine()->getRepository(Car::class);
        $parts = $repo->getParts($id);
        return $this->render('cars/showParts.html.twig',
            ['parts' => $parts]);
    }
}
