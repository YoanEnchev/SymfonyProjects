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

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade = $review->getGrade();
            if ($grade <= 5 && $grade >= 1 && (int)$grade == $grade) { // valid grade
                $review->setGradeWords();
                $review->setUser($this->getUser());
                $review->setProduct($product);

                $em = $this->getDoctrine()->getManager();
                $em->persist($review);
                $em->flush();
            } else { //invalid grade
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('smartphones/viewSpecifications.html.twig',
            array('smartphone' => $smartphone,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView()));
    }
}
