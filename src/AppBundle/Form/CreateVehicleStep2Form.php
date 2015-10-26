<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateVehicleStep2Form extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('images');
    }

    public function getName() {
        return 'createVehicleStep2';
    }

}
