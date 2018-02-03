<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/users/profile", name="viewProfile")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProfile()
    {
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(User::class);
        $userSells = $repo->getUserSells($user->getUsername());

        return $this->render('user/profile.html.twig',
            array('user' => $user,
                'userSells' => $userSells));
    }
}
