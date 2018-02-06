<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UsernameFilter;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/users/profile", name="viewProfile")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProfile()
    {
        $user = $this->getUser();

        if($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $repo = $this->getDoctrine()->getRepository(User::class);
        $userSells = $repo->getUserSells($user->getUsername());

        return $this->render('user/profile.html.twig',
            array('user' => $user,
                'userSells' => $userSells));
    }

    /**
     * @Route("/admin/users", name="listUsers")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

        $form = $this->createForm(UsernameFilter::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $username = $form["username"]->getData();
            $user = $repo->findBy(array('username' => $username));

            return $this->render('admin/users/listUsers.html.twig',
                array('users' => $user,
                    'form' => $form->createView()));
        }

        return $this->render('admin/users/listUsers.html.twig',
            array('users' => $users,
                'form' => $form->createView()));
    }

    /**
     * @Route("/admin/users/banUser/{id}", name="banUser")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function banUser($id)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $userRepo->deleteUser($id);

        return $this->redirectToRoute('listUsers');
    }
}
