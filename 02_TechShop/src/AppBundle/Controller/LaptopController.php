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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllLaptops(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptops();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
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

            return $this->redirectToRoute('listAllLaptops');
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
        $otherLaptops = $repo->getHighestQuantitiesProds();

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
                'userReview' => $userReview,
                'otherLaptops' => $otherLaptops));
    }

    /**
     * @Route("/laptops/newToOld", name="listAllLaptopsNewToOld")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsNewToOld();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
    }

    /**
     * @Route("/laptops/oldToNew", name="listAllLaptopsOldToNew")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsOldToNew();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
    }

    /**
     * @Route("/laptops/highToLow", name="listAllLaptopsHighToLow")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsHighToLow();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
    }

    /**
     * @Route("/laptops/lowToHigh", name="listAllLaptopsLowToHigh")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getAllLaptopsLowToHigh();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
    }

    /**
     * @Route("/laptops/discount", name="listLaptopsDiscount")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Laptop::class);
        $laptops = $repo->getOnlyDiscount();

        //pagination:
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $laptops,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('laptops/listLaptops.html.twig',
            array('laptops' => $result));
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
