<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CarAd;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/myAds", name="currentUserAds")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loadCurrentUserAds()
    {
        /** @var User $currentUser */
       $currentUser = $this->getUser();
       if($currentUser === null) {
           return $this->redirectToRoute('homepage');
       }

       $ads = $currentUser->getCarAds();

       return $this->render('user/userAds.html.twig',array(
           'user' => $currentUser,
           'carAds' => $ads));
    }

    /**
     * @Route("/checkLaterList", name="checkLaterList")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showCheckLaterList()
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $ads = $user->getCheckLaterAds();

        return $this->render('user/checkLaterList.html.twig', array(
            'ads' => $ads
        ));
    }


    /**
     * @Route("/addToCheckLaterList/{id}", name="addToCheckLaterList")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCheckLaterList($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $addRepo = $this->getDoctrine()->getRepository(CarAd::class);
        $add = $addRepo->find($id);

        if($user === null || $add === null) {
            return $this->redirectToRoute('homepage');
        }

        $user->addToCheckLaterList($add);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('checkLaterList');
    }


    /**
     * @Route("/removeFromCheckLaterList/{id}", name="removeFromCheckLaterList")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromCheckLaterList($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $addRepo = $this->getDoctrine()->getRepository(CarAd::class);
        $add = $addRepo->find($id);

        if($user === null || $add === null) {
            return $this->redirectToRoute('homepage');
        }

        $user->removeFromCheckLaterList($add);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('checkLaterList');
    }
}
