<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
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
            $article->setSlug('???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185 ???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185 ???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???? +359 88 69 66 185???');

            $em = $this->getDoctrine()->getManager();

            /** @var Tag $tag */
            foreach($article->getTags() as $tag) {
                $tag->setArticle($article);
                $em->persist($tag);
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
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

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $grade = $comment->getGradeNumber();
        $content = $comment->getContent();

        if($form->isSubmitted() && $form->isValid()) {
            if ($grade <= 5 && $grade >= 1 && (int)$grade == $grade && strlen($content) > 0 && strlen($content) <= 1000) { // valid grade and content

            } else {
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('article/articleDetails.html.twig',array(
            'article' => $article,
            'form' => $form->createView()));
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
}
