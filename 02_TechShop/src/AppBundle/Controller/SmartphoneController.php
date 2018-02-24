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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllSmartphones()
    {
        $repo = $this->getDoctrine()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphones();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
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
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('smartphone');
            $smartphone->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($smartphone);
            $em->flush();

            return $this->redirectToRoute('homepage');
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesNewToOld();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
    }

    /**
     * @Route("/smartphones/oldToNew", name="listAllSmartphonesOldToNew")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesOldToNew();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
    }

    /**
     * @Route("/smartphones/highToLow", name="listAllSmartphonesHighToLow")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesHighToLow();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
    }

    /**
     * @Route("/smartphones/lowToHigh", name="listAllSmartphonesLowToHigh")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getAllSmartphonesLowToHigh();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
    }

    /**
     * @Route("/smartphones/discount", name="listSmartphonesDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Smartphone::class);
        $smartphones = $repo->getOnlyDiscount();

        return $this->render('smartphones/listSmartphones.html.twig',
            array('smartphones' => $smartphones));
    }
}
