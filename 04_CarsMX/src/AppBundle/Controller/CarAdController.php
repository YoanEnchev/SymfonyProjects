<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AdditionalImage;
use AppBundle\Entity\CarAd;
use AppBundle\Form\CarAdCreate;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

header("Access-Control-Allow-Origin: *");

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
            $year = $carAd->getManufactureYear();
            $price = $carAd->getPrice();


            //data validation
            if ($make != "Alfa Romeo" && $make != "Audi" && $make != "BMW" && $make != "Chevrolet" && $make != "Citroen"
                && $make != "Dacia" && $make != "Fiat" && $make != "Ford" && $make != "Great wall" && $make != "Honda"
                && $make != "Hyundai" && $make != "Infiniti" && $make != "Isuzu" && $make != "Jaguar" && $make != "Jeep"
                && $make != "Jeep" && $make != "Kia" && $make != "Lada" && $make != "Land Rover" && $make != "Lexus"
                && $make != "Mazda" && $make != "Mercedes-Benz" && $make != "Mini" && $make != "Mitsubishi" && $make != "Nissan"
                && $make != "Opel" && $make != "Peugeot" && $make != "Porsche" && $make != "Renault" && $make != "Seat"
                && $make != "Skoda" && $make != "Ssangyong" && $make != "Subaru" && $make != "Suzuki" && $make != "Toyota"
                && $make != "Volvo" && $make != "VW") {
                return $this->redirectToRoute('homepage');
            }
            if($transmission != "Manual" && $transmission != "Semiautomatic" && $transmission != "Automatic") {
                return $this->redirectToRoute('homepage');
            }
            if($fuel != "Gasoline" && $fuel != "Diesel" && $fuel != "Gas" && $fuel != "Electricity") {
                return $this->redirectToRoute('homepage');
            }
            if($doors != "2/3" && $doors != "4/5") {
                return $this->redirectToRoute('homepage');
            }
            if($color != "Black" && $color != "Blue" && $color != "Brown" && $color != "Cyan" && $color != "Green"
            && $color != "Magenta" && $color != "Orange" && $color != "Red" && $color != "Silver" && $color != "White"
            && $color != "Yellow") {
                return $this->redirectToRoute('homepage');
            }
            if($year <= 1900 || $year > date("Y") || $price <= 0 && $price > 100000000) {
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

            return $this->redirectToRoute('viewDetails', array('id' => $carAd->getId()));
        }

        return $this->render('addCar/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/listAllAds", name="listAllAds")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAll()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCars();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/listAllAds/priceDESC", name="priceDESC")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceDesc()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsPriceDesc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/listAllAds/priceASC", name="priceASC")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceAsc()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsPriceAsc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/listAllAds/yearDESC", name="yearDESC")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function yearDesc()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsYearDesc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/listAllAds/yearASC", name="yearASC")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function yearAsc()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsYearAsc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
 * @Route("/listAllAds/powerDESC", name="powerDESC")
 * @return \Symfony\Component\HttpFoundation\Response
 */
    public function powerDesc()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsPowerDesc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/listAllAds/powerASC", name="powerASC")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function powerASC()
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        $carAds = $repo->allCarsPowerAsc();

        return $this->render('addCar/listAll.html.twig',array(
            'carAds' => $carAds
        ));
    }

    /**
     * @Route("/deleteAd/{id}", name="deleteAd")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAd($id)
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        /** @var User $user */
        $user = $this->getUser();
        $ad = $repo->find($id);

        if($ad === null || $user === null) {
            return $this->redirectToRoute('homepage');
        }

        if($user === $ad->getUser() || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $em = $this->getDoctrine()->getManager();

            /** @var AdditionalImage $additionalImage */
            foreach ($ad->getAdditionalImages() as $additionalImage) {
                $em->persist($additionalImage);
                $em->remove($additionalImage);
                $em->flush();
            }

            $repo->removeFromCheckLaterLists($id);

            $em->persist($ad);
            $em->remove($ad);
            $em->flush();
        }
        else {
            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('currentUserAds');
    }

    /**
     * @Route("/editAd/{id}", name="editAd")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAd($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(CarAd::class);
        /** @var User $user */
        $user = $this->getUser();

        $ad = $repo->find($id);
        $form = $this->createForm(CarAdCreate::class, $ad);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if($ad === null || $user === null) {
            return $this->redirectToRoute('homepage');
        }

        /** @var ArrayCollection $additionalImages */
        $additionalImages = $ad->getAdditionalImages();
        $additionalImageCount = $additionalImages->count();

        if($form->isSubmitted() && $form->isValid()) {
            if ($user === $ad->getUser() || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

                /** @var AdditionalImage $additionalImage */
                foreach ($additionalImages as $additionalImage) {
                    $additionalImage->setCarAd($ad);
                    $em->persist($additionalImage);
                }

                $em->flush();
                $em->persist($ad);
                $em->flush();

                $additionalImageRepo = $this->getDoctrine()->getRepository(AdditionalImage::class);
                $additionalImageRepo->removeEmptyImages();

                return $this->redirectToRoute('currentUserAds');
            }
            else {
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('addCar/edit.html.twig',array(
            'form' => $form->createView(),
            'ad' => $ad,
            'additionalImageCount' => $additionalImageCount
        ));
    }

    /**
     * @Route("/details/{id}", name="viewDetails")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewDetails($id)
    {
        $addRepo = $this->getDoctrine()->getRepository(CarAd::class);
        $add = $addRepo->find($id);

        if($add === null) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('addCar/details.html.twig', array(
            'add' => $add
        ));
    }
}
