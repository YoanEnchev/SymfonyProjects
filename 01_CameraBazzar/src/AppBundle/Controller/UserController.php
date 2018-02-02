<?php

namespace AppBundle\Controller;

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
        return $this->render('user/profile.html.twig',
            array('user' => $user));
    }
}
