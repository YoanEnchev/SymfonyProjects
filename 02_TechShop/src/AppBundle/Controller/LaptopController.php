<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Laptop;
use AppBundle\Entity\Product;
use AppBundle\Entity\Review;
use AppBundle\Form\LaptopProduct;
use AppBundle\Form\ReviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LaptopController extends Controller
{
    /**
     * @Route("/laptop", name="listAllLaptops")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllSmartphones()
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptops();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("/laptop/create", name="createLaptop")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTablet(Request $request)
    {
        $form = $this->createForm(LaptopProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $laptop = new Laptop($data['ram'], $data['processorFrequency'], $data['processorMake'], $data['processorModel'],
                $data['videoCardMake'], $data['capacity'], $data['processorCores'], $data['operationSystem'], $data['weight']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount']);

            $product->setType('laptop');
            $laptop->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($laptop);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('laptops/addLaptop.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/laptop/{id}", name="viewLaptopSpecifications")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $laptop = $repo->specificationsForOne($id);

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

        return $this->render('laptops/viewSpecifications.html.twig',
            array('laptop' => $laptop,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView()));
    }
}
