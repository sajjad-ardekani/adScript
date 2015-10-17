<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdFormType
 *
 * @author diba
 */
class AdFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add("title")
                ->add("description")
                ->add("city")
                ->add("district", array('query_builder' => function(EntityRepository $er ) {
                        return $er->createQueryBuilder('w')
                                ->where('w.city = ?1')
                                ->setParameter(1, $caravan);
                    }))
                ->add("price")
                ->add("categories")
                ->add("email")
                ->add("phonenumber");
    }

    public function getName() {
        return 'app_ad_form';
    }

}
