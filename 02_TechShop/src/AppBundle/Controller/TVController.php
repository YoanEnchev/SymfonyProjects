<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Review;
use AppBundle\Entity\TV;
use AppBundle\Form\ReviewType;
use AppBundle\Form\TVProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TVController extends Controller
{
    /**
     * @Route("/tvs", name="listAllTVs")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllTVs()
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $tvs = $repo->getAllTVs();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tv/create", name="createTV")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTV(Request $request)
    {
        $form = $this->createForm(TVProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tv = new TV($data['screenDiagonalSize'], $data['isSmart'], $data['hasUSBPort'], $data['resolution'],
                $data['powerConsummation'], $data['weight'], $data['color']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('tv');
            $tv->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($tv);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('tvs/addTV.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/tv/{id}", name="viewTVSpecifications")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $tv = $repo->specificationsForOne($id);

        $repo_product = $this->getDoctrine()->getRepository(Product::class);
        $product = $repo_product->find($id);
        $review = new Review();
        $productReviews = $product->getReviews();
        $averageGrade = number_format((float)$product->averageGrade(), 2, '.', '');

        $currentUser = $this->getUser();
        $userReview = null;

        if($currentUser) {
            $userReview = $product->getUserReview($currentUser);
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade = $review->getGrade();
            if ($grade <= 5 && $grade >= 1 && (int)$grade == $grade) { // valid grade
                $review->setGradeWords();
                $review->setUser($currentUser);
                $review->setProduct($product);

                $em = $this->getDoctrine()->getManager();
                $em->persist($review);
                $em->flush();

                return $this->redirectToRoute('viewTVSpecifications', array('id' => $id));
            } else { //invalid grade
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('tvs/viewSpecifications.html.twig',
            array('tv' => $tv,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView(),
                'userReview' => $userReview));
    }

    /**
     * @Route("/tvs/newToOld", name="listAllTVsNewToOld")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTvsNewToOld();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tvs/oldToNew", name="listAllTVsOldToNew")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsOldToNew();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tvs/highToLow", name="listAllTVsHighToLow")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsHighToLow();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tvs/lowToHigh", name="listAllTVsLowToHigh")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsLowToHigh();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }

    /**
     * @Route("/tvs/discount", name="listTVDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getOnlyDiscount();

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $tvs));
    }
}
