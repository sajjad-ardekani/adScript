<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ad;
use AppBundle\Entity\EnumValue;
use AppBundle\Entity\Image;
use AppBundle\Entity\IntegerValue;
use AppBundle\Entity\Payment;
use AppBundle\Entity\StringValue;
use AppBundle\Form\AdFormType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SoapClient;

class AdController extends Controller {

    /**
     * @Route("/demo", name="demo",requirements={"_method" = "POST"})
     */
    public function demoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $ad = new Ad();
        $this->setDataAd($request, $em, $ad);
        $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');

        $files = $manager->uploadFiles();
        foreach ($files as $file) {
            $image = new Image();
            $image->setFilename($file->getfileName());
            $em->persist($image);
            $image->setAd($ad);
        }
        $em->persist($ad);
        $em->flush();
        $message = \Swift_Message::newInstance()
                ->setSubject('مدیریت آگهی شما در دیوارچه')
                ->setFrom('no-Replay@local.com')
                ->setTo('sajjad.ardekani@gmail.com')
                ->setBody($this->renderView(
                        'Emails/add-ad.html.twig', array('ad' => $ad)
                ), 'text/html');
        $this->get('mailer')->send($message);
        return new Response($ad->getId());
    }

    /**
     * @Route("/new", name="new-ad")
     */
    public function newAction(Request $request) {
        $ad = new Ad();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new AdFormType($this->getDoctrine()->getManager()), $ad);

        $categories = $em->getRepository("AppBundle:Category")->queryGetCategory();

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
    public function detailsAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository("AppBundle:Ad")->find($id);
        $types = $em->getRepository("AppBundle:Type")->findBy(['category' => $ad->getCategories()]);
        foreach ($types as $type) {

            switch ($type->getType()) {

                case "integer":
                    $value = $em->getRepository("AppBundle:IntegerValue")->findBy(['ad' => $ad]);

                    break;
                case "string":
                    $value = $em->getRepository("AppBundle:StringValue")->findBy(['ad' => $ad]);

                    break;
                case "enum":
                    $value = $em->getRepository("AppBundle:EnumValue")->findBy(['ad' => $ad]);
                    break;
            }
        }
        $user = $this->container->get('security.context')->getToken()->getUser();

        $view = $ad->getView();

        $ad->setView($view + 1);
        $em->persist($ad);
        $em->flush();

        if (isset($value)) {
            return $this->render('ad/details.html.twig', array(
                        "ad" => $ad,
                        "user" => $user,
                        "value" => $value));
        } else {
            return $this->render('ad/details.html.twig', array(
                        "ad" => $ad,
                        "user" => $user));
        }
    }

    /**
     * @Route("/{id}/edit-ad", name="edit-ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     *
     */
    public function editAction(Ad $ad, Request $request) {

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
        $em->remove($image);
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
                    $fieldType = "choice";
                    $formBuilder->add("prop_" . $type->getId(), $fieldType, [
                        'label' => $type->getName(),
                        "choices" => array_combine($type->getOptions(), $type->getOptions())
                    ]);
                    break;
            }
        }
        $form = $formBuilder->getForm();

        return $this->render("category/form.html.twig", array("form" => $form->createView(),
                    "category" => $category)
        );
    }

    /**
     * @Route("/{id}/payment", name="payment_ad")
     * @ParamConverter("ad", class="AppBundle:Ad")
     *
     */
    public function paymentAction(Ad $ad, Request $request) {

//        $this->enforceOwnerSecurity($ad);     SECURITY
        $form = $this->createFormBuilder($ad)
                ->add('payment')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $value = $form["payment"]->getData();

            $client = new SoapClient("http://www.jahanpay.com/webservice?wsdl");
            $api = "gt26162g543";
            $amount = $value->getAmount();
            $callbackUrl = "http://localhost/adscript/web/app_dev.php/verify-payment?PayId=" . $value->getId();
            $orderId = $ad->getId();
            $txt = urlencode($value->getName());
            $res = $client->requestpayment($api, $amount, $callbackUrl, $orderId, $txt);

            $response = new Response();
            $response->setStatusCode(200);
            $response->headers->set('Refresh', '5; url=http://www.jahanpay.com/pay_invoice/' . $res);
            $response->send();
        }
        return $this->render("ad/payment_form.html.twig", array(
                    "form" => $form->createView(),
                    "ad" => $ad));
    }

    /**
     * @Route("/verify-payment", name="verify_payment")
     *
     */
    public function verifyPaymentAction(Request $request) {

        $adId = $request->get('order_id');
        $PayId = $request->get('PayId');

        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository("AppBundle:Ad")->find($adId);
        $payment = $em->getRepository("AppBundle:Payment")->find($PayId);

        $au = $request->get('au');

        $api = "gt26162g543";
        $amount = $payment->getAmount(); //Tooman

        $client = new SoapClient("http://www.jahanpay.com/webservice?wsdl");
        $result = $client->verification($api, $amount, $au);

        if (!empty($result) and $result == 1) {
            echo "پرداخت با موفقیت انجام شده است .<br>";
            $ad->setStatus(0);
            $ad->setPayment($payment);
            $em->persist($ad);
            $em->flush();
        } else {
            echo "خطای شماره " . $result . '<br>';
        }


        $errorCode = array(
            -20 => 'api نامعتبر است',
            -21 => 'آی پی نامعتبر است',
            -22 => 'مبلغ از کف تعریف شده کمتر است',
            -23 => 'مبلغ از سقف تعریف شده بیشتر است',
            -24 => 'مبلغ نامعتبر است',
            -6 => 'ارتباط با بانک برقرار نشد',
            -26 => 'درگاه غیرفعال است',
            -27 => 'آی پی شما مسدود است',
            -9 => 'خطای ناشناخته',
            -29 => 'آدرس کال بک خالی است ',
            -30 => 'چنین تراکنشی یافت نشد',
            -31 => 'تراکنش انجام نشده ',
            -32 => 'تراکنش انجام شده اما مبلغ نادرست است ',
                //1 => "تراکنش با موفقیت انجام شده است " ,
        );


        return $this->render("ad/payment_ref.html.twig", array(
                    "au" => $au,
                    "ad" => $ad));
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

            $query = "SELECT iv,t,ad FROM AppBundle:StringValue iv "
                    . " JOIN iv.type t "
                    . " JOIN iv.ad ad "
                    . " WHERE  t.category=:cat AND iv.value=:val";
            $result = $this->getDoctrine()->getEntityManager()
                    ->createQuery($query)
                    ->setParameter("val", $data['search'])
                    ->setParameter("cat", $category)
                    ->getResult();
            $query = "SELECT iv,t,ad FROM AppBundle:EnumValue iv "
                    . " JOIN iv.type t "
                    . " JOIN iv.ad ad "
                    . " WHERE  t.category=:cat AND iv.value=:val";
            $result2 = $this->getDoctrine()->getEntityManager()
                    ->createQuery($query)
                    ->setParameter("val", $data['search'])
                    ->setParameter("cat", $category)
                    ->getResult();
            $query = "SELECT iv,t,ad FROM AppBundle:IntegerValue iv "
                    . " JOIN iv.type t "
                    . " JOIN iv.ad ad "
                    . " WHERE  t.category=:cat AND iv.value=:val";
            $result3 = $this->getDoctrine()->getEntityManager()
                    ->createQuery($query)
                    ->setParameter("val", $data['search'])
                    ->setParameter("cat", $category)
                    ->getResult();
            
        } else {
            $result = null;
            $result2 = null;
            $result3 = null;
        }

        return $this->render("Page/search.html.twig", array("form" => $form->createView(),
                    "category" => $category,
                    "result" => $result,
                   "result2" => $result2,
                   "result3" => $result3
                        )
        );
    }

    private function setDataAd(Request $request, EntityManager $em, Ad $ad) {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $json_ad = $request->get('ad');
        $json_decode_ad = json_decode($json_ad);
        $city = $em->getRepository("AppBundle:City")->find($json_decode_ad->city);
        $district = $em->getRepository("AppBundle:District")->find($json_decode_ad->district);
        $category = $em->getRepository("AppBundle:Category")->find($json_decode_ad->categories);
        if ($category->getFree() == 1) {
            $ad->setStatus(2);
        }

        $ad->setTitle($json_decode_ad->title);
        $ad->setDescription($json_decode_ad->description);
        $ad->setPrice($json_decode_ad->price);
        $ad->setCity($city);
        $ad->setDistrict($district);
        $ad->setCategories($category);
        $ad->setEmail($json_decode_ad->email);
        $ad->setPhonenumber($json_decode_ad->phonenumber);
        $ad->setUser($user);

        $json_property = $request->get('property');
        $json_decode_property = json_decode($json_property);


        foreach ($json_decode_property as $val) {

            $type_id = substr($val->name, -2, 1);
            $type = $em->getRepository("AppBundle:Type")->find($type_id);

            switch ($type->getType()) {
                case 'string':
                    $value = new StringValue();
                    break;
                case 'integer':
                    $value = new IntegerValue();
                    break;
                case 'enum':
                    $value = new EnumValue();
                    break;
                default :
                    throw new \Exception;
            }
            $value->setAd($ad);
            $value->setType($type);
            $em->persist($value);
            $value->setValue($val->value);
        }
    }

    private function enforceOwnerSecurity(Ad $event) {
        $user = $this->getUser();

        if ($user != $event->getUser()) {
            throw new AccessDeniedException('You are not the owner!!!');
        }
    }

}
