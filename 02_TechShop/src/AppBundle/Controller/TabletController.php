<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Review;
use AppBundle\Entity\Tablet;
use AppBundle\Form\ReviewType;
use AppBundle\Form\TabletProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TabletController extends Controller
{
    /**
     * @Route("/tablet", name="listAllTablets")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllTablets()
    {
        $repo = $this->getDoctrine()->getRepository(Tablet::class);
        $tablets = $repo->getAllTablets();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablet/create", name="createTablet")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTablet(Request $request)
    {
        $form = $this->createForm(TabletProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tablet = new Tablet($data['ram'], $data['capacity'], $data['displayDiagonal'], $data['processorFrequency'],
                $data['processorCores'], $data['operationSystem']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('tablet');
            $tablet->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($tablet);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('tablets/addTablet.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/tablet/{id}", name="viewTabletSpecifications")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Tablet::class);
        $tablet = $repo->specificationsForOne($id);

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

        return $this->render('tablets/viewSpecifications.html.twig',
            array('tablet' => $tablet,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView()));
    }
}
