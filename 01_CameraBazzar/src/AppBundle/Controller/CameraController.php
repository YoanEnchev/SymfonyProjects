<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Camera;
use AppBundle\Entity\User;
use AppBundle\Form\CameraType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CameraController extends Controller
{
    /**
     * @param int $id
     * @Route("/cameras/viewDetails/{id}", name="viewDetails")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewDetails(int $id)
    {
        $cameraRepo = $this->getDoctrine()->getRepository(Camera::class);
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $camera = $cameraRepo->find($id);
        $seller = $camera->getUser();

        $sellerCameras = $userRepo->getUserSells($seller->getUsername());

        return $this->render('camera/details.html.twig',
            array('camera' => $camera,
                'sellerCameras' => $sellerCameras));
    }

    /**
     * @Route("/camera/addCamera", name="addCamera")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addCamera(Request $request)
    {
        $camera = new Camera();
        $form = $this->createForm(CameraType::class, $camera);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $camera->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($camera);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('camera/addCamera.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/cameras/listCameras", name="listCameras")
     */
    public function listCameras()
    {
        $repo = $this->getDoctrine()->getRepository(Camera::class);
        $cameras = $repo->findAll();

        return $this->render('camera/listCameras.html.twig',
            array('cameras' => $cameras));
    }

    /**
     * @Route("/cameras/edit/{id}", name="editCamera")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCamera($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Camera::class);
        $camera = $repo->find($id);

        if($camera === null) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(CameraType::class, $camera);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($camera);
            $em->flush();

            return $this->redirectToRoute('viewProfile');
        }

        return $this->render('camera/edit.html.twig',
            array('camera' => $camera,
                'form' => $form->createView()));
    }

    /**
     * @Route("/cameras/delete/{id}", name="deleteCamera")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteCamera($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Camera::class);
        $camera = $repo->find($id);

        if($camera === null) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(CameraType::class, $camera);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($camera);
            $em->flush();

            return $this->redirectToRoute('viewProfile');
        }

        return $this->render('camera/delete.html.twig',
            array('camera' => $camera,
                'form' => $form->createView()));
    }

    /**
     * @Route("/cameras/{id}/sellerContacts", name="getSellerContacts")
     * @param $id
     */
    public function getSellerContacts($id)
    {
        $cameraRepo = $this->getDoctrine()->getRepository(Camera::class);
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $camera = $cameraRepo->find($id);

        $seller = $camera->getUser();
        $sellerCameras = $userRepo->getUserSells($seller->getUsername());

        return $this->render('camera/sellerContacts.html.twig',
           array('seller' => $seller,
               'sellerCameras' => $sellerCameras));
    }
}
