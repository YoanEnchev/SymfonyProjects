<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;

use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReadLaterListController extends Controller
{
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

        if ($user == null || $article == null) {
            return $this->redirectToRoute('homepage');
        }

        $alreadyAdded = $user->checkIfArticleAddedInLaterList($article);

        if(!$alreadyAdded) {
            $user->addToReadLaterList($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('viewReadLaterList');
    }

    /**
     * @Route("/readLaterList/", name="viewReadLaterList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readLaterList(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $readLaterList = $user->getReadLaterList();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $readLaterList,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return $this->render('users/readLaterList.html.twig', array(
            'readLaterList' => $result
        ));
    }

    /**
     * @Route("/removeFromReadLaterList/{articleId}", name="removeFromReadLaterList")
     * @param $articleId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromReadLaterList($articleId)
    {
        /** @var User $user */
        $user = $this->getUser();

        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepo->find($articleId);

        if ($user === null || !$user->checkIfArticleAddedInLaterList($article)) {
            return $this->redirectToRoute('homepage');
        }

        $user->removeFromReadLaterList($article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('viewReadLaterList');
    }
}
