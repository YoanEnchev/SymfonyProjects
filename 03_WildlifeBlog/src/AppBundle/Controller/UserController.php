<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use AppBundle\Form\UsernameFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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
        if($form->isSubmitted() && $form->isValid()) {
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

        if($user == null) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('users/viewProfile.html.twig', array(
            'user' => $user,
            'userComments' => $result
        ));
    }

    /**
     * @Route("/readLaterList", name="readLaterList")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showReadLaterList()
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $readLaterList = $user->getReadLaterList();

        return $this->render('users/readLaterList.html.twig',array(
            'readLaterList' => $readLaterList));
    }

    /**
     * @Route("/addToReadLater/{articleId}", name="addToReadLater")
     * @param $articleId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addToReadLaterList($articleId)
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);

        $article = $articleRepo->find($articleId);
        /** @var User $user */
        $user = $this->getUser();

        if($user == null) {
            return $this->redirectToRoute('homepage');
        }

        $user->addToReadLaterList($article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('readLaterList');
    }
}
