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

        $addRepo = $this->getDoctrine()->getRepository(CarAd::class);
        $cars = $addRepo->latestAddedAds();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $make = $data['make'];
            $model = $data['model'];
            $fuel = $data['fuel'];
            $transmission = $data['transmission'];
            $doors = $data['doors'];
            $fromYear = $data['fromYear'];
            $maxPrice = $data['maxPrice'];
            $sort = $data['sort'];
            $toYear = $data['toYear'];

            if($model == "") {
                $model = "Any";
            }
            if($fromYear == "") {
                $fromYear = "Any";
            }
            if($toYear == "") {
                $toYear = "Any";
            }
            if($maxPrice == "") {
                $maxPrice = "Any";
            }

            return $this->redirectToRoute('searchCar', array(
                'make' => $make, 'model' => $model, 'fuel' => $fuel,
                'transmission' => $transmission, 'doors' => $doors,
                'fromYear' => $fromYear, 'maxPrice' => $maxPrice,
                'sort' => $sort, 'toYear' => $toYear
            ));
        }

        return $this->render('home/index.html.twig', array(
            'form' => $form->createView(),
            'cars' => $cars
        ));
    }
}
