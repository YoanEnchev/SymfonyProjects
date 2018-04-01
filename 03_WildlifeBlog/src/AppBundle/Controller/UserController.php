<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UsernameFilter;
use AppBundle\Form\UserRegister;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="listUsers")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $form = $this->createForm(UsernameFilter::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form["username"]->getData();
            $user = $repo->findBy(array('username' => $username))[0];
            return $this->render('admin/users/showUser.html.twig',
                array('user' => $user,
                    'form' => $form->createView()));
        }
        return $this->render('admin/users/listUsers.html.twig',
            array('users' => $result,
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
        $em = $this->getDoctrine()->getManager();

        $user = $userRepo->find($id);
        $em->persist($user);
        $userRepo->removeUserComments($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('listUsers');
    }

    /**
     * @Route("/users/{id}", name="viewProfile")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProfile($id, Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);
        $userComments = $user->getComments();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $userComments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        if ($user == null) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('users/viewProfile.html.twig', array(
            'user' => $user,
            'userComments' => $result
        ));
    }

    /**
     * @Route("user/editUserInfo", name="editUserInfo")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProfileInfo(Request $request, UserPasswordEncoderInterface $encoder)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser == null) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserRegister::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usernameRegex = "/^\w{3,30}$/";
            $emailRegex = "/^(\w+)@(\w+)\.(\w+)$/";
            $passwordRegex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/";

            if (!preg_match($usernameRegex, $currentUser->getUsername()) || !preg_match($emailRegex, $currentUser->getEmail()) //invalid
                ||  !preg_match($passwordRegex, $currentUser->getPassword())) {
                return $this->redirectToRoute('register');
            }
            $userRepo = $this->getDoctrine()->getRepository(User::class);
            /** @var ArrayCollection $userList */
            $userList = $userRepo->findAll();

            /** @var User $registeredUser */
            foreach ($userList as $registeredUser)
            {
                if($registeredUser->getUsername() == $currentUser->getUsername() && $registeredUser->getId() != $currentUser->getId())
                {
                    return $this->render('register/register.html.twig', array(
                        'form' => $form->createView(),
                        'usernameTaken' => true,
                        'emailTaken' => false,
                        'username' => $currentUser->getUsername(),
                        'email' => ''
                    ));
                }

                if($registeredUser->getEmail() == $currentUser->getEmail() && $registeredUser->getId() != $currentUser->getId())
                {
                    return $this->render('register/register.html.twig', array(
                        'form' => $form->createView(),
                        'usernameTaken' => false,
                        'emailTaken' => true,
                        'username' => '',
                        'email' => $currentUser->getEmail()
                    ));
                }
            }


            $currentUser->setPassword($encoder->encodePassword($currentUser, $currentUser->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();

            return $this->redirectToRoute('viewProfile', array('id' => $currentUser->getId()));
        }

        return $this->render('users/editProfileData.html.twig', array(
            'user' => $currentUser,
            'form' => $form->createView(),
            'usernameTaken' => false,
            'emailTaken' => false));
    }
}
