<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                ->add('district')
                ->add("price")
                ->add("categories")
                ->add("email")
                ->add("phonenumber");
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'clas_data' => "AppBundle/Entity/Ad"
        ));
    }

    public function getName() {
        return 'app_ad_form';
    }

}
