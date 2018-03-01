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
            $product = new Product($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);

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

        if($tablet == null) {
            return $this->redirectToRoute('notFoundProd');
        }

        $tablet = $tablet[0];

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

                return $this->redirectToRoute('viewTabletSpecifications', array('id' => $id));
            } else { //invalid grade
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('tablets/viewSpecifications.html.twig',
            array('tablet' => $tablet,
                'reviews' => $productReviews,
                'averageGrade' => $averageGrade,
                'form' => $form->createView(),
                'userReview' => $userReview));
    }

    /**
     * @Route("/tablets/newToOld", name="listAllTabletsNewToOld")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewNewToOld()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tablet::class);
        $tablets = $repo->getAllTabletsNewToOld();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablets/oldToNew", name="listAllTabletsOldToNew")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOldToNew()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tablet::class);
        $tablets = $repo->getAllTabletsOldToNew();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablets/highToLow", name="listAllTabletsHighToLow")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewHighToLow()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tablet::class);
        $tablets = $repo->getAllTabletsHighToLow();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablets/lowToHigh", name="listAllTabletsLowToHigh")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLowToHigh()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tablet::class);
        $tablets = $repo->getAllTabletsLowToHigh();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("/tablets/discount", name="listTabletsDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOnlyDiscount()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Tablet::class);
        $tablets = $repo->getOnlyDiscount();

        return $this->render('tablets/listTablets.html.twig',
            array('tablets' => $tablets));
    }

    /**
     * @Route("edit/tablet/{id}", name="editTablet")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTablet($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Tablet::class);
        $tabletBefore = $repo->specificationsForOne($id)[0];

        $form = $this->createForm(TabletProduct::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $productId = $tabletBefore['product_id'];
            $tabletId = $tabletBefore['id'];

            $productRepo = $this->getDoctrine()->getRepository(Product::class);

            $tablet = $repo->find($tabletId);
            $product = $productRepo->find($productId);

            $tablet->editData($data['ram'], $data['capacity'], $data['displayDiagonal'], $data['processorFrequency'],
                $data['processorCores'], $data['operationSystem']);
            $product->editData($data['make'], $data['model'], $data['originalPrice'], $data['imageAddress'], $data['discount'], $data['quantity']);

            $product->setType('tablet');
            $tablet->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tablet);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('viewTabletSpecifications', array('id' => $id));
        }

        return $this->render('tablets/editTablet.html.twig',
            array('tablet' => $tabletBefore,
                'form' => $form->createView()));
    }
}
