<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller {

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction() {
        $em = $this->getDoctrine()->getManager();

        $query_ad_approved = $em->createQuery('SELECT COUNT(u)
            FROM AppBundle:Ad u
            WHERE u.status = :status');
        $ad_approved_count = $query_ad_approved
                ->setParameter("status", 1)
                ->getSingleScalarResult();

        $query_ad_deactive = $em->createQuery('SELECT COUNT(u)
            FROM AppBundle:Ad u
            WHERE u.status = :status');
        $ad_deactive_count = $query_ad_deactive
                ->setParameter("status", 0)
                ->getSingleScalarResult();

        $query_user_active = $em->createQuery('SELECT COUNT(u)
            FROM AppBundle:User u
            WHERE u.enabled = :enabled');
        $user_active_count = $query_user_active
                ->setParameter("enabled", 1)
                ->getSingleScalarResult();

        $query_user_deactive = $em->createQuery('SELECT COUNT(u)
            FROM AppBundle:User u
            WHERE u.enabled = :enabled');
        $user_deactive_count = $query_user_deactive
                ->setParameter("enabled", 0)
                ->getSingleScalarResult();
        $query_payment_waiting = $em->createQuery('SELECT COUNT(a)
            FROM AppBundle:Ad a
            WHERE a.status = :status');
        $ad_payment_count = $query_payment_waiting
                ->setParameter("status", 2)
                ->getSingleScalarResult();
        $query_denied_Ad = $em->createQuery('SELECT COUNT(a)
            FROM AppBundle:Ad a
            WHERE a.status = :status');
        $denied_Ad_count = $query_denied_Ad
                ->setParameter("status", 3)
                ->getSingleScalarResult();


        return $this->render('admin/dashboard.html.twig', array(
                    "approvedNumber" => $ad_approved_count,
                    "deActiveAdNumber" => $ad_deactive_count,
                    "userActive" => $user_active_count,
                    "userDeactive" => $user_deactive_count,
                    "paymentAdNumber" => $ad_payment_count,
                    "deniedAdNumber" => $denied_Ad_count,
        ));
    }

    /**
     * @Route("/approved-ad", name="approved_ads")
     */
    public function approvedAdsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $limited = 10;
        $ads = $em->getRepository("AppBundle:Ad")->getAdsApproved($limited);

        return $this->render('admin/approved_ads.html.twig', array(
                    "ads" => $ads
        ));
    }

    /**
     * @Route("/deactive-ad/{id}", name="deactive_ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     */
    public function deactiveAdAction(Ad $ad) {
        $em = $this->getDoctrine()->getManager();
        $ad->setStatus(0);
        $em->persist($ad);
        $em->flush();
        return $this->redirectToRoute("approved_ads");
    }

    /**
     * @Route("/deactive-ads", name="deactive_ads")
     */
    public function deactiveAdsAction() {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository("AppBundle:Ad")->getAdsNotApproved();
        return $this->render('admin/deactive_ads.html.twig', array(
                    "ads" => $ads
        ));
    }

    /**
     * @Route("/show-denied-ads", name="show_denied_ads")
     */
    public function showDeniedAdsAction() {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository("AppBundle:Ad")->findAllAdsDenied();
        return $this->render('admin/denaid_ads.html.twig', array(
                    "ads" => $ads
        ));
    }

    /**
     * @Route("/active-ad/{id}", name="active_ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     */
    public function activeAdAction(Ad $ad) {
        $em = $this->getDoctrine()->getManager();
        $ad->setStatus(1);
        $em->persist($ad);
        $em->flush();
        return $this->redirectToRoute("deactive_ads");
    }

    /**
     * @Route("/denied-ad/{id}", name="denied_ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     */
    public function deniedAdsAction(Ad $ad) {
        $em = $this->getDoctrine()->getManager();
        $ad->setStatus(3);
        $em->persist($ad);
        $em->flush();
        return $this->redirectToRoute("denied_ad_description", ['id' => $ad->getId()]);
    }

    /**
     * @Route("/denied-ad-des/{id}", name="denied_ad_description")
     * @ParamConverter("ad", class="AppBundle:Ad")
     */
    public function deniedAdsDescriptionAction(Ad $ad, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $ad_denied = new \AppBundle\Entity\AdDenied();
        $form = $this->createFormBuilder($ad_denied)
                ->add("description")
                ->add('reason', 'choice', array(
                    'choices' => array(
                        'reason 1' => 'reason 1',
                        'reason 2' => 'reason 2',
                        'reason 3' => 'reason 3',
                        'reason 4' => 'reason 4'),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => "به دلیل: ",
                ))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $ad_denied = $form->getData();
            $ad_denied->setAd($ad);
            $em->persist($ad_denied);
            $em->flush();
            return $this->redirectToRoute("show_denied_ads");
        }
        return $this->render('admin/ad_denied_description.html.twig', array(
                    "form" => $form->createView()
        ));
    }

    /**
     * @Route("/user-active", name="user_active")
     */
    public function userActiveAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAllUserEnabled();
        return $this->render('admin/active_user.html.twig', array(
                    "users" => $users
        ));
    }

    /**
     * @Route("/user-active/{id}", name="useractive")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function activeUserAction(User $user) {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(TRUE);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("user_deactive");
    }

    /**
     * @Route("/user-deactive", name="user_deactive")
     */
    public function userDeactiveAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAllUserdisabled();
        return $this->render('admin/deactive_user.html.twig', array(
                    "users" => $users
        ));
    }

    /**
     * @Route("/user-deactive/{id}", name="userdeactive")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function deactiveUserAction(User $user) {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(False);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("user_active");
    }

    /**
     * @Route("/add-category", name="add_category")
     */
    public function addCategoryAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = new Category();
        $categories = $em->getRepository("AppBundle:Category")->findAll();

        $form = $this->createFormBuilder($category)->add("name")
                ->add("parent")
                ->add('free', 'choice', array(
                    'choices' => array(1 => 'Yes', 0 => 'No'),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => "نیاز به پرداخت؟ ",
                ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute("add_category");
        }


        return $this->render('admin/add_category.html.twig', array(
                    "categories" => $categories,
                    "form" => $form->createView()
        ));
    }

    /**
     * @Route("/add-category/{id}", name="del_category")
     * @ParamConverter("category", class="AppBundle:Category")
     */
    public function delCategoryAction($category) {
        $em = $this->getDoctrine()->getManager();

        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("add_category");
    }

    /**
     * @Route("/payment-waiting", name="payment_waiting")
     */
    public function paymentWaitingAction() {
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository("AppBundle:Ad")->findAllAdWaitingForPayment();
        return $this->render('admin/payment_ad.html.twig', array(
                    "ads" => $ads
        ));
    }

}
