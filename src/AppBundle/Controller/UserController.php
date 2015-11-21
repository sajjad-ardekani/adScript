<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * @Route("/post-ad/{id}", name="user_post_ad")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function userPostAdAction(User $user) {
        $this->enforceOwnerSecurity($user);
        $em = $this->getDoctrine()->getManager();

        $ads = $em->getRepository("AppBundle:Ad")->findBy(['user' => $user]);

        return $this->render('user/user_ad_post.html.twig', array(
                    "ads" => $ads
        ));
    }
     /**
     * @Route("/{id}/details-ad-user", name="ad_details_user")
     *
     */
    public function detailsAction($id) {
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
        if (isset($value)) {
            return $this->render('user/details_ad_user.html.twig', array(
                        "ad" => $ad,
                        "user" => $user,
                        "value" => $value));
        } else {
            return $this->render('user/details_ad_user.html.twig', array(
                        "ad" => $ad,
                        "user" => $user));
        }
    }


    private function enforceOwnerSecurity(User $user) {
        $user_login = $this->container->get('security.context')->getToken()->getUser();

        if ($user_login != $user) {
            throw new AccessDeniedException('You are not the owner!!!');
        }
    }

}
