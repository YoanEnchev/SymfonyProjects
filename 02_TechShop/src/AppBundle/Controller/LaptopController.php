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
    public function addLaptop(Request $request)
    {
        $form = $this->createForm(LaptopProduct::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $laptop = new Laptop($data['ram'], $data['processorFrequency'], $data['processorMake'], $data['processorModel'],
                $data['videoCardMake'], $data['capacity'], $data['processorCores'], $data['operationSystem'], $data['weight']);
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);

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

        if($laptop == null) {
            return $this->redirectToRoute('notFoundProd');
        }

        $laptop = $laptop[0];

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

                return $this->redirectToRoute('viewLaptopSpecifications', array('id' => $id));
            } else { //invalid grade
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('laptops/viewSpecifications.html.twig',
            array('laptop' => $laptop,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView(),
                'userReview' => $userReview));
    }

    /**
     * @Route("/laptops/newToOld", name="listAllLaptopsNewToOld")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsNewToOld();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("/laptops/oldToNew", name="listAllLaptopsOldToNew")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsOldToNew();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("/laptops/highToLow", name="listAllLaptopsHighToLow")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsHighToLow();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("/laptops/lowToHigh", name="listAllLaptopsLowToHigh")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsLowToHigh();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("/laptops/discount", name="listLaptopsDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getOnlyDiscount();

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $laptops));
    }

    /**
     * @Route("edit/laptop/{id}", name="editLaptop")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editLaptop($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $laptopBefore = $repo->specificationsForOne($id)[0];

        $form = $this->createForm(LaptopProduct::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productId = $laptopBefore['product_id'];
            $laptopId = $laptopBefore['id'];

            $productRepo = $this->getDoctrine()->getRepository(Product::class);

            $laptop = $repo->find($laptopId);
            $product = $productRepo->find($productId);

            $laptop->editData($data['ram'], $data['processorFrequency'], $data['processorMake'], $data['processorModel'],
                $data['videoCardMake'], $data['capacity'], $data['processorCores'], $data['operationSystem'], $data['weight']);
            $product->editData($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'],
                $data['quantity']);
            $product->setType('laptop');
            $laptop->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($laptop);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('viewLaptopSpecifications', array('id' => $id));
        }

        return $this->render('laptops/editLaptop.html.twig',
            array('laptop' => $laptopBefore,
                'form' => $form->createView()));
    }

    /**
     * @Route("delete/laptop/{id}", name="deleteLaptop")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteLaptop($id)
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $productRepo = $this->getDoctrine()->getRepository(Product::class);

        $laptop = $repo->specificationsForOne($id)[0];
        $productId = $laptop['product_id'];
        $laptopId = $laptop['id'];

        $laptopDelete = $repo->find($laptopId);
        $productDelete = $productRepo->find($productId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($laptopDelete);
        $em->remove($productDelete);
        $em->flush();

        return $this->redirectToRoute('listAllLaptops');
    }
}
