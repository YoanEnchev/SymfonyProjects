<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Laptop;
use AppBundle\Entity\Product;
use AppBundle\Form\LaptopProduct;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewSpecificationsForOne($id)
    {
        $repo = $this->getDoctrine()->getRepository(Laptop::class);
        $laptop = $repo->specificationsForOne($id);

        return $this->render('laptops/viewSpecifications.html.twig',
            array('laptop' => $laptop));
    }
}
