<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Form\AdFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        /**
     * @Route("/city/{id}", name="show-district")
     */
    public function showDistrictAction($id, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $district = $em->getRepository("AppBundle:District")->queryOwnedBy($id);
        
        
        return $this->render('ad/ajaxload.html.twig', array(
                    'dis' => $district
        ));
    }

}
