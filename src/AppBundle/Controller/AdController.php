<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Entity\Image;
use AppBundle\Form\AdFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdController extends Controller {

    /**
     * @Route("/demo", name="demo")
     */
    public function demoAction(Request $request) {
        $ad = new Ad();
        $value = new \AppBundle\Entity\IntegerValue();
        $em = $this->getDoctrine()->getManager();
        $req = $request->get('a');
        $reqArr = json_decode($req);

        foreach ($reqArr as $val) {

            $value->setValue($val->value);
            $value->setAd($ad);
            $value->setType($value);
            $em->persist($value);
        }
        $em->flush();

        return $this->redirectToRoute("new-ad");
    }

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
            $em = $this->getDoctrine()->getManager();
            $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');
            $files = $manager->uploadFiles();
            foreach ($files as $file) {
                $image = new Image();
                $image->setFilename($file->getfileName());
                $em->persist($image);
                $image->setAd($ad);
            }
            $ad->setUser($this->getUser());
            $ad->setUser($user);
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute("app-property-category", ['id' => $ad->getCategories()->getId(), 'adId' => $ad->getId()]);
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
            $response = new JsonResponse($sub_category);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('Ad/ajaxload.html.twig', array(
                    'dis' => $sub_category
        ));
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
            $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');
            $files = $manager->uploadFiles();
            foreach ($files as $file) {
                $image = new Image();
                $image->setFilename($file->getfileName());
                $em->persist($image);
                $image->setAd($ad);
            }
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
     * @Route("/{id}/delete-image", name="delete_image")
     * @ParamConverter("image", class="AppBundle:Image")
     *
     */
    public function deleteImageAction(Image $image, Request $request) {
//        $this->enforceOwnerSecurity($ad);     SECURITY
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository("AppBundle:Image")->find($image);
        $em->remove($images);
        $em->flush();
        return $this->redirectToRoute("edit-ad", ["id" => $image->getAd()->getId()]);
//        return $this->redirectToRoute("homepage");
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

    /**
     * @Route("/new/{id}", name="app-property-category")
     * @ParamConverter("category", class="AppBundle\Entity\Category")
     */
    public function createCategoryPropertyAction(Request $request, $category, Ad $adId) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository("AppBundle:Type")->findBy(['category' => $category]);
        $formBuilder = $this->createFormBuilder();

        foreach ($types as $type) {
            switch ($type->getType()) {
                case "integer":
                    $fieldType = "text";
                    $formBuilder->add("prop_" . $type->getId(), $fieldType, ['label' => $type->getName()]);
                    break;
                case "string":
                    $fieldType = "textarea";
                    $formBuilder->add("prop_" . $type->getId(), $fieldType, ['label' => $type->getName()]);
                    break;
                case "enum":
                    $fieldType = "choise";
                    $formBuilder->add("prop_" . $type->getId(), $fieldType, [
                        'label' => $type->getName(),
                        "choices" => array_combine($type->getOptions(), $type->getOptions())
                    ]);
                    break;
            }
        }
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $ad = $em->getRepository("AppBundle:Ad")->find($adId);
            foreach ($data as $k => $v) {
                list(, $id) = explode("_", $k);
                $type = $this->getDoctrine()->getRepository("AppBundle:Type")->find($id);

                switch ($type->getType()) {
                    case 'string':
                        $value = new \AppBundle\Entity\StringValue();
                        break;
                    case 'integer':
                        $value = new \AppBundle\Entity\IntegerValue();
                        break;
//                    case 'enum':
//                        $value = new \AppBundle\Entity\EnumValue();
//                        break;
                    default :
                        throw new \Exception;
                }
                $value->setAd($ad);
                $value->setType($type);
                $value->setValue($v);
                $em->persist($value);
            }
            $em->flush();
        }
        return $this->render("category/form.html.twig", array("form" => $form->createView())
        );
    }

    /**
     * @Route("/search/{id}")
     * @ParamConverter("category", class="AppBundle\Entity\Category")
     */
    public function searchAction(Request $request, $category) {
        $form = $this->createFormBuilder()
                ->add("search")
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            $query = "SELECT iv,t,ad FROM AppBundle:IntegerValue iv "
                    . " JOIN iv.type t "
                    . " JOIN iv.ad ad "
                    . " WHERE  t.category=:cat AND iv.value=:val";
            $result = $this->getDoctrine()->getEntityManager()
                    ->createQuery($query)
                    ->setParameter("val", $data['search'])
                    ->setParameter("cat", $category)
                    ->getResult();
//            $query = "SELECT iv,t,ad FROM AppBundle:EnumValue iv "
//                    . " JOIN iv.type t "
//                    . " JOIN iv.ad ad "
//                    . " WHERE  t.category=:cat AND iv.value=:val";
//            $result2 = $this->getDoctrine()->getEntityManager()
//                    ->createQuery($query)
//                    ->setParameter("val", $data['search'])
//                    ->setParameter("cat", $category)
//                    ->getResult();
        } else {
            $result = null;
        }

        return $this->render("Page/search.html.twig", array("form" => $form->createView(),
                    "category" => $category,
                    "result" => $result,
//                    "result2" => $result2
                )
                );
    }

    private function enforceOwnerSecurity(Ad $event) {
        $user = $this->getUser();

        if ($user != $event->getUser()) {
            throw new AccessDeniedException('You are not the owner!!!');
        }
    }

}
