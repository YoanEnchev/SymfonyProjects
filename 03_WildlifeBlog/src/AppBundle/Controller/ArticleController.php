<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Form\ArticleType;
use AppBundle\Form\CommentType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/admin/article/create", name="createArticle")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createArticle(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            $today = new \DateTime();
            $article->setDateAdded($today);
            $article->setSlug();

            $em = $this->getDoctrine()->getManager();

            /** @var Tag $tag */
            foreach($article->getTags() as $tag) {
                $tag->setArticle($article);
                $em->persist($tag);
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('readArticle', array('id' => $article->getId()));
        }

        return $this->render('article/create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/article/{id}/read", name="readArticle")
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readMore(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        $comment = new Comment();
        $comments = $article->getComments();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $grade = $comment->getGradeNumber();
        $content = $comment->getContent();
        $averageGrade = $article->calcAverageGrade();
        $tags = $article->getTags();

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $userCommented = false;

        if($currentUser != null) {
            $userCommented = $article->checkIfUserCommented($currentUser);
        }
        if($form->isSubmitted() && $form->isValid()) {
            if ($grade <= 5 && $grade >= 1 && (int)$grade == $grade && strlen($content) > 0 && strlen($content) <= 1000) { // valid grade and content
                $comment->setUser($currentUser);
                $comment->setArticle($article);
                $article->addComment($comment);

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('readArticle', array('id' => $id));
            } else {
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('article/articleDetails.html.twig',array(
            'article' => $article,
            'form' => $form->createView(),
            'userCommented' => $userCommented,
            'comments' => $result,
            'averageGrade' => $averageGrade,
            'tags' => $tags));
    }

    /**
     * @Route("/admin/article/{id}/edit", name="editArticle")
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editArticle(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        /** @var ArrayCollection $tags */
        $tags = $article->getTags();
        $numberOfTags= $tags->count();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setSlug();

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $repo->removeEmptyTags();

            return $this->redirectToRoute('readArticle', array('id' => $id));
        }

        return $this->render('article/editArticle.html.twig',array(
            'article' => $article,
            'form' => $form->createView(),
            'numberOfTags' => $numberOfTags));
    }

    /**
     * @Route("/admin/article/{id}/delete", name="deleteArticle")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticle($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        $repo->removeAllTagsAndComments($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/articles/{habitat}", name="listByHabitats")
     * @param string $habitat
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listArticlesByHabitat($habitat, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->getArticlesFromHabitat($habitat);

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return $this->render('article/listArticles.html.twig', array(
            'articles' => $result,
            'habitat' => $habitat
        ));
    }

    /**
     * @Route("/articles/tag/{tagName}", name="listByTagName")
     * @param string tagName
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listArticlesByTagName($tagName, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->getByTagName($tagName);

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return $this->render('article/listByTagName.html.twig', array(
            'articles' => $result,
            'tagName' => $tagName
        ));
    }

    /**
     * @Route("/articles", name="listAllArticles")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllArticles(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );
        return $this->render('article/listAllArticles.html.twig',
            array('articles' => $result));
    }
}
