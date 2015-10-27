<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Form\AdFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdController extends Controller {

    /**
     * @Route("/new", name="new-ad")
     */
    public function newAction(Request $request) {
        $ad = new Ad();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new AdFormType($this->getDoctrine()->getManager()), $ad);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $categories = $em->getRepository("AppBundle:Category")->queryGetCategory();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $ad->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $ad->setUser($user);
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute("upload", ["id" => $ad->getId()]);
        }
        return $this->render('ad/new_ad.html.twig', array(
                    'form' => $form->createView(),
                    'categories' => $categories
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
    
        public function showSubCategoryAction($id, $format, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $sub_category = $em->getRepository("AppBundle:Category")->queryOwnedBy($id);
        
        if ($format == 'json') {
            $response = new \Symfony\Component\HttpFoundation\JsonResponse($sub_category);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('Ad/ajaxload.html.twig', array(
                    'dis' => $sub_category
        ));
    }

    /**
     * @Route("/{id}/upload", name="upload")
     *
     */
    public function uploadAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $adId = $em->getRepository("AppBundle:Ad")->find($id);

        return $this->render('ad/upload.html.twig', array("gallery" => $adId));
    }

    /**
     * @Route("/{id}/details", name="details")
     *
     */
    public function detailsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository("AppBundle:Ad")->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();

        return $this->render('ad/details.html.twig', array(
                    "ad" => $ad,
                    "user" => $user));
    }

    /**
     * @Route("/{id}/edit-ad", name="edit-ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     *
     */
    public function editAction($ad, Request $request) {
//        $this->enforceOwnerSecurity($ad);  SECURITY
        $form = $this->createForm(new AdFormType($this->getDoctrine()->getManager()), $ad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute("details", ['id' => $ad->getId()]);
        }
        return $this->render('ad/edit-ad.html.twig', array(
                    "form" => $form->createView(),
                    "ad" => $ad));
    }
    
        /**
     * @Route("/delete-ad/{id}", name="delete_ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     */
    public function deleteAdAction($ad, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($ad);
        $em->flush();
        
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/{id}/edit-ad/upload", name="edit-ad-upload")
     * @ParamConverter("ad", class="AppBundle:Ad")
     *
     */
    public function editImageAction(Ad $ad, Request $request) {
//        $this->enforceOwnerSecurity($ad);     SECURITY
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository("AppBundle:Image")->find($ad);

        return $this->render('ad/upload-edit.html.twig', array(
                    "image" => $image,
                    "gallery" => $ad
        ));
    }

    private function enforceOwnerSecurity(Ad $event) {
        $user = $this->getUser();

        if ($user != $event->getUser()) {
            throw new AccessDeniedException('You are not the owner!!!');
        }
    }

}
