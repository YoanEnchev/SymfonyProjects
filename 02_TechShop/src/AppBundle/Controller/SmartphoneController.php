<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Review;
use AppBundle\Entity\Smartphone;
use AppBundle\Form\ReviewType;
use AppBundle\Form\SmartphoneProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SmartphoneController extends Controller
{
    /**
     * @Route("/smartphones", name="listAllSmartphones")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllSmartphones(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphones();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("/smartphone/create", name="createSmartphone")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addSmartphone(Request $request)
    {
        $form = $this->createForm(SmartphoneProduct::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $smartphone = new Smartphone($data['ram'], $data['resolution'], $data['frontCameraResolution'], $data['backCameraResolution'],
                $data['screenDiagonalSize'], $data['memory'], $data['processorFrequency'], $data['color']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);

            $product->setType('smartphone');
            $smartphone->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($smartphone);
            $em->flush();

            return $this->redirectToRoute('listAllSmartphones');
        }

        return $this->render('smartphones/addSmartphone.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/smartphone/{id}", name="viewSmartphoneSpecifications")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Smartphone::class);
        $smartphone = $repo->specificationsForOne($id);

        if($smartphone == null) {
            return $this->redirectToRoute('notFoundProd');
        }

        $smartphone = $smartphone[0];

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

                return $this->redirectToRoute('viewSmartphoneSpecifications', array('id' => $id));

            } else { //invalid grade
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('smartphones/viewSpecifications.html.twig',
            array('smartphone' => $smartphone,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView(),
                'userReview' => $userReview));
    }

    /**
     * @Route("/smartphones/newToOld", name="listAllSmartphonesNewToOld")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesNewToOld();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("/smartphones/oldToNew", name="listAllSmartphonesOldToNew")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesOldToNew();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("/smartphones/highToLow", name="listAllSmartphonesHighToLow")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesHighToLow();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("/smartphones/lowToHigh", name="listAllSmartphonesLowToHigh")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesLowToHigh();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("/smartphones/discount", name="listSmartphonesDiscount")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getOnlyDiscount();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $smartphones,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $result));
    }

    /**
     * @Route("edit/smartphone/{id}", name="editSmartphone")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editSmartphone($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Smartphone::class);
        $smartphoneBefore = $repo->specificationsForOne($id)[0];

        $form = $this->createForm(SmartphoneProduct::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productId = $smartphoneBefore['product_id'];
            $smartphoneId = $smartphoneBefore['id'];

            $productRepo = $this->getDoctrine()->getRepository(Product::class);

            $smartphone = $repo->find($smartphoneId);
            $product = $productRepo->find($productId);

            $smartphone->editData($data['ram'], $data['resolution'], $data['frontCameraResolution'], $data['backCameraResolution'],
                $data['screenDiagonalSize'], $data['memory'], $data['processorFrequency'], $data['color']);
            $product->editData($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);
            $product->setType('smartphone');
            $smartphone->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($smartphone);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('viewSmartphoneSpecifications', array('id' => $id));
        }

        return $this->render('smartphones/editSmartphone.html.twig',
            array('smartphone' => $smartphoneBefore,
                'form' => $form->createView()));
    }

    /**
     * @Route("delete/smartphone/{id}", name="deleteSmartphone")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteSmartphone($id)
    {
        $repo = $this->getDoctrine()->getRepository(Smartphone::class);
        $productRepo = $this->getDoctrine()->getRepository(Product::class);

        $smartphone = $repo->specificationsForOne($id)[0];
        $productId = $smartphone['product_id'];
        $tvId = $smartphone['id'];

        $smartphoneDelete = $repo->find($tvId);
        $productDelete = $productRepo->find($productId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($smartphoneDelete);
        $em->remove($productDelete);
        $em->flush();

        return $this->redirectToRoute('listAllSmartphones');
    }
}
