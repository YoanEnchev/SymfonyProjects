<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Camera;
use AppBundle\Form\CameraType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CameraController extends Controller
{
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
     * @param int $id
     * @Route("/cameras/viewDetails/{id}", name="viewDetails")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewDetails(int $id)
    {
        $repo = $this->getDoctrine()->getRepository(Camera::class);
        $camera = $repo->find($id);

        return $this->render('camera/details.html.twig',
            array('camera' => $camera));
    }
}
