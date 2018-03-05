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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllTVs(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $tvs = $repo->getAllTVs();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
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
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tv = new TV($data['screenDiagonalSize'], $data['isSmart'], $data['hasUSBPort'], $data['resolution'],
                $data['powerConsummation'], $data['weight'], $data['color']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);

            $product->setType('tv');
            $tv->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($tv);
            $em->flush();

            return $this->redirectToRoute('listAllTVs');
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

        if ($tv == null) {
            return $this->redirectToRoute('notFoundProd');
        }
        $tv = $tv[0];


        $repo_product = $this->getDoctrine()->getRepository(Product::class);
        $product = $repo_product->find($id);
        $otherTvs = $repo->getHighestQuantitiesProds();

        $review = new Review();
        $productReviews = $product->getReviews();
        $averageGrade = number_format((float)$product->averageGrade(), 2, '.', '');

        $currentUser = $this->getUser();
        $userReview = null;

        if ($currentUser) {
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
                'userReview' => $userReview,
                'otherTvs' => $otherTvs));
    }

    /**
     * @Route("/tvs/newToOld", name="listAllTVsNewToOld")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTvsNewToOld();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
    }

    /**
     * @Route("/tvs/oldToNew", name="listAllTVsOldToNew")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsOldToNew();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
    }

    /**
     * @Route("/tvs/highToLow", name="listAllTVsHighToLow")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsHighToLow();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
    }

    /**
     * @Route("/tvs/lowToHigh", name="listAllTVsLowToHigh")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getAllTVsLowToHigh();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
    }

    /**
     * @Route("/tvs/discount", name="listTVDiscount")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(TV::class);
        $tvs = $repo->getOnlyDiscount();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tvs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('tvs/listTVs.twig',
            array('tvs' => $result));
    }

    /**
     * @Route("edit/tv/{id}", name="editTv")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTv($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $tvBefore = $repo->specificationsForOne($id)[0];

        $form = $this->createForm(TVProduct::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productId = $tvBefore['product_id'];
            $tvId = $tvBefore['id'];

            $productRepo = $this->getDoctrine()->getRepository(Product::class);

            $tv = $repo->find($tvId);
            $product = $productRepo->find($productId);

            $tv->editData($data['screenDiagonalSize'], $data['isSmart'], $data['hasUSBPort'], $data['resolution'],
                $data['powerConsummation'], $data['weight'], $data['color']);
            $product->editData($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);


            $product->setType('tv');
            $tv->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tv);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('viewTVSpecifications', array('id' => $id));
        }

        return $this->render('tvs/editTv.html.twig',
            array('tv' => $tvBefore,
                'form' => $form->createView()));
    }

    /**
     * @Route("delete/tv/{id}", name="deleteTv")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTv($id)
    {
        $repo = $this->getDoctrine()->getRepository(TV::class);
        $productRepo = $this->getDoctrine()->getRepository(Product::class);

        $tv = $repo->specificationsForOne($id)[0];
        $productId = $tv['product_id'];
        $tvId = $tv['id'];

        $tvDelete = $repo->find($tvId);
        $productDelete = $productRepo->find($productId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($tvDelete);
        $em->remove($productDelete);
        $em->flush();

        return $this->redirectToRoute('listAllTVs');
    }
}
