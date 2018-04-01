<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CarAd;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

header('Access-Control-Allow-Origin: *');

class UserController extends Controller
{
    /**
     * @Route("/myAds", name="currentUserAds")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loadCurrentUserAds(Request $request)
    {
        /** @var User $currentUser */
       $currentUser = $this->getUser();
       if($currentUser === null) {
           return $this->redirectToRoute('homepage');
       }

       $ads = $currentUser->getCarAds();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $ads,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

       return $this->render('user/userAds.html.twig',array(
           'user' => $currentUser,
           'carAds' => $result));
    }

    /**
     * @Route("/checkLaterList", name="checkLaterList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showCheckLaterList(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $ads = $user->getCheckLaterAds();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $ads,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );


        return $this->render('user/checkLaterList.html.twig', array(
            'ads' => $result
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

        if(!$user->alreadyInCheckLaterList($add)) {
            $user->addToCheckLaterList($add);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

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

    /**
     * @Route("/profile/{id}", name="userProfile")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewUserProfile($id, Request $request)
    {
        /** @var User $user */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);

        if($user == null) {
            return $this->redirectToRoute('homepage');
        }

        $carAds = $user->getCarAds();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $carAds,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('user/userProfile.html.twig', array(
            'user' => $user,
            'carAds' => $result
        ));
    }
}
