<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Form\AdFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdController extends Controller {

    /**
     * @Route("/new", name="new-ad")
     */
    public function newAction(Request $request) {
        $ad = new Ad();

        $form = $this->createForm(new AdFormType(), $ad);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $ad->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute("new-ad");
        }
        return $this->render('ad/new_ad.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function showDistrictAction($id, $format, Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $district = $em->getRepository("AppBundle:District")->queryOwnedBy($id);
        $serializer = $this->container->get('serializer');
        if ($format == 'json') {
            $response = new Response($serializer->serialize($district, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }



        return $this->render('Ad/ajaxload.html.twig', array(
                    'dis' => $district
        ));
    }

    /**
     * @Route("/upload", name="upload")
     *
     */
    public function uploadAction(Request $request) {

        $document = new \AppBundle\Entity\Image();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new \AppBundle\Form\UploadFormType(), $document);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($document);
            $em->flush();


            return $this->redirectToRoute('upload');
        }


        return $this->render('ad/upload.html.twig', array('form' => $form->createView()));
    }

}
