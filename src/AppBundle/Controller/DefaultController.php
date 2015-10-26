<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository("AppBundle:Ad")->getAdsForBlog();
        $images = $em->getRepository("AppBundle:Image")->findAll();
        return $this->render('default/index.html.twig', array("ads" => $ads, "images" => $images));
    }

    public function sidebarAction() {
        $em = $this->getDoctrine()
                ->getEntityManager();

        $categories = $em->getRepository('AppBundle:Category')
                ->findAll();


        return $this->render('Page\sidebar.html.twig', array(
                    'categories' => $categories
        ));
    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function categoryAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository("AppBundle:Ad")->getCategoryAd($id);
        $images = $em->getRepository("AppBundle:Image")->findAll();
        return $this->render('default/index.html.twig', array("ads" => $ads, "images" => $images));
    }

    /**
     * @Route("/test", name="test")
     */
    public function createVehicleAction() {
        $formData = new \AppBundle\Entity\Ad(); // Your form data class. Has to be an object, won't work properly with an array.

        $flow = $this->get('myCompany.form.flow.createVehicle'); // must match the flow's service id
        $flow->bind($formData);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // flow finished
                $em = $this->getDoctrine()->getManager();
                $em->persist($formData);
                $em->flush();

                $flow->reset(); // remove step data from the session

                return $this->redirect($this->generateUrl('test')); // redirect when done
            }
        }

        return $this->render('Vehicle\createVehicle.html.twig', array(
                    'form' => $form->createView(),
                    'flow' => $flow,
        ));
    }

}
