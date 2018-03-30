<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CarAd;
use AppBundle\Form\SearchCar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SearchCar::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $addRepo = $this->getDoctrine()->getRepository(CarAd::class);

            $make = $data['make'];
            $model = $data['model'];
            $fuel = $data['fuel'];
            $transmission = $data['transmission'];
            $doors = $data['doors'];
            $fromYear = $data['fromYear'];
            $maxPrice = $data['maxPrice'];
            $sort = $data['sort'];
            $toYear = $data['toYear'];

            //data validation:
            if ($make != "Alfa Romeo" && $make != "Audi" && $make != "BMW" && $make != "Chevrolet" && $make != "Citroen"
                && $make != "Dacia" && $make != "Fiat" && $make != "Ford" && $make != "Great wall" && $make != "Honda"
                && $make != "Hyundai" && $make != "Infiniti" && $make != "Isuzu" && $make != "Jaguar" && $make != "Jeep"
                && $make != "Jeep" && $make != "Kia" && $make != "Lada" && $make != "Land Rover" && $make != "Lexus"
                && $make != "Mazda" && $make != "Mercedes-Benz" && $make != "Mini" && $make != "Mitsubishi" && $make != "Nissan"
                && $make != "Opel" && $make != "Peugeot" && $make != "Porsche" && $make != "Renault" && $make != "Seat"
                && $make != "Skoda" && $make != "Ssangyong" && $make != "Subaru" && $make != "Suzuki" && $make != "Toyota"
                && $make != "Volvo" && $make != "VW" && $make != "Any") {
                return $this->redirectToRoute('homepage');
            }
            if ($transmission != "Manual" && $transmission != "Semiautomatic" && $transmission != "Automatic" && $transmission != "Any") {
                return $this->redirectToRoute('homepage');
            }
            if ($fuel != "Gasoline" && $fuel != "Diesel" && $fuel != "Gas" && $fuel != "Electricity" && $fuel != "Any") {
                return $this->redirectToRoute('homepage');
            }
            if ($doors != "2/3" && $doors != "4/5" && $doors != "Any") {
                return $this->redirectToRoute('homepage');
            }

            if ($fromYear != "" && ($fromYear <= 1900 || $fromYear > date("Y"))) {
                return $this->redirectToRoute('homepage');
            }
            if ($toYear != "" && ($toYear <= 1900 || $toYear > date("Y"))) {
                return $this->redirectToRoute('homepage');
            }
            if ($maxPrice != "" && ($maxPrice <= 0 || $maxPrice > 100000000)) {
                return $this->redirectToRoute('homepage');
            }
            if ($sort != "expensiveCheap" && $sort != "cheapExpensive" && $sort != "Year" && $sort != "Power") {
                return $this->redirectToRoute('homepage');
            }

            $repo = $this->getDoctrine()->getRepository(CarAd::class);
            $cars = $repo->searchForCar($make, $model, $fuel, $transmission, $doors, $fromYear, $maxPrice, $sort, $toYear);

            return $this->redirectToRoute('listAllAds');
        }

        return $this->render('home/index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
